<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarifa;

class TarifaSeeder extends Seeder
{
    public function run(): void
    {
        // Tarifa Completa
        Tarifa::create([
            'nombre' => 'Tarifa Completa',
            'duracion' => 30, // 30 días
            'descripcion' => 'Acceso a todas las clases del gimnasio',
            'precio' => 100.00
        ]);

        // Tarifa Personalizada (Sin clases aún)
        Tarifa::create([
            'nombre' => 'Tarifa Personalizada',
            'duracion' => 30,
            'descripcion' => 'Selecciona tus clases favoritas',
            'precio' => 70.00
        ]);
    }
}
