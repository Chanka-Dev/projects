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
        Schema::create('plan_cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->enum('tipo_cuenta', ['activo', 'pasivo', 'patrimonio', 'ingreso', 'gasto']);
            $table->string('subtipo');
            $table->integer('nivel');
            $table->boolean('acepta_movimientos')->default(true);
            $table->boolean('activa')->default(true);
            $table->enum('naturaleza', ['deudora', 'acreedora']);
            $table->foreignId('cuenta_padre_id')->nullable()->constrained('plan_cuentas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_cuentas');
    }
};
