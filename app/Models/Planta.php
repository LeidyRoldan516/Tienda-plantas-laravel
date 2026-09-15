<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->attributes['precio'];
    }

    public function setPrecio(int $precio): void
    {
        $this->attributes['precio'] = $precio;
    }

    public function getStock(): int
    {
        return $this->attributes['stock'];
    }

    public function setStock(int $stock): void
    {
        $this->attributes['stock'] = $stock;
    }

    public function getImagenUrl(): ?string
    {
        return $this->attributes['imagen_url'] ?? null;
    }

    public function setImagenUrl(?string $imagen_url): void
    {
        $this->attributes['imagen_url'] = $imagen_url;
    }

    public function getCategoriaId(): int
    {
        return $this->attributes['categoria_id'];
    }

    public function setCategoriaId(int $categoria_id): void
    {
        $this->attributes['categoria_id'] = $categoria_id;
    }

    public function getNombreListado(): string
    {
        $nombre = $this->getNombre();
        if ($this->getStock() === 0) {
            return $nombre . ' (Sin stock)';
        }
        return $nombre;
    }

    public function getPrecioFormateado(): string
    {
        return '$ ' . number_format($this->getPrecio(), 0, ',', '.');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function getNombreCategoria(): string
    {
        return $this->categoria->getNombre();
    }
}
