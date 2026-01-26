<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hacer CI nullable en clientes
        if (Schema::hasColumn('clientes', 'ci')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('ci')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // No revertir
    }
};
