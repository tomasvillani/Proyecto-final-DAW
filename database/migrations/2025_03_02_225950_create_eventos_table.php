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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();  // Campo ID (autoincremental)
            $table->string('titulo');  // Título del evento
            $table->text('descripcion');  // Descripción del evento
            $table->date('fecha');  // Fecha del evento
            $table->time('hora');  // Hora del evento
            $table->string('imagen')->nullable();  // Imagen del evento (puede ser null)
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
