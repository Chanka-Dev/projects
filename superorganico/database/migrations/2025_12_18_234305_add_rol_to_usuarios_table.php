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
        // Primero actualizar los usuarios existentes con rol 'cajero' a 'empleado'
        DB::statement("UPDATE usuarios SET rol = 'empleado' WHERE rol = 'cajero'");
        
        Schema::table('usuarios', function (Blueprint $table) {
            // Agregar campo name si no existe
            if (!Schema::hasColumn('usuarios', 'name')) {
                $table->string('name')->after('id');
            }
            
            // Modificar el enum de rol para cambiar 'cajero' por 'empleado'
            DB::statement("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('administrador', 'empleado') NOT NULL DEFAULT 'empleado'");
        });
        
        // Actualizar el campo name para usuarios sin nombre
        DB::statement("UPDATE usuarios SET name = CONCAT('Usuario ', id) WHERE name IS NULL OR name = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            DB::statement("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('administrador', 'cajero') NOT NULL DEFAULT 'cajero'");
            
            if (Schema::hasColumn('usuarios', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
