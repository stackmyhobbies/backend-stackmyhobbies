<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailQueued extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu dirección de correo electrónico')
            ->greeting('¡Hola!')
            ->line('Por favor verifica tu dirección de correo electrónico haciendo clic en el botón de abajo.')
            ->action('Verificar correo', $verificationUrl)
            ->line('Si no creaste una cuenta, puedes ignorar este correo.');
    }

    protected function verificationUrl($notifiable): string
    {
        $hash = $notifiable->email_verification_token ?? sha1($notifiable->getEmailForVerification());

        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => $hash,
            ]
        );

        return rtrim(config('app.frontend_url', ''), '/').'/auth/verify-email?url='.urlencode($temporarySignedUrl);
    }
}
