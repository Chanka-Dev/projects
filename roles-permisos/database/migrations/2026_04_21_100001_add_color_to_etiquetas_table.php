<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('etiquetas', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('nombre'); // ej: #e74c3c
        });
    }

    public function down(): void
    {
        Schema::table('etiquetas', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
