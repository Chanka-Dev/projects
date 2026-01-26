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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->decimal('efectivo_recibido', 10, 2)->nullable();
            $table->decimal('cambio', 10, 2)->nullable();
            $table->enum('estado_contable', ['no_contabilizado', 'contabilizado', 'anulado'])->default('no_contabilizado');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('cascade');
            $table->foreignId('asiento_id')->nullable()->constrained('asiento_contables')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
