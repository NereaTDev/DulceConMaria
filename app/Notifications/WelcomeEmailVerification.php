<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeEmailVerification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Bienvenida a DulceConMaría – Confirma tu correo')
            ->greeting('Hola '.$notifiable->name.' 👋')
            ->line('¡Gracias por registrarte en el campus de DulceConMaría!')
            ->line('Antes de acceder a todos tus contenidos, necesitamos que confirmes que este correo es tuyo.')
            ->action('Confirmar mi correo', $verificationUrl)
            ->line('Si no has creado esta cuenta, puedes ignorar este mensaje.');
    }
}
