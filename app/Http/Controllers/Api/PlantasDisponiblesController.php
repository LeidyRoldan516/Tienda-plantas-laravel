<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantaDisponibleResource;
use App\Models\Planta;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlantasDisponiblesController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $plantas = Planta::query()
            ->with('categoria')
            ->where('stock', '>', 0)
            ->orderBy('id')
            ->get();

        return PlantaDisponibleResource::collection($plantas)->additional([
            'meta' => [
                'total' => $plantas->count(),
                'proveedor' => 'El Rincón de las Plantas',
            ],
        ]);
    }
}
