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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('periodo'); 
            $table->decimal('saldo_inicial', 12, 2)->default(0);
            $table->decimal('debe_periodo', 12, 2)->default(0);
            $table->decimal('haber_periodo', 12, 2)->default(0);
            $table->decimal('saldo_final', 12, 2)->default(0);
            $table->foreignId('cuenta_id')->constrained('plan_cuentas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
