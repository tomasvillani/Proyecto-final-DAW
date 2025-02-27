<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;

class HorarioController extends Controller
{
    //
    public function cargar_horarios()
    {
        $horarios = Horario::all();
        $dias = Horario::DIAS;
        return view('classes',compact('horarios','dias'));
    }
}
