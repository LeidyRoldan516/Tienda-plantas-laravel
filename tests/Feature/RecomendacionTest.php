<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Planta;
use App\Models\User;
use App\Services\RecomendacionIAService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecomendacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_puede_guardar_preferencias(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $respuesta = $this->actingAs($cliente)->post(route('recomendaciones.preferencias'), [
            'experiencia' => 'principiante',
            'espacio' => 'balcon',
            'iluminacion' => 'media',
            'tiempo_cuidado' => 'bajo',
            'mascotas' => '1',
        ]);

        $respuesta->assertRedirect(route('recomendaciones.index'));

        $this->assertDatabaseHas('perfiles_preferencias', [
            'usuario_id' => $cliente->id,
            'experiencia' => 'principiante',
            'espacio' => 'balcon',
            'iluminacion' => 'media',
            'tiempo_cuidado' => 'bajo',
            'mascotas' => 1,
        ]);
    }

    public function test_preferencias_invalidas_son_rechazadas(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $respuesta = $this->actingAs($cliente)->from(route('recomendaciones.index'))
            ->post(route('recomendaciones.preferencias'), [
                'experiencia' => 'experto',
                'espacio' => 'balcon',
                'iluminacion' => 'media',
                'tiempo_cuidado' => 'bajo',
                'mascotas' => '1',
            ]);

        $respuesta->assertRedirect(route('recomendaciones.index'));
        $respuesta->assertSessionHasErrors('experiencia');
        $this->assertDatabaseCount('perfiles_preferencias', 0);
    }

    public function test_generar_sin_preferencias_muestra_error_comprensible(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $respuesta = $this->actingAs($cliente)->post(route('recomendaciones.generar'));

        $respuesta->assertRedirect(route('recomendaciones.index'));
        $respuesta->assertSessionHas('error');
    }

    public function test_servicio_sin_credenciales_informa_error(): void
    {
        config(['services.gemini.key' => null]);
        $categoria = Categoria::query()->create(['nombre' => 'Interior']);
        Planta::query()->create([
            'nombre' => 'Monstera',
            'descripcion' => 'Planta de interior',
            'precio' => 45000,
            'stock' => 5,
            'imagen_url' => null,
            'categoria_id' => $categoria->id,
        ]);

        $cliente = User::factory()->create(['rol' => 'cliente']);
        $cliente->perfilPreferencias()->create([
            'experiencia' => 'principiante',
            'espacio' => 'balcon',
            'iluminacion' => 'media',
            'tiempo_cuidado' => 'bajo',
            'mascotas' => false,
        ]);

        $respuesta = $this->actingAs($cliente)->post(route('recomendaciones.generar'));

        $respuesta->assertRedirect(route('recomendaciones.index'));
        $respuesta->assertSessionHas('error', __('messages.recomendaciones_sin_credenciales'));
        $this->assertDatabaseCount('recomendaciones', 0);
    }

    public function test_servicio_valida_solo_plantas_existentes(): void
    {
        $servicio = app(RecomendacionIAService::class);
        $metodo = new \ReflectionMethod(RecomendacionIAService::class, 'validarYMapear');
        $metodo->setAccessible(true);

        $planta = Planta::query()->create([
            'nombre' => 'Monstera',
            'descripcion' => 'Planta de interior',
            'precio' => 45000,
            'stock' => 5,
            'imagen_url' => null,
            'categoria_id' => Categoria::query()->create(['nombre' => 'Interior'])->id,
        ]);

        $resultado = $metodo->invoke($servicio, [
            'explicacion' => 'Estas plantas encajan contigo.',
            'plantas' => [
                ['id' => $planta->id, 'motivo' => 'Fácil de cuidar'],
                ['id' => 99999, 'motivo' => 'Inventada'],
            ],
        ], collect([$planta]));

        $this->assertSame('Estas plantas encajan contigo.', $resultado['explicacion']);
        $this->assertCount(1, $resultado['plantas']);
        $this->assertSame($planta->id, $resultado['plantas'][0]['planta']->id);
    }
}
