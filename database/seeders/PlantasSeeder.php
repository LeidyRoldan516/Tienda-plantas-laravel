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

        $plantas = [
            ['nombre' => 'Monstera', 'descripcion' => 'Planta de interior con hojas grandes; prefiere luz indirecta.', 'precio' => 45000, 'stock' => 8, 'categoria_id' => $interior->id],
            ['nombre' => 'Poto', 'descripcion' => 'Planta de interior fácil de cuidar; tolera luz indirecta.', 'precio' => 22000, 'stock' => 12, 'categoria_id' => $interior->id],
            ['nombre' => 'Sansevieria', 'descripcion' => 'Planta resistente, adecuada para personas con poca experiencia.', 'precio' => 32000, 'stock' => 7, 'categoria_id' => $interior->id],
            ['nombre' => 'Helecho', 'descripcion' => 'Planta de interior que prefiere humedad y luz indirecta.', 'precio' => 28000, 'stock' => 5, 'categoria_id' => $interior->id],
            ['nombre' => 'Lavanda', 'descripcion' => 'Planta aromática que necesita buena iluminación.', 'precio' => 25000, 'stock' => 10, 'categoria_id' => $aromaticas->id],
            ['nombre' => 'Albahaca', 'descripcion' => 'Planta aromática útil en la cocina; necesita riego frecuente.', 'precio' => 15000, 'stock' => 9, 'categoria_id' => $aromaticas->id],
            ['nombre' => 'Rosa', 'descripcion' => 'Planta de exterior con flores; requiere buena luz.', 'precio' => 38000, 'stock' => 6, 'categoria_id' => $exterior->id],
            ['nombre' => 'Geranio', 'descripcion' => 'Planta de exterior con flores y cuidados sencillos.', 'precio' => 20000, 'stock' => 0, 'categoria_id' => $exterior->id],
        ];

        foreach ($plantas as $datos) {
            Planta::firstOrCreate(
                ['nombre' => $datos['nombre']],
                $datos
            );
        }
    }
}
