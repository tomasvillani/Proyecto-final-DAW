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
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Lunes', 'clase' => 'Tonificación'],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Lunes', 'clase' => 'Musculación'],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Lunes', 'clase' => 'Yoga'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Lunes', 'clase' => 'Crossfit'],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Lunes', 'clase' => 'Pilates'],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Lunes', 'clase' => 'Cardio'],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Lunes', 'clase' => 'Power Lifting'],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Lunes', 'clase' => 'Pérdida de Peso'],

            // Martes
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Martes', 'clase' => 'Cardio'],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Martes', 'clase' => 'Yoga'],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Martes', 'clase' => 'Musculación'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Martes', 'clase' => 'Power Lifting'],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Martes', 'clase' => 'Tonificación'],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Martes', 'clase' => 'Crossfit'],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Martes', 'clase' => 'Pilates'],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Martes', 'clase' => 'Pérdida de Peso'],

            // Miércoles
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Miércoles', 'clase' => 'Crossfit'],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Miércoles', 'clase' => 'Tonificación'],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Miércoles', 'clase' => 'Cardio'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Miércoles', 'clase' => 'Yoga'],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Miércoles', 'clase' => 'Power Lifting'],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Miércoles', 'clase' => 'Pilates'],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Miércoles', 'clase' => 'Pérdida de Peso'],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Miércoles', 'clase' => 'Musculación'],

            // Jueves
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Jueves', 'clase' => 'Pilates'],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Jueves', 'clase' => 'Crossfit'],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Jueves', 'clase' => 'Musculación'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Jueves', 'clase' => 'Tonificación'],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Jueves', 'clase' => 'Yoga'],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Jueves', 'clase' => 'Cardio'],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Jueves', 'clase' => 'Power Lifting'],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Jueves', 'clase' => 'Pérdida de Peso'],

            // Viernes
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30', 'dia' => 'Viernes', 'clase' => 'Pérdida de Peso'],
            ['hora_inicio' => '10:00', 'hora_fin' => '11:30', 'dia' => 'Viernes', 'clase' => 'Power Lifting'],
            ['hora_inicio' => '12:00', 'hora_fin' => '13:30', 'dia' => 'Viernes', 'clase' => 'Pilates'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30', 'dia' => 'Viernes', 'clase' => 'Cardio'],
            ['hora_inicio' => '16:00', 'hora_fin' => '17:30', 'dia' => 'Viernes', 'clase' => 'Crossfit'],
            ['hora_inicio' => '18:00', 'hora_fin' => '19:30', 'dia' => 'Viernes', 'clase' => 'Musculación'],
            ['hora_inicio' => '19:30', 'hora_fin' => '21:00', 'dia' => 'Viernes', 'clase' => 'Tonificación'],
            ['hora_inicio' => '21:00', 'hora_fin' => '22:30', 'dia' => 'Viernes', 'clase' => 'Yoga'],
        ]);
    }
}