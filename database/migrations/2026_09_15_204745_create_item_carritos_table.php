<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_carritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrito_compras_id')
                ->constrained('carrito_compras')->cascadeOnDelete();
            $table->foreignId('planta_id')
                ->constrained('plantas')->restrictOnDelete();
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->timestamps();

            $table->unique(['carrito_compras_id', 'planta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_carritos');
    }
};
