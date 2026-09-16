<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recomendacion extends Model
{
    protected $table = 'recomendaciones';

    protected $fillable = [
        'usuario_id',
        'explicacion',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function plantas(): BelongsToMany
    {
        return $this->belongsToMany(Planta::class, 'planta_recomendacion')
            ->withPivot(['motivo', 'orden'])
            ->orderByPivot('orden');
    }
}
