<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    /**
     * Página de tutorial del campus.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->email_verified_at) {
            return redirect()->route('verification.notice');
        }

        return view('campus.onboarding');
    }

    /**
     * Marcar que el usuario ha completado u omitido el onboarding.
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

            return redirect()->route('campus.onboarding');
        }

        if ($action === 'skip') {
            $user->forceFill([
                'dismissed_onboarding_at' => now(),
            ])->save();

            return back();
        }

        return back();
    }
}
