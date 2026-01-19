<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adjustment_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('movement_type'); // ajuste_entrada, ajuste_salida
            $table->date('date')->nullable(); // Fecha del ajuste
            $table->string('motive')->nullable();        // Motivo general del ajuste
            $table->text('notes')->nullable();          // Notas adicionales

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_stocks');
    }
};
