<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // No queremos enviar correos a direcciones que no existen en nuestra base de datos.
        $exists = User::where('email', $request->input('email'))->exists();

        if (! $exists) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'No encontramos ningún usuario registrado con este correo.',
                ]);
        }

        // En este punto el correo existe: enviamos el enlace de reseteo usando el broker de contraseñas.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
