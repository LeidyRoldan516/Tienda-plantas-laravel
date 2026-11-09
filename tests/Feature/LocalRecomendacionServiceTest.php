<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace Tests\Feature;

use App\Exceptions\RecomendacionIAException;
use App\Models\Categoria;
use App\Models\PerfilPreferencias;
use App\Models\Planta;
use App\Services\LocalRecomendacionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalRecomendacionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_prioriza_plantas_acordes_al_perfil(): void
    {
        $interior = Categoria::query()->create(['nombre' => 'Interior']);
        $exterior = Categoria::query()->create(['nombre' => 'Exterior']);

        $poto = $this->crearPlanta('Poto dorado', 'Planta colgante fácil de cuidar. Tolera poca luz.', $interior);
        $this->crearPlanta('Buganvilla', 'Enredadera vibrante para exteriores soleados.', $exterior);

        $perfil = new PerfilPreferencias([
            'experiencia' => 'principiante',
            'espacio' => 'interior_pequeno',
            'iluminacion' => 'baja',
            'tiempo_cuidado' => 'bajo',
            'mascotas' => false,
        ]);

        $resultado = (new LocalRecomendacionService)->generar(
            $perfil,
            Planta::query()->with('categoria')->get()
        );

        $this->assertSame(__('messages.recomendaciones_local_explicacion'), $resultado['explicacion']);
        $this->assertCount(1, $resultado['plantas']);
        $this->assertSame($poto->id, $resultado['plantas'][0]['planta']->id);
        $this->assertNotSame('', $resultado['plantas'][0]['motivo']);
    }

    public function test_falla_sin_plantas_disponibles(): void
    {
        $this->expectException(RecomendacionIAException::class);

        (new LocalRecomendacionService)->generar(new PerfilPreferencias, collect());
    }

    private function crearPlanta(string $nombre, string $descripcion, Categoria $categoria): Planta
    {
        return Planta::query()->create([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => 30000,
            'stock' => 5,
            'imagen_url' => null,
            'categoria_id' => $categoria->id,
        ]);
    }
}
