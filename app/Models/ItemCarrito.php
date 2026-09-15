<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCarrito extends Model
{
    protected $table = 'item_carritos';

    protected $fillable = [
        'carrito_compras_id',
        'planta_id',
        'cantidad',
        'precio_unitario',
    ];

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(CarritoCompras::class, 'carrito_compras_id');
    }

    public function planta(): BelongsTo
    {
        return $this->belongsTo(Planta::class);
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->cantidad * (float) $this->precio_unitario;
    }
}
