<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Http\Controllers;

use App\Exceptions\RecomendacionIAException;
use App\Http\Requests\GuardarPreferenciasRequest;
use App\Models\Planta;
use App\Models\Recomendacion;
use App\Services\RecomendacionIAService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RecomendacionController extends Controller
{
    public function index(): View
    {
        $usuario = auth()->user();
        $perfil = $usuario->perfilPreferencias;
        $recomendaciones = $usuario->recomendaciones()
            ->with(['plantas.categoria'])
            ->latest()
            ->limit(5)
            ->get();

        return view('recomendaciones.index', compact('perfil', 'recomendaciones'));
    }

    public function guardarPreferencias(GuardarPreferenciasRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        auth()->user()->perfilPreferencias()->updateOrCreate(
            ['usuario_id' => auth()->id()],
            $datos
        );

        return redirect()
            ->route('recomendaciones.index')
            ->with('mensaje', __('messages.preferencias_guardadas'));
    }

    public function generar(RecomendacionIAService $servicio): RedirectResponse
    {
        $usuario = auth()->user();
        $perfil = $usuario->perfilPreferencias;

        if (! $perfil || ! $perfil->estaCompleto()) {
            return redirect()
                ->route('recomendaciones.index')
                ->with('error', __('messages.preferencias_requeridas'));
        }

        $plantasDisponibles = Planta::query()
            ->with('categoria')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        try {
            $resultado = $servicio->generar($perfil, $plantasDisponibles);
        } catch (RecomendacionIAException $excepcion) {
            return redirect()
                ->route('recomendaciones.index')
                ->with('error', $excepcion->getMessage());
        }

        $recomendacion = DB::transaction(function () use ($usuario, $resultado) {
            $recomendacion = Recomendacion::create([
                'usuario_id' => $usuario->id,
                'explicacion' => $resultado['explicacion'],
            ]);

            $sincronizacion = [];

            foreach ($resultado['plantas'] as $indice => $item) {
                $sincronizacion[$item['planta']->id] = [
                    'motivo' => $item['motivo'],
                    'orden' => $indice + 1,
                ];
            }

            $recomendacion->plantas()->sync($sincronizacion);

            return $recomendacion;
        });

        return redirect()
            ->route('recomendaciones.show', $recomendacion)
            ->with('mensaje', __('messages.recomendaciones_generadas'));
    }

    public function show(Recomendacion $recomendacion): View
    {
        abort_unless($recomendacion->usuario_id === auth()->id(), 403);

        $recomendacion->load(['plantas.categoria']);

        return view('recomendaciones.show', compact('recomendacion'));
    }
}
