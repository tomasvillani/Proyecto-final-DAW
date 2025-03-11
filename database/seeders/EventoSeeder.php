<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Http;

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
        foreach (range(1, 12) as $index) {
            try {
                // Petición a Unsplash para obtener una imagen aleatoria
                $response = Http::get("https://api.unsplash.com/photos/random", [
                    'client_id' => env('UNSPLASH_ACCESS_KEY'), // API Key desde .env
                    'query' => 'gym, fitness, workout, sport'  // Especificamos la búsqueda para gimnasio/deporte
                ]);

                // Si la petición fue exitosa, obtenemos la URL de la imagen
                if ($response->successful() && isset($response->json()['urls']['regular'])) {
                    $imageUrl = $response->json()['urls']['regular'];
                } else {
                    throw new \Exception('No se encontró imagen.');
                }

            } catch (\Exception $e) {
                // En caso de error, usar una imagen por defecto
                $imageUrl = 'https://via.placeholder.com/800x600';
            }

            // Inserción en la base de datos
            DB::table('eventos')->insert([
                'titulo' => $faker->sentence,
                'descripcion' => $faker->paragraph,
                'fecha' => $faker->date,
                'hora' => $faker->time,
                'imagen' => $imageUrl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
