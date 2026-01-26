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
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('iva', 10, 2)->default(0)->after('subtotal');
            $table->decimal('it', 10, 2)->default(0)->after('iva');
            $table->text('observaciones')->nullable()->after('cambio');
            $table->date('fecha')->nullable()->after('fecha_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['iva', 'it', 'observaciones', 'fecha']);
        });
    }
};
