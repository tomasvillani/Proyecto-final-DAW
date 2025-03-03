<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inicializa Faker
        $faker = Faker::create();

        // Crea 10 eventos ficticios
        foreach (range(1, 10) as $index) {
            DB::table('eventos')->insert([
                'titulo' => $faker->sentence,  // Título del evento
                'descripcion' => $faker->paragraph,  // Descripción del evento
                'fecha' => $faker->date,  // Fecha del evento
                'hora' => $faker->time,  // Hora del evento
                'imagen' => $faker->imageUrl(),  // URL de la imagen
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
