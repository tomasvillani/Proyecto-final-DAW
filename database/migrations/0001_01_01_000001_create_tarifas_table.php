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
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre de la tarifa
            $table->integer('duracion'); // Duración en días
            $table->text('descripcion')->nullable(); // Descripción de la tarifa
            $table->decimal('precio', 8, 2); // Precio con dos decimales
            $table->json('clases'); // Almacena un array de clases en formato JSON
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};
