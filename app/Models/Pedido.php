<?php

/**
 * Autores:
 * - David Zapata Orozco
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['usuario_id', 'fecha', 'estado', 'total'];

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getUsuarioId(): int
    {
        return $this->attributes['usuario_id'];
    }

    public function setUsuarioId(int $usuarioId): void
    {
        $this->attributes['usuario_id'] = $usuarioId;
    }

    public function getFecha(): string
    {
        return $this->attributes['fecha'];
    }

    public function setFecha(string $fecha): void
    {
        $this->attributes['fecha'] = $fecha;
    }

    public function getEstado(): string
    {
        return $this->attributes['estado'];
    }

    public function setEstado(string $estado): void
    {
        $this->attributes['estado'] = $estado;
    }

    public function getTotal(): float
    {
        return (float) $this->attributes['total'];
    }

    public function setTotal(float $total): void
    {
        $this->attributes['total'] = $total;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items()
    {
        return $this->hasMany(ItemPedido::class);
    }
}
