<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'country',
        'instagram',
        'notes',
        'grant_all_courses',
        'password',
        'role',
    ];

    protected $casts = [
        'grant_all_courses' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Enviar la notificación de restablecimiento de contraseña.
     *
     * Mantenemos la notificación estándar de Laravel para que los tests y el
     * logging sigan funcionando, pero además intentamos enviar el correo a
     * través de la API HTTP de Brevo para no depender de SMTP.
     */
    public function sendPasswordResetNotification($token): void
    {
        // Notificación estándar (usada por los tests y por el mailer de Laravel).
        $this->notify(new ResetPasswordNotification($token));

        // Envío adicional vía API HTTP de Brevo si hay API key configurada.
        $apiKey = env('BREVO_API_KEY');

        if (! $apiKey) {
            return;
        }

        try {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $this->getEmailForPasswordReset(),
            ], false));

            $fromEmail = env('MAIL_FROM_ADDRESS', 'no-reply@dulceconmaria.com');
            $fromName = env('MAIL_FROM_NAME', 'DulceConMaría');

            Http::withHeaders([
                'api-key' => $apiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'email' => $fromEmail,
                    'name'  => $fromName,
                ],
                'to' => [[
                    'email' => $this->email,
                    'name'  => $this->name ?? $this->email,
                ]],
                'subject' => 'Restablece tu contraseña de DulceConMaría',
                'htmlContent' => view('emails.password-reset', [
                    'user'     => $this,
                    'resetUrl' => $resetUrl,
                ])->render(),
            ]);
        } catch (\Throwable $e) {
            // Si falla la llamada HTTP, lo registramos pero no bloqueamos el flujo.
            report($e);
        }
    }
}
