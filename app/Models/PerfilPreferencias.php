<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilPreferencias extends Model
{
    protected $table = 'perfiles_preferencias';

    protected $fillable = [
        'usuario_id',
        'experiencia',
        'espacio',
        'iluminacion',
        'tiempo_cuidado',
        'mascotas',
    ];

    protected function casts(): array
    {
        return [
            'mascotas' => 'boolean',
        ];
    }

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

    public function getExperiencia(): string
    {
        return $this->attributes['experiencia'];
    }

    public function setExperiencia(string $experiencia): void
    {
        $this->attributes['experiencia'] = $experiencia;
    }

    public function getEspacio(): string
    {
        return $this->attributes['espacio'];
    }

    public function setEspacio(string $espacio): void
    {
        $this->attributes['espacio'] = $espacio;
    }

    public function getIluminacion(): string
    {
        return $this->attributes['iluminacion'];
    }

    public function setIluminacion(string $iluminacion): void
    {
        $this->attributes['iluminacion'] = $iluminacion;
    }

    public function getTiempoCuidado(): string
    {
        return $this->attributes['tiempo_cuidado'];
    }

    public function setTiempoCuidado(string $tiempoCuidado): void
    {
        $this->attributes['tiempo_cuidado'] = $tiempoCuidado;
    }

    public function getMascotas(): bool
    {
        return (bool) $this->attributes['mascotas'];
    }

    public function setMascotas(bool $mascotas): void
    {
        $this->attributes['mascotas'] = $mascotas;
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estaCompleto(): bool
    {
        return filled($this->getExperiencia())
            && filled($this->getEspacio())
            && filled($this->getIluminacion())
            && filled($this->getTiempoCuidado());
    }
}
