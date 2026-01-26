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
        Schema::create('lote_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_lote')->unique();
            $table->date('fecha_ingreso');
            $table->date('fecha_caducidad')->nullable();
            $table->integer('cantidad_inicial');
            $table->integer('cantidad_actual');
            $table->decimal('costo_unitario', 10, 2);
            $table->enum('estado', ['disponible', 'no_disponible'])->default('disponible');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->foreignId('compra_id')->nullable()->constrained('compras')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote_inventarios');
    }
};
