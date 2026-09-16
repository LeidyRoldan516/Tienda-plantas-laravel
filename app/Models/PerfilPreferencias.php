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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estaCompleto(): bool
    {
        return filled($this->experiencia)
            && filled($this->espacio)
            && filled($this->iluminacion)
            && filled($this->tiempo_cuidado);
    }
}
