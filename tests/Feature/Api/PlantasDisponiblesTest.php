<?php

namespace Tests\Feature\Api;

use App\Http\Resources\PlantaDisponibleResource;
use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PlantasDisponiblesTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_publico_devuelve_json_con_el_contrato_exacto(): void
    {
        $categoria = Categoria::create(['nombre' => 'Aromáticas']);
        $planta = $this->crearPlanta($categoria, ['imagen_url' => 'https://example.com/lavanda.jpg']);

        // Una petición normal, sin token ni cabecera Accept, también debe devolver JSON.
        $respuesta = $this->get('/api/plantas-disponibles');

        $this->assertGuest();
        $respuesta->assertOk()->assertHeader('Content-Type', 'application/json')
            ->assertExactJson([
                'data' => [[
                    'id' => $planta->id,
                    'nombre' => 'Lavanda',
                    'categoria' => 'Aromáticas',
                    'descripcion' => 'Planta aromática para el jardín.',
                    'precio' => 25000,
                    'stock' => 10,
                    'imagen_url' => 'https://example.com/lavanda.jpg',
                    'detalle_url' => route('catalogo.show', $planta),
                ]],
                'meta' => ['total' => 1, 'proveedor' => 'El Rincón de las Plantas'],
            ]);

        $datos = $respuesta->json('data.0');
        $this->assertIsInt($datos['precio']);
        $this->assertIsInt($datos['stock']);
        $this->assertSame('http', parse_url($datos['detalle_url'], PHP_URL_SCHEME));
        $this->assertNotEmpty(parse_url($datos['detalle_url'], PHP_URL_HOST));
        $this->assertArrayNotHasKey('categoria_id', $datos);
        $this->assertArrayNotHasKey('created_at', $datos);
        $this->assertArrayNotHasKey('updated_at', $datos);
        $this->assertArrayNotHasKey('usuario_id', $datos);
    }

    public function test_solo_incluye_stock_positivo_en_orden_ascendente_por_id(): void
    {
        $categoria = Categoria::create(['nombre' => 'Interior']);
        $primera = $this->crearPlanta($categoria, ['nombre' => 'Z', 'stock' => 4]);
        $this->crearPlanta($categoria, ['stock' => 0]);
        $ultima = $this->crearPlanta($categoria, ['nombre' => 'A', 'stock' => 1]);

        $respuesta = $this->get('/api/plantas-disponibles')->assertOk()->assertJsonCount(2, 'data');

        $this->assertSame([$primera->id, $ultima->id], array_column($respuesta->json('data'), 'id'));
        $this->assertSame(count($respuesta->json('data')), $respuesta->json('meta.total'));
        $respuesta->assertJsonPath('data.0.imagen_url', null);
    }

    public function test_catalogo_vacio_devuelve_un_arreglo_vacio(): void
    {
        $this->comprobarRespuestaVacia();
    }

    public function test_catalogo_sin_stock_devuelve_un_arreglo_vacio(): void
    {
        $this->crearPlanta(Categoria::create(['nombre' => 'Interior']), ['stock' => 0]);

        $this->comprobarRespuestaVacia();
    }

    public function test_resource_admite_una_relacion_de_categoria_ausente(): void
    {
        // El esquema exige categoria_id: verificamos el caso defensivo sin alterar tablas.
        $planta = new Planta(['nombre' => 'Lavanda', 'precio' => 25000, 'stock' => 10]);
        $planta->id = 1;
        $planta->setRelation('categoria', null);

        $datos = (new PlantaDisponibleResource($planta))->resolve();

        $this->assertNull($datos['categoria']);
        $this->assertNull($datos['imagen_url']);
    }

    public function test_carga_categorias_sin_consultas_por_cada_planta(): void
    {
        foreach (range(1, 4) as $numero) {
            $this->crearPlanta(Categoria::create(['nombre' => 'Categoría '.$numero]));
        }

        DB::enableQueryLog();
        DB::flushQueryLog();

        try {
            $this->get('/api/plantas-disponibles')->assertOk()->assertJsonCount(4, 'data');
            $consultas = collect(DB::getQueryLog())->filter(
                fn (array $consulta) => str_contains($consulta['query'], 'categorias')
            );
            $this->assertCount(1, $consultas);
        } finally {
            DB::disableQueryLog();
            DB::flushQueryLog();
        }
    }

    private function crearPlanta(Categoria $categoria, array $atributos = []): Planta
    {
        return Planta::create(array_merge([
            'nombre' => 'Lavanda',
            'descripcion' => 'Planta aromática para el jardín.',
            'precio' => 25000,
            'stock' => 10,
            'imagen_url' => null,
            'categoria_id' => $categoria->id,
        ], $atributos));
    }

    private function comprobarRespuestaVacia(): void
    {
        $respuesta = $this->get('/api/plantas-disponibles')->assertOk()->assertExactJson([
            'data' => [],
            'meta' => ['total' => 0, 'proveedor' => 'El Rincón de las Plantas'],
        ]);

        $this->assertSame([], json_decode($respuesta->getContent())->data);
    }
}
