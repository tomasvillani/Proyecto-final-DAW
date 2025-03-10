<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    //
    const DIAS = ['Lunes','Martes','Miércoles','Jueves','Viernes'];
    protected $fillable = [
        'clase', // El nombre de la clase
        'hora_inicio',
        'hora_fin',
        'dia',
        'disponible',
    ];
}
