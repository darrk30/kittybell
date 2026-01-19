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
        Schema::create('spents', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // Nombre del gasto
            $table->text('description')->nullable();        // Notas adicionales
            $table->decimal('amount', 12, 2);         // Monto del gasto
            $table->date('date');      // Fecha del gasto
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->string('transaction_code', 50)->unique();
            $table->foreignId('cash_summary_id')->nullable()->constrained('cash_summaries')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spents');
    }
};
