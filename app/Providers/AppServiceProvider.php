<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('dni_espanol', function ($attribute, $value, $parameters, $validator) {
            // Comprobar el formato del DNI
            if (preg_match('/^[0-9]{8}[A-Z]{1}$/i', $value)) {
                // Extraer el número y la letra del DNI
                $dni_number = substr($value, 0, 8);
                $dni_letter = strtoupper(substr($value, 8, 1));
    
                // Array de letras válidas para el cálculo
                $valid_letters = 'TRWAGMYFPDXBNJZSQVHLCKE'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
                // Calcular la letra correcta
                $calculated_letter = $valid_letters[$dni_number % 23];
    
                // Comparar la letra calculada con la letra del DNI
                return $dni_letter === strtoupper($calculated_letter);
            }
    
            return false;
        });
    }
}
