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
        Schema::create('categoria_gastos', function (Blueprint $table) {
            $table->id();
             $table->string('nombre');
            $table->boolean('activa')->default(true);
            $table->foreignId('cuenta_id')->nullable()->constrained('plan_cuentas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_gastos');
    }
};
