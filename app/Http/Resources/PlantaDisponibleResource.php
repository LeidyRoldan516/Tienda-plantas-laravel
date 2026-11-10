<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantaDisponibleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria?->nombre,
            'descripcion' => $this->descripcion,
            'precio' => (int) $this->precio,
            'stock' => (int) $this->stock,
            'imagen_url' => $this->imagen_url,
            'detalle_url' => route('catalogo.show', ['planta' => $this->id]),
        ];
    }
}
