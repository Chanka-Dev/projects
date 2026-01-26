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
        Schema::table('historial_servicios', function (Blueprint $table) {
            $table->decimal('porcentaje_comision', 5, 2)->default(0)->after('precio_cobrado');
            $table->decimal('monto_comision', 10, 2)->default(0)->after('porcentaje_comision');
            $table->foreignId('pago_trabajadora_id')->nullable()->after('monto_comision')->constrained('pago_trabajadoras')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historial_servicios', function (Blueprint $table) {
            $table->dropForeign(['pago_trabajadora_id']);
            $table->dropColumn(['porcentaje_comision', 'monto_comision', 'pago_trabajadora_id']);
        });
    }
};
