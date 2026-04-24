<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingredientes', function (Blueprint $table) {
            $table->string('unidad_default', 50)->nullable()->after('nombre');   // taza, gramo, cdita...
            $table->string('categoria', 80)->nullable()->after('unidad_default'); // Lácteo, Vegetal, Proteína...
            $table->text('descripcion')->nullable()->after('categoria');
            $table->boolean('es_alergeno')->default(false)->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('ingredientes', function (Blueprint $table) {
            $table->dropColumn(['unidad_default', 'categoria', 'descripcion', 'es_alergeno']);
        });
    }
};
