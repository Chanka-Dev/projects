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
        Schema::table('productos', function (Blueprint $table) {
            // Agregar campos faltantes
            if (!Schema::hasColumn('productos', 'codigo')) {
                $table->string('codigo')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('productos', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('productos', 'tipo')) {
                $table->enum('tipo', ['verdura', 'fruta', 'grano', 'lacteo', 'otro'])->default('otro')->after('categoria');
            }
            if (!Schema::hasColumn('productos', 'unidad_medida')) {
                $table->enum('unidad_medida', ['kg', 'unidad', 'litro', 'bolsa', 'caja'])->default('unidad')->after('precio_venta');
            }
            if (!Schema::hasColumn('productos', 'stock_minimo')) {
                $table->integer('stock_minimo')->default(0)->after('stock');
            }
            if (!Schema::hasColumn('productos', 'activo')) {
                $table->boolean('activo')->default(true)->after('perecedero');
            }
            if (!Schema::hasColumn('productos', 'dias_alerta_vencimiento')) {
                $table->integer('dias_alerta_vencimiento')->default(7)->after('dias_caducidad');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'codigo',
                'descripcion',
                'tipo',
                'unidad_medida',
                'stock_minimo',
                'activo',
                'dias_alerta_vencimiento'
            ]);
        });
    }
};
