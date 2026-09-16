<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Services;

use App\Exceptions\RecomendacionIAException;
use App\Models\PerfilPreferencias;
use App\Models\Planta;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\Schema;
use Gemini\Enums\DataType;
use Gemini\Enums\ResponseMimeType;
use Gemini\Exceptions\ErrorException;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Responses\GenerativeModel\GenerateContentResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class RecomendacionIAService
{
    /**
     * Solicita recomendaciones a Gemini y valida que solo
     * se incluyan plantas existentes en el catálogo enviado.
     *
     * @param  Collection<int, Planta>  $plantasDisponibles
     * @return array{explicacion: string, plantas: list<array{planta: Planta, motivo: string}>}
     *
     * @throws RecomendacionIAException
     */
    public function generar(PerfilPreferencias $perfil, Collection $plantasDisponibles): array
    {
        if ($plantasDisponibles->isEmpty()) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_sin_plantas')
            );
        }

        $apiKey = config('gemini.api_key') ?: config('services.gemini.key');
        $modelo = (string) config('services.gemini.model');

        if (blank($apiKey)) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_sin_credenciales')
            );
        }

        $catalogo = $plantasDisponibles->map(fn (Planta $planta) => [
            'id' => $planta->id,
            'nombre' => $planta->nombre,
            'descripcion' => $planta->descripcion,
            'categoria' => $planta->categoria?->nombre,
            'precio' => $planta->precio,
            'stock' => $planta->stock,
        ])->values()->all();

        $preferencias = [
            'experiencia' => $perfil->experiencia,
            'espacio' => $perfil->espacio,
            'iluminacion' => $perfil->iluminacion,
            'tiempo_cuidado' => $perfil->tiempo_cuidado,
            'mascotas' => $perfil->mascotas,
        ];

        $instrucciones = <<<'PROMPT'
Eres un asesor de una tienda de plantas. Debes recomendar únicamente plantas del catálogo recibido.
Responde solo con JSON válido, sin markdown, con esta forma:
{"explicacion":"texto breve para el cliente","plantas":[{"id":1,"motivo":"por qué encaja"}]}
Reglas:
- Usa solo IDs presentes en el catálogo.
- Recomienda entre 2 y 4 plantas si es posible.
- No inventes plantas ni IDs.
- Considera experiencia, espacio, iluminación, tiempo de cuidado y mascotas.
PROMPT;

        $contenidoUsuario = json_encode([
            'preferencias' => $preferencias,
            'catalogo' => $catalogo,
        ], JSON_UNESCAPED_UNICODE);

        try {
            $respuesta = $this->solicitarAGemini($modelo, $instrucciones, $contenidoUsuario);
        } catch (Throwable $excepcion) {
            Log::warning('Fallo al consultar Gemini para recomendaciones.', [
                'mensaje' => $excepcion->getMessage(),
            ]);

            throw new RecomendacionIAException(
                __('messages.recomendaciones_error_servicio')
            );
        }

        try {
            $datos = $respuesta->json(associative: true);
        } catch (Throwable $excepcion) {
            Log::warning('Gemini devolvió una respuesta sin contenido usable.', [
                'mensaje' => $excepcion->getMessage(),
            ]);

            throw new RecomendacionIAException(
                __('messages.recomendaciones_respuesta_invalida')
            );
        }

        if (! is_array($datos)) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_respuesta_invalida')
            );
        }

        return $this->validarYMapear($datos, $plantasDisponibles);
    }

    /**
     * @throws Throwable
     */
    private function solicitarAGemini(
        string $modelo,
        string $instrucciones,
        string $contenidoUsuario
    ): GenerateContentResponse {
        $configuracion = new GenerationConfig(
            temperature: 0.4,
            responseMimeType: ResponseMimeType::APPLICATION_JSON,
            responseSchema: new Schema(
                type: DataType::OBJECT,
                properties: [
                    'explicacion' => new Schema(type: DataType::STRING),
                    'plantas' => new Schema(
                        type: DataType::ARRAY,
                        items: new Schema(
                            type: DataType::OBJECT,
                            properties: [
                                'id' => new Schema(type: DataType::INTEGER),
                                'motivo' => new Schema(type: DataType::STRING),
                            ],
                            required: ['id', 'motivo'],
                        ),
                    ),
                ],
                required: ['explicacion', 'plantas'],
            ),
        );

        return retry(
            times: 3,
            callback: fn () => Gemini::generativeModel(model: $modelo)
                ->withSystemInstruction(Content::parse($instrucciones))
                ->withGenerationConfig($configuracion)
                ->generateContent($contenidoUsuario),
            sleepMilliseconds: app()->runningUnitTests()
                ? 0
                : fn (int $intento) => $intento * 1000,
            when: function (Throwable $excepcion): bool {
                Log::warning('Gemini respondió con error al generar recomendaciones.', [
                    'mensaje' => $excepcion->getMessage(),
                ]);

                if ($excepcion instanceof ErrorException) {
                    return in_array($excepcion->getErrorCode(), [429, 500, 502, 503], true);
                }

                return true;
            },
        );
    }

    /**
     * @param  Collection<int, Planta>  $plantasDisponibles
     * @return array{explicacion: string, plantas: list<array{planta: Planta, motivo: string}>}
     *
     * @throws RecomendacionIAException
     */
    private function validarYMapear(array $datos, Collection $plantasDisponibles): array
    {
        $explicacion = trim((string) ($datos['explicacion'] ?? ''));
        $items = $datos['plantas'] ?? null;

        if ($explicacion === '' || ! is_array($items) || $items === []) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_respuesta_invalida')
            );
        }

        $porId = $plantasDisponibles->keyBy('id');
        $recomendadas = [];
        $idsVistos = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $plantaId = (int) ($item['id'] ?? 0);
            $motivo = trim((string) ($item['motivo'] ?? ''));

            if ($plantaId <= 0 || isset($idsVistos[$plantaId])) {
                continue;
            }

            $planta = $porId->get($plantaId);

            if (! $planta) {
                continue;
            }

            $idsVistos[$plantaId] = true;
            $recomendadas[] = [
                'planta' => $planta,
                'motivo' => $motivo !== ''
                    ? $motivo
                    : __('messages.recomendaciones_motivo_default'),
            ];
        }

        if ($recomendadas === []) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_sin_coincidencias')
            );
        }

        return [
            'explicacion' => $explicacion,
            'plantas' => $recomendadas,
        ];
    }
}
