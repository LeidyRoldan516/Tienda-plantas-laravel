<?php

/**
 * Autor: Simon Martinez Gomez
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planta_recomendacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recomendacion_id')
                ->constrained('recomendaciones')
                ->cascadeOnDelete();
            $table->foreignId('planta_id')
                ->constrained('plantas')
                ->restrictOnDelete();
            $table->text('motivo')->nullable();
            $table->unsignedTinyInteger('orden')->default(1);

            $table->unique(['recomendacion_id', 'planta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planta_recomendacion');
    }
};
