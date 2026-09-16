<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Services;

use App\Exceptions\RecomendacionIAException;
use App\Models\PerfilPreferencias;
use App\Models\Planta;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
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

        $apiKey = config('services.gemini.key');
        $baseUrl = rtrim((string) config('services.gemini.base_url'), '/');
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

        $url = "{$baseUrl}/models/{$modelo}:generateContent";

        try {
            $respuesta = Http::timeout(30)
                ->withoutVerifying()
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                ])
                ->acceptJson()
                ->post($url, [
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $instrucciones],
                        ],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $contenidoUsuario],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'responseMimeType' => 'application/json',
                        'responseSchema' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'explicacion' => [
                                    'type' => 'STRING',
                                ],
                                'plantas' => [
                                    'type' => 'ARRAY',
                                    'items' => [
                                        'type' => 'OBJECT',
                                        'properties' => [
                                            'id' => ['type' => 'INTEGER'],
                                            'motivo' => ['type' => 'STRING'],
                                        ],
                                        'required' => ['id', 'motivo'],
                                    ],
                                ],
                            ],
                            'required' => ['explicacion', 'plantas'],
                        ],
                    ],
                ]);
        } catch (Throwable $excepcion) {
            Log::warning('Fallo de conexión con Gemini para recomendaciones.', [
                'mensaje' => $excepcion->getMessage(),
            ]);

            throw new RecomendacionIAException(
                __('messages.recomendaciones_error_servicio')
            );
        }

        if (! $respuesta->successful()) {
            Log::warning('Gemini respondió con error al generar recomendaciones.', [
                'status' => $respuesta->status(),
                'cuerpo' => $respuesta->body(),
            ]);

            throw new RecomendacionIAException(
                __('messages.recomendaciones_error_servicio')
            );
        }

        $contenido = data_get($respuesta->json(), 'candidates.0.content.parts.0.text');

        if (! is_string($contenido) || blank($contenido)) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_respuesta_invalida')
            );
        }

        $datos = json_decode($contenido, true);

        if (! is_array($datos)) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_respuesta_invalida')
            );
        }

        return $this->validarYMapear($datos, $plantasDisponibles);
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
