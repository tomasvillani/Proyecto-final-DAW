<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Restablece tu contraseña')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Recibimos una solicitud para restablecer tu contraseña en Gym Tinajo.')
            ->line('Si no hiciste esta solicitud, ignora este mensaje.')
            ->action('Restablecer Contraseña', url(route('password.reset', ['token' => $this->token])))  // Corregir URL aquí
            ->line('Si tienes algún problema, contáctanos en gymtinajo@gmail.com.')
            ->salutation('Atentamente, el equipo de Gym Tinajo.');
    }    
}