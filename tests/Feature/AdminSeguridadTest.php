<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSeguridadTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitados_no_pueden_acceder_al_panel(): void
    {
        $rutas = [
            '/admin/dashboard',
            '/admin/plantas',
            '/admin/categorias',
        ];

        foreach ($rutas as $ruta) {
            $this->get($ruta)->assertRedirect('/login');
        }
    }

    public function test_clientes_no_pueden_acceder_al_panel(): void
    {
        $cliente = User::factory()->cliente()->create();

        $this->actingAs($cliente);

        $rutas = [
            '/admin/dashboard',
            '/admin/plantas',
            '/admin/categorias',
        ];

        foreach ($rutas as $ruta) {
            $this->get($ruta)->assertForbidden();
        }
    }

    public function test_administradores_pueden_acceder_al_panel(): void
    {
        $administrador = User::factory()->administrador()->create();

        $this->actingAs($administrador);

        $rutas = [
            '/admin/dashboard',
            '/admin/plantas',
            '/admin/categorias',
        ];

        foreach ($rutas as $ruta) {
            $this->get($ruta)->assertOk();
        }
    }
}
