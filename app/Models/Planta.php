<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planta extends Model
{
    protected $table = 'plantas';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen_url',
        'categoria_id',
    ];

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getNombre(): string
    {
        return $this->attributes['nombre'];
    }

    public function setNombre(string $nombre): void
    {
        $this->attributes['nombre'] = $nombre;
    }

    public function getDescripcion(): string
    {
        return $this->attributes['descripcion'];
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->attributes['descripcion'] = $descripcion;
    }

    public function getPrecio(): int
    {
        return (int) $this->attributes['precio'];
    }

    public function setPrecio(int $precio): void
    {
        $this->attributes['precio'] = $precio;
    }

    public function getStock(): int
    {
        return (int) $this->attributes['stock'];
    }

    public function setStock(int $stock): void
    {
        $this->attributes['stock'] = $stock;
    }

    public function getImagenUrl(): ?string
    {
        return $this->attributes['imagen_url'] ?? null;
    }

    public function setImagenUrl(?string $imagenUrl): void
    {
        $this->attributes['imagen_url'] = $imagenUrl;
    }

    public function getCategoriaId(): int
    {
        return (int) $this->attributes['categoria_id'];
    }

    public function setCategoriaId(int $categoriaId): void
    {
        $this->attributes['categoria_id'] = $categoriaId;
    }

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

    public function recomendaciones(): BelongsToMany
    {
        return $this->belongsToMany(Recomendacion::class, 'planta_recomendacion')
            ->withPivot(['motivo', 'orden']);
    }
}
