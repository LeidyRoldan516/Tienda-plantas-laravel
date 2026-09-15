<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planta extends Model
{
    protected $table = 'plantas';

    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'stock',
        'imagen_url', 'categoria_id',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function itemsCarrito(): HasMany
    {
        return $this->hasMany(ItemCarrito::class);
    }

    public function itemsPedido(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }
}
