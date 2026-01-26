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
        // Eliminar duracion_minutos de servicios
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('duracion_minutos');
        });
        
        // Eliminar hora_inicio y hora_fin de citas
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['hora_inicio', 'hora_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->integer('duracion_minutos')->nullable();
        });
        
        Schema::table('citas', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
        });
    }
};
