<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('usuario_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->date('fecha')->nullable();
            $table->string('estado')->default('pendiente');
            $table->decimal('total', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('usuario_id');
            $table->dropColumn(['fecha', 'estado', 'total']);
        });
    }
};
