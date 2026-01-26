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
        Schema::create('pago_trabajadoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajadora_id')->constrained('trabajadoras')->onDelete('cascade');
            $table->date('fecha_inicio_periodo');
            $table->date('fecha_fin_periodo');
            $table->decimal('total_servicios', 10, 2)->default(0);
            $table->decimal('total_comisiones', 10, 2)->default(0);
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_trabajadoras');
    }
};
