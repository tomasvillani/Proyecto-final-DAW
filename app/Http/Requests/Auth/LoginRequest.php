<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dni' => ['required', 'regex:/^[0-9]{8}[A-Z]{1}$/i', 'dni_espanol', 'exists:users,dni'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'dni.required' => 'El DNI es obligatorio',
            'dni.regex' => 'Este DNI no es válido',
            'dni.exists' => 'Este DNI no está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'dni.dni_espanol' => 'La letra de este DNI no es válida',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Intentar autenticar al usuario
        if (! Auth::attempt($this->only('dni', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Contraseña incorrecta'
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'dni' => 'Demasiados intentos fallidos. Vuelve en '.$seconds.' segundos'
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('dni')).'|'.$this->ip());
    }
}
