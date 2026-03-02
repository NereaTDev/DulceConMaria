<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'grant_all_courses'       => 'boolean',
        'has_seen_onboarding'     => 'boolean',
        'dismissed_onboarding_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'         => 'hashed',
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
     * A partir de ahora delegamos completamente en la notificación
     * WelcomeEmailVerification, que usará el mailer configurado
     * (SMTP Brevo en producción).
     */
    public function sendEmailVerificationNotification(): void
    {
        // Generamos un código de verificación de email específico para este usuario.
        $this->generateEmailVerificationCode();

        // Notificación estándar (usada por los tests y por el mailer de Laravel).
        $this->notify(new WelcomeEmailVerification());
    }

    /**
     * Enviar la notificación de restablecimiento de contraseña.
     *
     * Usamos únicamente la notificación estándar de Laravel, que
     * se enviará vía SMTP Brevo según la configuración MAIL_*.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
