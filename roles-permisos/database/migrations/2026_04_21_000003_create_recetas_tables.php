<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('fuente')->nullable();
            $table->string('link')->nullable();
            $table->string('imagen')->nullable();
            $table->text('instrucciones')->nullable(); // instrucciones generales (sin partes)
            $table->timestamps();
        });

        Schema::create('receta_partes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->string('titulo'); // ej: "Para la mayonesa"
            $table->integer('orden')->default(0);
            $table->text('instrucciones')->nullable();
            $table->timestamps();
        });

        Schema::create('receta_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->foreignId('receta_parte_id')->nullable()->constrained('receta_partes')->cascadeOnDelete();
            $table->foreignId('ingrediente_id')->constrained('ingredientes')->restrictOnDelete();
            $table->string('cantidad')->nullable(); // "½ taza", "2", "1 pizca"
            $table->string('notas')->nullable();    // "remojadas", "finamente picado"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receta_ingredientes');
        Schema::dropIfExists('receta_partes');
        Schema::dropIfExists('recetas');
    }
};
