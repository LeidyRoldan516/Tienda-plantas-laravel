<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarritoCompras extends Model
{
    protected $table = 'carrito_compras';

    protected $fillable = ['usuario_id'];

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getUsuarioId(): int
    {
        return (int) $this->attributes['usuario_id'];
    }

    public function setUsuarioId(int $usuarioId): void
    {
        $this->attributes['usuario_id'] = $usuarioId;
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemCarrito::class);
    }
}
