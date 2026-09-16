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
        Schema::create('perfiles_preferencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('experiencia');
            $table->string('espacio');
            $table->string('iluminacion');
            $table->string('tiempo_cuidado');
            $table->boolean('mascotas')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfiles_preferencias');
    }
};
