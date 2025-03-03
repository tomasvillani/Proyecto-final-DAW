<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'titulo',       // Título del evento
        'descripcion',  // Descripción del evento
        'fecha',        // Fecha del evento
        'hora',         // Hora del evento
        'imagen',       // Imagen del evento
    ];    
}
