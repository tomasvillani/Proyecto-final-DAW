<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'user_id',  // Agrega el campo 'user_id'
        'clase',    // Agrega otros campos necesarios
        'dia',
        'hora',
    ];
}
