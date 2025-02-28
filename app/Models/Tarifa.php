<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tarifa extends Model
{
    //
    use HasFactory;

    // Relación con el modelo User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
