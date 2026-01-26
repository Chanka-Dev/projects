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
        Schema::create('asiento_contables', function (Blueprint $table) {
            $table->id();
            $table->string('numero_asiento')->unique();
            $table->date('fecha');
            $table->enum('tipo_asiento', ['apertura', 'cierre', 'diario', 'ajuste'])->default('diario');
            $table->string('descripcion');
            $table->string('origen')->nullable();
            $table->integer('origen_id')->nullable();
            $table->enum('estado', ['pendiente', 'contabilizado', 'anulado'])->default('pendiente');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asiento_contables');
    }
};
