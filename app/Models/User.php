<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\WelcomeEmailVerification;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
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

    /**
     * Generar un código de verificación de email de 6 dígitos
     * y guardarlo en la base de datos.
     */
    public function generateEmailVerificationCode(): string
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'email_verification_code' => $code,
        ])->save();

        return $code;
    }

    /**
     * Verificar el email usando un código de verificación.
     */
    public function verifyEmailWithCode(string $code): bool
    {
        if (! $this->email_verification_code) {
            return false;
        }

        if (trim($code) !== $this->email_verification_code) {
            return false;
        }

        $this->forceFill([
            'email_verified_at' => now(),
            'email_verification_code' => null,
        ])->save();

        event(new \Illuminate\Auth\Events\Verified($this));

        return true;
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
     * Enviar el email de verificación de correo (bienvenida).
     *
     * - Usa la notificación estándar de Laravel (WelcomeEmailVerification)
     *   para mantener compatibilidad con tests y mailer por defecto.
     * - Además intenta enviarlo vía API HTTP de Brevo, igual que el
     *   restablecimiento de contraseña, para no depender sólo de SMTP.
     */
    public function sendEmailVerificationNotification(): void
    {
        // Generamos un código de verificación de email específico para este usuario.
        $code = $this->generateEmailVerificationCode();

        // Notificación estándar (usada por los tests y por el mailer de Laravel).
        $this->notify(new WelcomeEmailVerification());

        // Envío adicional vía API HTTP de Brevo si hay API key configurada.
        $apiKey = env('BREVO_API_KEY');

        if (! $apiKey) {
            return;
        }

        try {
            // Construimos la URL de verificación igual que hace VerifyEmail.
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $this->getKey(),
                    'hash' => sha1($this->getEmailForVerification()),
                ]
            );

            $fromEmail = env('MAIL_FROM_ADDRESS', 'no-reply@dulceconmaria.com');
            $fromName = env('MAIL_FROM_NAME', 'DulceConMaría');

            $response = Http::withHeaders([
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
                'subject' => 'Bienvenida a DulceConMaría – Confirma tu correo',
                'htmlContent' => view('emails.welcome-verification', [
                    'user'            => $this,
                    'verificationUrl' => $verificationUrl,
                ])->render(),
            ]);

            if ($response->failed()) {
                report(new \RuntimeException(
                    'Brevo API error '.$response->status().': '.$response->body()
                ));
            }
        } catch (\Throwable $e) {
            // Si falla la llamada HTTP, lo registramos pero no bloqueamos el flujo.
            report($e);
        }
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

            $response = Http::withHeaders([
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

            if ($response->failed()) {
                report(new \RuntimeException(
                    'Brevo API error '.$response->status().': '.$response->body()
                ));
            }
        } catch (\Throwable $e) {
            // Si falla la llamada HTTP, lo registramos pero no bloqueamos el flujo.
            report($e);
        }
    }
}
