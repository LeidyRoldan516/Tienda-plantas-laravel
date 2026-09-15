<?php

/**
 * Autores:
 * - David Zapata Orozco
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $fillable = ['pedido_id', 'planta_id', 'cantidad', 'precio_unitario', 'subtotal'];

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getPedidoId(): int
    {
        return $this->attributes['pedido_id'];
    }

    public function setPedidoId(int $pedidoId): void
    {
        $this->attributes['pedido_id'] = $pedidoId;
    }

    public function getPlantaId(): int
    {
        return $this->attributes['planta_id'];
    }

    public function setPlantaId(int $plantaId): void
    {
        $this->attributes['planta_id'] = $plantaId;
    }

    public function getCantidad(): int
    {
        return $this->attributes['cantidad'];
    }

    public function setCantidad(int $cantidad): void
    {
        $this->attributes['cantidad'] = $cantidad;
    }

    public function getPrecioUnitario(): float
    {
        return (float) $this->attributes['precio_unitario'];
    }

    public function setPrecioUnitario(float $precioUnitario): void
    {
        $this->attributes['precio_unitario'] = $precioUnitario;
    }

    public function getSubtotal(): float
    {
        return (float) $this->attributes['subtotal'];
    }

    public function setSubtotal(float $subtotal): void
    {
        $this->attributes['subtotal'] = $subtotal;
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function planta()
    {
        return $this->belongsTo(Planta::class);
    }
}
