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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste', 'merma']);
            $table->integer('cantidad');
            $table->dateTime('fecha_movimiento');
            $table->decimal('costo_unitario', 10, 2);
            $table->string('referencia')->nullable(); // 'venta', 'compra', 'ajuste', etc.
            $table->unsignedBigInteger('referencia_id')->nullable(); // ID de la venta, compra, etc.
            $table->text('observaciones')->nullable();
            $table->foreignId('lote_id')->constrained('lote_inventarios')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
