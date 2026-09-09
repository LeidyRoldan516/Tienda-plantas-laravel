<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $fillable = ['pedido_id', 'planta_id', 'cantidad', 'precio_unitario', 'subtotal'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function planta()
    {
        return $this->belongsTo(Planta::class);
    }
}