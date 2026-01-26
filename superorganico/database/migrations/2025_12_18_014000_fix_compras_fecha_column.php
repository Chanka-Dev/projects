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
        // Agregar columna 'fecha' si no existe, copiar datos de fecha_compra
        if (!Schema::hasColumn('compras', 'fecha')) {
            Schema::table('compras', function (Blueprint $table) {
                $table->date('fecha')->after('numero_compra')->nullable();
            });

            // Copiar datos de fecha_compra a fecha
            DB::statement('UPDATE compras SET fecha = fecha_compra WHERE fecha IS NULL');

            // Ahora hacer fecha NOT NULL
            Schema::table('compras', function (Blueprint $table) {
                $table->date('fecha')->change();
            });
        }

        // Hacer fecha_compra nullable para evitar conflicto
        if (Schema::hasColumn('compras', 'fecha_compra')) {
            Schema::table('compras', function (Blueprint $table) {
                $table->date('fecha_compra')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            // Revertir cambios
            if (Schema::hasColumn('compras', 'fecha')) {
                $table->dropColumn('fecha');
            }
            if (Schema::hasColumn('compras', 'fecha_compra')) {
                $table->date('fecha_compra')->nullable(false)->change();
            }
        });
    }
};
