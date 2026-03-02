<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifyEmailCodeController extends Controller
{
    /**
     * Mostrar el formulario para introducir el código de verificación.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('campus');
        }

        return view('auth.verify-email-code');
    }

    /**
     * Procesar el código de verificación enviado por el usuario.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('campus');
        }

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        if ($user->verifyEmailWithCode($validated['code'])) {
            return redirect()->route('campus')->with('status', 'email-verified');
        }

        return back()->withErrors([
            'code' => 'El código no es correcto. Revisa el email o solicita uno nuevo.',
        ]);
    }
}
