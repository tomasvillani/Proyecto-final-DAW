<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use App\Models\Tarifa;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dni',         
        'name',
        'surname',     
        'email',
        'tipo_usuario',
        'password',
    ];

    protected $casts = [
        'clases' => 'array',  // Esto asegura que 'clases' se trate como un array.
    ];

    /**
     * Get the tarifa associated with the user.
     */
    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    /**
     * Verifica si el usuario puede acceder a la clase dada.
     *
     * @param  string  $clase
     * @return bool
     */
    public function puedeReservar($clase)
    {
        $clasesPermitidas = json_decode($this->tarifa->clases, true);
        return in_array($clase, $clasesPermitidas);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
