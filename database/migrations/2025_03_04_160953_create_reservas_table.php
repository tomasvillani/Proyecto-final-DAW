<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();  // ID de reserva
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // ID de usuario como FK
            $table->string('clase');  // Clase reservada
            $table->date('dia');  // Día de la reserva
            $table->time('hora');  // Hora de la reserva
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
