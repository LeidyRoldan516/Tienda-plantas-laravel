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

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getCarritoComprasId(): int
    {
        return (int) $this->attributes['carrito_compras_id'];
    }

    public function setCarritoComprasId(int $carritoComprasId): void
    {
        $this->attributes['carrito_compras_id'] = $carritoComprasId;
    }

    public function getPlantaId(): int
    {
        return (int) $this->attributes['planta_id'];
    }

    public function setPlantaId(int $plantaId): void
    {
        $this->attributes['planta_id'] = $plantaId;
    }

    public function getCantidad(): int
    {
        return (int) $this->attributes['cantidad'];
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
        return $this->getCantidad() * $this->getPrecioUnitario();
    }

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
        return $this->getSubtotal();
    }
}
