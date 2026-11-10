<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Interfaces;

use App\Exceptions\RecomendacionIAException;
use App\Models\PerfilPreferencias;
use App\Models\Planta;
use Illuminate\Support\Collection;

/**
 * Contrato para generar recomendaciones de plantas a partir del perfil del cliente.
 *
 * Implementaciones:
 *  - GeminiRecomendacionService: consulta el servicio externo de Google Gemini.
 *  - LocalRecomendacionService: reglas locales usadas como respaldo.
 */
interface RecomendacionServiceInterface
{
    /**
     * @param  Collection<int, Planta>  $plantasDisponibles
     * @return array{explicacion: string, plantas: list<array{planta: Planta, motivo: string}>}
     *
     * @throws RecomendacionIAException
     */
    public function generar(PerfilPreferencias $perfil, Collection $plantasDisponibles): array;
}
