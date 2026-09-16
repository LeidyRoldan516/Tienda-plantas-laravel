<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\Database\Seeder;

class PlantasSeeder extends Seeder
{
    public function run(): void
    {
        $interior = Categoria::firstOrCreate(['nombre' => 'Interior']);
        $exterior = Categoria::firstOrCreate(['nombre' => 'Exterior']);
        $aromaticas = Categoria::firstOrCreate(['nombre' => 'Aromáticas']);
        $suculentas = Categoria::firstOrCreate(['nombre' => 'Suculentas']);

        $plantas = [
            [
                'nombre' => 'Monstera deliciosa',
                'descripcion' => 'Hojas grandes y perforadas. Ideal para espacios luminosos con luz indirecta.',
                'precio' => 65000,
                'stock' => 8,
                'imagen_url' => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $interior->id,
            ],
            [
                'nombre' => 'Poto dorado',
                'descripcion' => 'Planta colgante fácil de cuidar. Tolera poca luz y limpia el aire.',
                'precio' => 22000,
                'stock' => 15,
                'imagen_url' => 'https://images.unsplash.com/photo-1593691509543-c55fb32d8de5?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $interior->id,
            ],
            [
                'nombre' => 'Sansevieria',
                'descripcion' => 'Muy resistente. Perfecta si buscas una planta de bajo mantenimiento.',
                'precio' => 32000,
                'stock' => 10,
                'imagen_url' => 'https://images.unsplash.com/photo-1593482892290-f5495b1b1d7a?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $interior->id,
            ],
            [
                'nombre' => 'Helecho boston',
                'descripcion' => 'Frondas verdes y densas. Prefiere humedad y luz filtrada.',
                'precio' => 28000,
                'stock' => 6,
                'imagen_url' => 'https://images.unsplash.com/photo-1509423350716-97f9360b4e09?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $interior->id,
            ],
            [
                'nombre' => 'Ficus lyrata',
                'descripcion' => 'Árbol de interior con hojas grandes. Necesita luz abundante e indirecta.',
                'precio' => 89000,
                'stock' => 4,
                'imagen_url' => 'https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $interior->id,
            ],
            [
                'nombre' => 'Calathea orbifolia',
                'descripcion' => 'Hojas rayadas muy decorativas. Ideal para rincones con luz suave.',
                'precio' => 48000,
                'stock' => 7,
                'imagen_url' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $interior->id,
            ],
            [
                'nombre' => 'Lavanda',
                'descripcion' => 'Aroma relajante y flores lilas. Necesita sol directo y buen drenaje.',
                'precio' => 25000,
                'stock' => 12,
                'imagen_url' => 'https://images.unsplash.com/photo-1499002238440-d264edd596ec?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $aromaticas->id,
            ],
            [
                'nombre' => 'Albahaca',
                'descripcion' => 'Hierba aromática para cocina. Riego frecuente y mucha luz.',
                'precio' => 12000,
                'stock' => 20,
                'imagen_url' => 'https://images.unsplash.com/photo-1618376092197-3aa47914e8e7?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $aromaticas->id,
            ],
            [
                'nombre' => 'Romero',
                'descripcion' => 'Aromática mediterránea. Ideal para balcones soleados.',
                'precio' => 18000,
                'stock' => 14,
                'imagen_url' => 'https://images.unsplash.com/photo-1515586000433-45406d8e6662?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $aromaticas->id,
            ],
            [
                'nombre' => 'Rosa jardinera',
                'descripcion' => 'Floración abundante para exteriores. Requiere sol y riego regular.',
                'precio' => 38000,
                'stock' => 9,
                'imagen_url' => 'https://images.unsplash.com/photo-1496062031456-07b74c67d375?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $exterior->id,
            ],
            [
                'nombre' => 'Geranio',
                'descripcion' => 'Flores coloridas y cuidados sencillos para terrazas y balcones.',
                'precio' => 20000,
                'stock' => 0,
                'imagen_url' => 'https://images.unsplash.com/photo-1591857177580-dc82b99c95e5?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $exterior->id,
            ],
            [
                'nombre' => 'Buganvilla',
                'descripcion' => 'Enredadera vibrante para exteriores soleados.',
                'precio' => 42000,
                'stock' => 5,
                'imagen_url' => 'https://images.unsplash.com/photo-1465146633011-14f8e0781093?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $exterior->id,
            ],
            [
                'nombre' => 'Echeveria',
                'descripcion' => 'Suculenta en forma de roseta. Poca agua y mucha luz.',
                'precio' => 15000,
                'stock' => 18,
                'imagen_url' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $suculentas->id,
            ],
            [
                'nombre' => 'Aloe vera',
                'descripcion' => 'Suculenta medicinal. Fácil de cuidar y muy resistente.',
                'precio' => 19000,
                'stock' => 11,
                'imagen_url' => 'https://images.unsplash.com/photo-1509423350716-97f9360b4e09?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $suculentas->id,
            ],
            [
                'nombre' => 'Cactus barrel',
                'descripcion' => 'Cactus redondo decorativo. Ideal para escritorios soleados.',
                'precio' => 16000,
                'stock' => 13,
                'imagen_url' => 'https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&w=800&q=80',
                'categoria_id' => $suculentas->id,
            ],
        ];

        foreach ($plantas as $datos) {
            Planta::updateOrCreate(
                ['nombre' => $datos['nombre']],
                $datos
            );
        }
    }
}
