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
        // Agregar campos a clientes
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'nit')) {
                $table->string('nit')->nullable()->after('ci');
            }
            if (!Schema::hasColumn('clientes', 'tipo')) {
                $table->enum('tipo', ['persona', 'empresa'])->default('persona')->after('nombre');
            }
            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->text('direccion')->nullable()->after('email');
            }
            if (!Schema::hasColumn('clientes', 'ciudad')) {
                $table->string('ciudad')->nullable()->after('direccion');
            }
            if (!Schema::hasColumn('clientes', 'pais')) {
                $table->string('pais')->default('Bolivia')->after('ciudad');
            }
            if (!Schema::hasColumn('clientes', 'activo')) {
                $table->boolean('activo')->default(true)->after('pais');
            }
        });
        
        // Agregar campos a proveedores
        Schema::table('proveedores', function (Blueprint $table) {
            if (!Schema::hasColumn('proveedores', 'nit')) {
                $table->string('nit')->nullable()->after('ci');
            }
            if (!Schema::hasColumn('proveedores', 'direccion')) {
                $table->text('direccion')->nullable()->after('email');
            }
            if (!Schema::hasColumn('proveedores', 'ciudad')) {
                $table->string('ciudad')->nullable()->after('direccion');
            }
            if (!Schema::hasColumn('proveedores', 'pais')) {
                $table->string('pais')->default('Bolivia')->after('ciudad');
            }
            if (!Schema::hasColumn('proveedores', 'activo')) {
                $table->boolean('activo')->default(true)->after('pais');
            }
        });
        
        // Agregar campos a compras
        Schema::table('compras', function (Blueprint $table) {
            if (!Schema::hasColumn('compras', 'numero_factura')) {
                $table->string('numero_factura')->nullable()->after('numero_compra');
            }
            if (!Schema::hasColumn('compras', 'credito_fiscal')) {
                $table->decimal('credito_fiscal', 10, 2)->default(0)->after('impuestos');
            }
            if (!Schema::hasColumn('compras', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('total');
            }
            
            // Agregar fecha si no existe
            if (!Schema::hasColumn('compras', 'fecha')) {
                $table->date('fecha')->nullable()->after('numero_factura');
            }
        });
        
        // Agregar campos a ventas
        Schema::table('ventas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas', 'tipo_comprobante')) {
                $table->enum('tipo_comprobante', ['factura', 'nota_venta'])->default('factura')->after('fecha_hora');
            }
            if (!Schema::hasColumn('ventas', 'iva')) {
                $table->decimal('iva', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('ventas', 'it')) {
                $table->decimal('it', 10, 2)->default(0)->after('iva');
            }
            if (!Schema::hasColumn('ventas', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('cambio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['nit', 'tipo', 'direccion', 'ciudad', 'pais', 'activo']);
        });
        
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['nit', 'direccion', 'ciudad', 'pais', 'activo']);
        });
        
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn(['numero_factura', 'fecha', 'credito_fiscal', 'observaciones']);
        });
        
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['tipo_comprobante', 'iva', 'it', 'observaciones']);
        });
    }
};
