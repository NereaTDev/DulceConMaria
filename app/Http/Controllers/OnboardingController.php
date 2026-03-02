<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    /**
     * Marcar que el usuario ha completado u omitido el onboarding.
     *
     * Esta acción solo actualiza flags en BBDD; el tutorial se muestra
     * íntegramente en el modal del propio campus (sin cambiar de URL).
     */
    public function complete(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $action = $request->input('action', 'view');

        if ($action === 'view') {
            $user->forceFill([
                'has_seen_onboarding'     => true,
                'dismissed_onboarding_at' => null,
            ])->save();

            // Redirigimos siempre al campus sin query string para que
            // no se vuelva a forzar la apertura del modal.
            return redirect()->route('campus');
        }

        if ($action === 'skip') {
            $user->forceFill([
                'dismissed_onboarding_at' => now(),
            ])->save();

            // Igual: volvemos al campus limpio para evitar bucles.
            return redirect()->route('campus');
        }

        return redirect()->route('campus');
    }
}
