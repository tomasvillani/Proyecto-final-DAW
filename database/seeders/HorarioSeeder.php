<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        Horario::insert([
            // Lunes
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Lunes', 'clase' => 'Tonificación', 'disponible' => true],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Lunes', 'clase' => 'Musculación', 'disponible' => true],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Lunes', 'clase' => 'Yoga', 'disponible' => true],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Lunes', 'clase' => 'Crossfit', 'disponible' => true],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Lunes', 'clase' => 'Pilates', 'disponible' => true],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Lunes', 'clase' => 'Cardio', 'disponible' => true],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Lunes', 'clase' => 'Power Lifting', 'disponible' => true],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Lunes', 'clase' => 'Pérdida de Peso', 'disponible' => true],

            // Martes
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Martes', 'clase' => 'Cardio', 'disponible' => true],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Martes', 'clase' => 'Yoga', 'disponible' => true],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Martes', 'clase' => 'Musculación', 'disponible' => true],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Martes', 'clase' => 'Power Lifting', 'disponible' => true],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Martes', 'clase' => 'Tonificación', 'disponible' => true],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Martes', 'clase' => 'Crossfit', 'disponible' => true],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Martes', 'clase' => 'Pilates', 'disponible' => true],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Martes', 'clase' => 'Pérdida de Peso', 'disponible' => true],

            // Miércoles
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Miércoles', 'clase' => 'Crossfit', 'disponible' => true],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Miércoles', 'clase' => 'Tonificación', 'disponible' => true],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Miércoles', 'clase' => 'Cardio', 'disponible' => true],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Miércoles', 'clase' => 'Yoga', 'disponible' => true],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Miércoles', 'clase' => 'Power Lifting', 'disponible' => true],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Miércoles', 'clase' => 'Pilates', 'disponible' => true],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Miércoles', 'clase' => 'Pérdida de Peso', 'disponible' => true],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Miércoles', 'clase' => 'Musculación', 'disponible' => true],

            // Jueves
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Jueves', 'clase' => 'Pilates', 'disponible' => true],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Jueves', 'clase' => 'Crossfit', 'disponible' => true],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Jueves', 'clase' => 'Musculación', 'disponible' => true],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Jueves', 'clase' => 'Tonificación', 'disponible' => true],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Jueves', 'clase' => 'Yoga', 'disponible' => true],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Jueves', 'clase' => 'Cardio', 'disponible' => true],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Jueves', 'clase' => 'Power Lifting', 'disponible' => true],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Jueves', 'clase' => 'Pérdida de Peso', 'disponible' => true],

            // Viernes
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Viernes', 'clase' => 'Pérdida de Peso', 'disponible' => true],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Viernes', 'clase' => 'Power Lifting', 'disponible' => true],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Viernes', 'clase' => 'Pilates', 'disponible' => true],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Viernes', 'clase' => 'Cardio', 'disponible' => true],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Viernes', 'clase' => 'Crossfit', 'disponible' => true],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Viernes', 'clase' => 'Musculación', 'disponible' => true],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Viernes', 'clase' => 'Tonificación', 'disponible' => true],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Viernes', 'clase' => 'Yoga', 'disponible' => true],
        ]);
    }
}