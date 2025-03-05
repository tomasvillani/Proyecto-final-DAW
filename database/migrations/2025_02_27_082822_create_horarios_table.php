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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id(); // ID_Horario (Primary Key)
            $table->time('hora_inicio'); // Nueva columna para la hora de inicio
            $table->time('hora_fin'); // Nueva columna para la hora de finalización
            $table->string('dia', 20);
            $table->string('clase', 50);
            $table->boolean('disponible')->default(true); // Nuevo campo disponible (booleano)
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
