<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Planta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class InternacionalizacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_solo_se_aceptan_es_y_en_y_el_idioma_persiste(): void
    {
        $this->from('/login')->get(route('idioma.cambiar', 'en'))
            ->assertRedirect('/login')->assertSessionHas('locale', 'en');
        $this->get('/login')->assertOk()->assertSee('lang="en"', false)->assertSee('Log in');
        $this->get('/register')->assertOk()->assertSee('Create account');
        $this->get(route('idioma.cambiar', 'fr'))->assertNotFound();
        $this->get(route('idioma.cambiar', 'es'))->assertSessionHas('locale', 'es');
        $this->get('/login')->assertSee('Iniciar sesión');
    }

    public function test_es_es_el_idioma_predeterminado_incluso_con_sesion_invalida(): void
    {
        $this->get('/login')->assertSee('lang="es"', false)->assertSee('Iniciar sesión');
        $this->withSession(['locale' => 'fr'])->get('/login')->assertSee('lang="es"', false);
    }

    public function test_login_logout_y_registro_conservan_el_idioma(): void
    {
        $user = User::factory()->cliente()->create();
        $this->withSession(['locale' => 'en'])->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHas('locale', 'en');
        $this->post('/logout')->assertSessionHas('locale', 'en');
        $this->assertGuest();
        $this->get('/login')->assertSee('lang="en"', false);
        $this->post('/register', [
            'name' => 'New customer',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHas('locale', 'en');
    }

    public function test_las_areas_cliente_y_admin_se_traducen(): void
    {
        $cliente = User::factory()->cliente()->create();
        $this->actingAs($cliente)->withSession(['locale' => 'en']);
        foreach (['cliente.dashboard', 'catalogo.index', 'carrito.index', 'pedidos.index', 'recomendaciones.index', 'profile.edit'] as $route) {
            $this->get(route($route))->assertOk()->assertSee('lang="en"', false)->assertDontSee('messages.');
        }
        $admin = User::factory()->administrador()->create();
        $this->actingAs($admin);
        foreach (['admin.dashboard', 'admin.plantas.index', 'admin.plantas.create', 'admin.categorias.index', 'admin.categorias.create'] as $route) {
            $this->get(route($route))->assertOk()->assertSee('lang="en"', false)->assertDontSee('messages.');
        }
    }

    public function test_las_vistas_con_datos_conservan_el_contenido_y_traducen_los_estados(): void
    {
        $categoria = Categoria::create(['nombre' => 'Categor?a original']);
        $planta = Planta::create([
            'nombre' => "Planta de O'Brien",
            'descripcion' => 'Descripci?n original de la planta',
            'precio' => 20000,
            'stock' => 8,
            'categoria_id' => $categoria->id,
        ]);
        $cliente = User::factory()->cliente()->create();
        $this->actingAs($cliente)->withSession(['locale' => 'en']);
        $this->get('/')->assertOk()->assertSee('A living corner for your home');
        $this->get(route('catalogo.show', $planta))->assertOk()
            ->assertSee($planta->nombre)->assertSee($planta->descripcion)->assertSee('Add to cart');
        $this->post(route('carrito.agregar'), ['planta_id' => $planta->id, 'cantidad' => 1])
            ->assertSessionHas('mensaje', 'Plant added to cart.');
        $this->get(route('carrito.index'))->assertOk()->assertSee('Place order');
        $response = $this->post(route('pedidos.crear'));
        $this->get($response->headers->get('Location'))->assertOk()->assertSee('Pending')->assertSee('Cancel order');
        $this->actingAs(User::factory()->administrador()->create());
        foreach ([
            ['admin.plantas.show', $planta], ['admin.plantas.edit', $planta],
            ['admin.categorias.show', $categoria], ['admin.categorias.edit', $categoria],
        ] as [$route, $model]) {
            $this->get(route($route, $model))->assertOk()->assertDontSee('messages.');
        }
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Available');
    }

    public function test_las_claves_coinciden_y_los_mensajes_estandar_se_traducen(): void
    {
        foreach (['messages', 'auth', 'validation', 'passwords', 'pagination'] as $file) {
            $es = require resource_path("lang/es/{$file}.php");
            $en = require resource_path("lang/en/{$file}.php");
            $this->assertSame(array_keys($es), array_keys($en));
        }
        foreach (['es' => 'obligatorio', 'en' => 'required'] as $locale => $expected) {
            app()->setLocale($locale);
            $this->assertStringContainsString($expected, Validator::make([], ['email' => 'required'])->errors()->first());
            $paginator = new LengthAwarePaginator(range(1, 10), 25, 10);
            $html = $paginator->links()->toHtml();
            $this->assertStringNotContainsString('messages.', $html);
            $this->assertStringContainsString($locale === 'es' ? 'Mostrando' : 'Showing', $html);
        }
    }
}
