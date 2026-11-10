<?php

namespace Tests\Feature;

use App\Interfaces\CatalogoNftInterface;
use App\Models\User;
use App\Services\MonoverseCatalogoNftService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CatalogoNftTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.monoverse.base_url' => 'https://monoverse.test']);
        Http::preventStrayRequests();
    }

    public function test_catalogo_es_publico_muestra_datos_y_envia_accept_json(): void
    {
        Http::fake(['monoverse.test/*' => Http::response($this->catalogo())]);

        $this->get(route('nfts.index'))->assertOk()
            ->assertSee('NFTs aliados')->assertSee('Nebula Echoes #1')
            ->assertSee('Fragmentos de color')->assertSee('Nebula Echoes')
            ->assertSee('3.20')->assertSee('MONO')->assertSee('juanjose')
            ->assertSee('https://monoverse.test/nfts/1')
            ->assertSee('target="_blank"', false)->assertSee('rel="noopener noreferrer"', false);

        $this->assertGuest();
        Http::assertSent(fn (Request $request) => $request->url() === 'https://monoverse.test/api/v1/nfts?page=1'
            && $request->method() === 'GET' && $request->hasHeader('Accept', 'application/json')
            && ! $request->hasHeader('Authorization') && ! $request->hasHeader('Cookie'));
    }

    public function test_usa_pagina_solicitada_y_metadatos_para_la_paginacion(): void
    {
        $catalogo = $this->catalogo();
        $catalogo['meta'] = ['current_page' => 2, 'per_page' => 20, 'total' => 60, 'last_page' => 3];
        Http::fake(['monoverse.test/*' => Http::response($catalogo)]);

        $this->get(route('nfts.index', ['page' => 2]))->assertOk()
            ->assertSee('Página 2 de 3')->assertSee(route('nfts.index', ['page' => 1]))
            ->assertSee(route('nfts.index', ['page' => 3]));

        Http::assertSent(fn (Request $request) => $request['page'] === 2);
    }

    public function test_ultima_pagina_no_muestra_enlace_siguiente(): void
    {
        $catalogo = $this->catalogo();
        $catalogo['meta']['current_page'] = 2;
        $catalogo['meta']['last_page'] = 2;
        Http::fake(['monoverse.test/*' => Http::response($catalogo)]);

        $this->get('/nfts?page=2')->assertOk()->assertSee('Anterior')->assertDontSee('rel="next"', false);
    }

    #[DataProvider('paginasInvalidas')]
    public function test_rechaza_paginas_invalidas_sin_consultar_monoverse(string $consulta): void
    {
        Http::fake();
        $this->get('/nfts?'.$consulta)->assertStatus(422)->assertSee('La página debe ser un número entero');
        Http::assertNothingSent();
    }

    public static function paginasInvalidas(): array
    {
        return array_map(fn (string $consulta) => [$consulta], [
            'page=0', 'page=-2', 'page=1.5', 'page=abc', 'page=', 'page[]=1', 'page=1000001',
        ]);
    }

    public function test_catalogo_vacio_no_se_confunde_con_un_error(): void
    {
        $catalogo = $this->catalogo();
        $catalogo['data'] = [];
        $catalogo['meta']['total'] = 0;
        Http::fake(['monoverse.test/*' => Http::response($catalogo)]);

        $this->get('/nfts')->assertOk()->assertSee('No hay NFTs disponibles')
            ->assertDontSee('No pudimos consultar Monoverse');
    }

    public function test_imagen_nula_o_vacia_y_campos_opcionales_tienen_respaldo(): void
    {
        $secuencia = Http::sequence();
        foreach ([null, '', 'not-a-valid-url'] as $imagen) {
            $catalogo = $this->catalogo();
            $catalogo['data'][0] = ['id' => 1, 'image_url' => $imagen, 'description' => null];
            $secuencia->push($catalogo);
        }
        Http::fake(['monoverse.test/*' => $secuencia]);
        foreach (range(1, 3) as $consulta) {
            $respuesta = $this->get('/nfts')->assertOk()
                ->assertSee('NFT sin nombre')->assertSee('Sin descripción disponible.')
                ->assertSee('No disponible')->assertDontSee('target="_blank"', false);
            $this->comprobarTarjetaSinImagen($respuesta);
        }
    }

    public function test_paginacion_estado_vacio_y_validacion_en_ingles(): void
    {
        $catalogo = $this->catalogo();
        $catalogo['meta']['last_page'] = 2;
        $vacio = $catalogo;
        $vacio['data'] = [];
        Http::fake(['monoverse.test/*' => Http::sequence()->push($catalogo)->push($vacio)]);

        $this->withSession(['locale' => 'en'])->get('/nfts')->assertOk()
            ->assertSee('Page 1 of 2')->assertSee('Next');
        $this->get('/nfts')->assertOk()->assertSee('There are no NFTs available on this page.');
        $this->get('/nfts?page=0')->assertStatus(422)->assertSee('The page must be an integer');
    }

    public function test_muestra_la_imagen_cuando_existe(): void
    {
        $catalogo = $this->catalogo();
        $catalogo['data'][0]['image_url'] = 'https://monoverse.test/image.jpg';
        Http::fake(['monoverse.test/*' => Http::response($catalogo)]);

        $this->get('/nfts')->assertOk()->assertSee('src="https://monoverse.test/image.jpg"', false);
    }

    #[DataProvider('estadosDeError')]
    public function test_error_http_muestra_mensaje_amigable(int $estado): void
    {
        Http::fake(['monoverse.test/*' => Http::response(['message' => 'internal-sensitive-detail'], $estado)]);

        $this->get('/nfts')->assertOk()->assertSee('No pudimos consultar Monoverse')
            ->assertSee('Volver a intentar')->assertDontSee('internal-sensitive-detail')
            ->assertDontSee('No hay NFTs disponibles');
    }

    public static function estadosDeError(): array
    {
        return [[422], [429], [500], [503]];
    }

    public function test_timeout_o_fallo_de_conexion_no_produce_error_500(): void
    {
        Http::fake(fn () => throw new ConnectionException('cURL error 28: internal-sensitive-detail'));

        $this->get('/nfts')->assertOk()->assertSee('No pudimos consultar Monoverse')
            ->assertDontSee('cURL')->assertDontSee('internal-sensitive-detail');
    }

    #[DataProvider('respuestasInvalidas')]
    public function test_json_o_estructura_invalida_no_produce_error_500(mixed $contenido): void
    {
        Http::fake(['monoverse.test/*' => Http::response($contenido)]);

        $this->get('/nfts')->assertOk()->assertSee('No pudimos consultar Monoverse');
    }

    public static function respuestasInvalidas(): array
    {
        $meta = ['current_page' => 1, 'per_page' => 20, 'total' => 0, 'last_page' => 1];

        return [
            ['not-json'], ['null'], ['<html>unavailable</html>'],
            [['meta' => $meta]], [['data' => []]], [['data' => null, 'meta' => $meta]],
            [['data' => [], 'meta' => ['current_page' => 0]]],
            [['data' => ['invalid'], 'meta' => $meta]],
            [['data' => [['id' => 1, 'name' => ['invalid']]], 'meta' => $meta]],
            ['{"data":{},"meta":{"current_page":1,"per_page":20,"total":0,"last_page":1}}'],
        ];
    }

    public function test_textos_en_ingles_y_datos_externos_sin_traducir(): void
    {
        Http::fake(['monoverse.test/*' => Http::sequence()->push($this->catalogo())->push([], 503)]);
        $respuesta = $this->withSession(['locale' => 'en'])->get('/nfts')->assertOk()
            ->assertSee('Partner NFTs')->assertSee('Collection')->assertSee('Seller')
            ->assertSee('View on Monoverse')
            ->assertSee('Fragmentos de color')->assertDontSee('messages.nfts_');
        $this->comprobarTarjetaSinImagen($respuesta);

        $this->get('/nfts')->assertOk()->assertSee('We could not reach Monoverse')->assertSee('Try again');
    }

    public function test_contenido_externo_se_escapa_y_urls_peligrosas_no_se_renderizan(): void
    {
        $catalogo = $this->catalogo();
        $catalogo['data'][0]['name'] = '<script>alert(1)</script>';
        $catalogo['data'][0]['detail_url'] = 'javascript:alert(1)';
        $catalogo['data'][0]['image_url'] = 'data:image/svg+xml,evil';
        Http::fake(['monoverse.test/*' => Http::response($catalogo)]);

        $respuesta = $this->get('/nfts')->assertOk()->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('javascript:alert', false)
            ->assertDontSee('data:image/svg+xml', false);
        $this->comprobarTarjetaSinImagen($respuesta);
    }

    public function test_contrato_esta_enlazado_y_el_controlador_admite_otra_implementacion(): void
    {
        $this->assertInstanceOf(MonoverseCatalogoNftService::class, app(CatalogoNftInterface::class));
        $this->mock(CatalogoNftInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('consultar')->once()->with(1)->andReturn($this->catalogo());
        });
        Http::fake();
        $this->get('/nfts')->assertOk()->assertSee('Nebula Echoes #1');
        Http::assertNothingSent();
    }

    public function test_consulta_en_tiempo_real_sin_escrituras_en_base_de_datos(): void
    {
        Http::fake(['monoverse.test/*' => Http::response($this->catalogo())]);
        DB::enableQueryLog();
        DB::flushQueryLog();
        try {
            $this->get('/nfts')->assertOk();
            $this->get('/nfts')->assertOk();
            Http::assertSentCount(2);
            $this->assertSame([], DB::getQueryLog());
        } finally {
            DB::disableQueryLog();
            DB::flushQueryLog();
        }
    }

    public function test_cliente_autenticado_tiene_acceso_y_enlace_en_el_perfil(): void
    {
        Http::fake(['monoverse.test/*' => Http::response($this->catalogo())]);
        $this->actingAs(User::factory()->cliente()->create());
        $this->get('/nfts')->assertOk()->assertSee('NFTs aliados');
        $this->get(route('profile.edit'))->assertOk()->assertSee(route('nfts.index'));
    }

    private function comprobarTarjetaSinImagen(TestResponse $respuesta): void
    {
        $respuesta->assertDontSee('Vista previa no disponible')->assertDontSee('Preview unavailable');
        $this->assertSame(1, preg_match('/<article\b[^>]*>(.*?)<\/article>/s', $respuesta->getContent(), $tarjeta));
        $this->assertStringNotContainsString('<img', $tarjeta[1]);
        $this->assertStringNotContainsString('aspect-[4/5]', $tarjeta[1]);
        $this->assertMatchesRegularExpression('/^\s*<div class="space-y-4 p-6">/', $tarjeta[1]);
    }

    private function catalogo(): array
    {
        return [
            'data' => [[
                'id' => 1,
                'name' => 'Nebula Echoes #1',
                'description' => 'Fragmentos de color',
                'collection' => 'Nebula Echoes',
                'price' => '3.20',
                'currency' => 'MONO',
                'seller' => 'juanjose',
                'image_url' => null,
                'detail_url' => 'https://monoverse.test/nfts/1',
            ]],
            'meta' => ['current_page' => 1, 'per_page' => 20, 'total' => 1, 'last_page' => 1],
        ];
    }
}
