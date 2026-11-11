<?php

namespace Tests\Feature\Api;

use App\Models\Categoria;
use App\Models\Planta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantasAccesoTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_autenticado_puede_consultar_api_sin_modificar_plantas(): void
    {
        $cliente = User::factory()->cliente()->create();

        $categoria = Categoria::create([
            'nombre' => 'Interior',
        ]);

        $planta = Planta::create([
            'nombre' => 'Monstera',
            'descripcion' => 'Planta de interior',
            'precio' => 35000,
            'stock' => 5,
            'imagen_url' => null,
            'categoria_id' => $categoria->id,
        ]);

        $this->actingAs($cliente);

        $this->get('/api/plantas-disponibles')
            ->assertOk()
            ->assertJsonPath('data.0.id', $planta->id)
            ->assertJsonPath('data.0.nombre', 'Monstera')
            ->assertJsonPath('data.0.stock', 5);

        $this->assertDatabaseHas('plantas', [
            'id' => $planta->id,
            'nombre' => 'Monstera',
            'stock' => 5,
        ]);

        $this->assertDatabaseCount('plantas', 1);
    }
}
