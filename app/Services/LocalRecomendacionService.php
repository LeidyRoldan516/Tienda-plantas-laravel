<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Services;

use App\Exceptions\RecomendacionIAException;
use App\Interfaces\RecomendacionServiceInterface;
use App\Models\PerfilPreferencias;
use App\Models\Planta;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Recomendador de respaldo que no depende de servicios externos.
 * Puntúa cada planta según su categoría y palabras clave de su descripción.
 */
class LocalRecomendacionService implements RecomendacionServiceInterface
{
    private const MAXIMO_PLANTAS = 3;

    /** Categorías adecuadas para cada tipo de espacio. */
    private const CATEGORIAS_POR_ESPACIO = [
        'interior_pequeno' => ['interior', 'suculentas'],
        'interior_amplio' => ['interior'],
        'oficina' => ['interior', 'suculentas'],
        'balcon' => ['aromaticas', 'exterior', 'suculentas'],
        'jardin' => ['exterior', 'aromaticas'],
    ];

    /** Palabras clave de la descripción asociadas a cada nivel de luz. */
    private const PALABRAS_POR_ILUMINACION = [
        'baja' => ['poca luz', 'luz suave', 'resistente'],
        'media' => ['indirecta', 'filtrada', 'luz suave'],
        'alta' => ['sol', 'mucha luz', 'luminos', 'abundante'],
    ];

    /** Palabras clave que indican bajo mantenimiento. */
    private const PALABRAS_FACIL_CUIDADO = [
        'facil', 'resistente', 'bajo mantenimiento', 'poca agua', 'sencillo',
    ];

    /** Palabras clave que indican cuidados exigentes. */
    private const PALABRAS_CUIDADO_EXIGENTE = [
        'humedad', 'riego frecuente', 'riego regular',
    ];

    public function generar(PerfilPreferencias $perfil, Collection $plantasDisponibles): array
    {
        if ($plantasDisponibles->isEmpty()) {
            throw new RecomendacionIAException(
                __('messages.recomendaciones_sin_plantas')
            );
        }

        $evaluadas = $plantasDisponibles
            ->map(fn (Planta $planta) => $this->evaluar($perfil, $planta))
            ->sortByDesc(fn (array $item) => [$item['puntaje'], $item['planta']->stock])
            ->values();

        $seleccionadas = $evaluadas->where('puntaje', '>', 0)->take(self::MAXIMO_PLANTAS);

        if ($seleccionadas->isEmpty()) {
            $seleccionadas = $evaluadas->take(self::MAXIMO_PLANTAS);
        }

        return [
            'explicacion' => __('messages.recomendaciones_local_explicacion'),
            'plantas' => $seleccionadas->map(fn (array $item) => [
                'planta' => $item['planta'],
                'motivo' => $item['motivos'] !== []
                    ? implode(' ', $item['motivos'])
                    : __('messages.recomendaciones_motivo_default'),
            ])->values()->all(),
        ];
    }

    /**
     * @return array{planta: Planta, puntaje: int, motivos: list<string>}
     */
    private function evaluar(PerfilPreferencias $perfil, Planta $planta): array
    {
        $categoria = $this->normalizar((string) $planta->categoria?->nombre);
        $descripcion = $this->normalizar((string) $planta->descripcion);
        $puntaje = 0;
        $motivos = [];

        $categoriasEspacio = self::CATEGORIAS_POR_ESPACIO[$perfil->getEspacio()] ?? [];
        if (in_array($categoria, $categoriasEspacio, true)) {
            $puntaje += 3;
            $motivos[] = __('messages.recomendaciones_local_motivo_espacio');
        }

        $palabrasLuz = self::PALABRAS_POR_ILUMINACION[$perfil->getIluminacion()] ?? [];
        if ($this->contieneAlguna($descripcion, $palabrasLuz)) {
            $puntaje += 2;
            $motivos[] = __('messages.recomendaciones_local_motivo_luz');
        }

        $buscaFacil = $perfil->getExperiencia() === 'principiante'
            || $perfil->getTiempoCuidado() === 'bajo';

        if ($buscaFacil && $this->contieneAlguna($descripcion, self::PALABRAS_FACIL_CUIDADO)) {
            $puntaje += 2;
            $motivos[] = __('messages.recomendaciones_local_motivo_cuidado');
        }

        if ($buscaFacil && $this->contieneAlguna($descripcion, self::PALABRAS_CUIDADO_EXIGENTE)) {
            $puntaje -= 1;
        }

        if ($perfil->getTiempoCuidado() === 'alto'
            && $this->contieneAlguna($descripcion, self::PALABRAS_CUIDADO_EXIGENTE)) {
            $puntaje += 1;
            $motivos[] = __('messages.recomendaciones_local_motivo_dedicacion');
        }

        return [
            'planta' => $planta,
            'puntaje' => $puntaje,
            'motivos' => $motivos,
        ];
    }

    /**
     * @param  list<string>  $palabras
     */
    private function contieneAlguna(string $texto, array $palabras): bool
    {
        return Str::contains($texto, $palabras);
    }

    private function normalizar(string $texto): string
    {
        return Str::lower(Str::ascii($texto));
    }
}
