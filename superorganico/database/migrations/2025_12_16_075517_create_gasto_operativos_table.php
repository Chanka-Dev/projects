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
        Schema::create('gasto_operativos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_gasto')->unique();
            $table->string('descripcion');
            $table->date('fecha_gasto');
            $table->decimal('monto', 10, 2);
            $table->string('tipo_comprobante')->nullable();
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->enum('estado_contable', ['no_contabilizado', 'contabilizado', 'anulado'])->default('no_contabilizado');
            $table->foreignId('categoria_gasto_id')->constrained('categoria_gastos')->onDelete('cascade');
            $table->foreignId('cuenta_id')->constrained('plan_cuentas')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('asiento_id')->nullable()->constrained('asiento_contables')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto_operativos');
    }
};
