<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tarifa extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'duracion', 'descripcion', 'precio'];

    // Relación con Usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
