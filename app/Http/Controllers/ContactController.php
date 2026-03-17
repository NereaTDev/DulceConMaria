<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Mail\ContactConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'message'    => ['nullable', 'string', 'max:2000'],
            // Honeypot: los bots suelen rellenarlo, los humanos no lo ven
            'website_hp' => ['nullable', 'string', 'max:255'],
        ]);

        // Si el honeypot viene relleno, asumimos que es spam y no enviamos nada
        if (! empty($validated['website_hp'])) {
            Log::info('Contacto bloqueado por honeypot', [
                'name'       => $validated['name'] ?? null,
                'email'      => $validated['email'] ?? null,
                'website_hp' => $validated['website_hp'],
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Respondemos como si todo hubiera ido bien para no dar pistas al bot
            return back()->with('status', 'contact.sent');
        }

        $toEmail = config('mail.from.address', 'nerea.trebol@yahoo.es');

        try {
            // 1) Email para ti: resumen del mensaje (via SMTP Brevo)
            Mail::to($toEmail)->send(new ContactMessage(
                $validated['name'],
                $validated['email'],
                $validated['message'] ?? null,
            ));

            // 2) Email de confirmación para la persona que escribe
            Mail::to($validated['email'])->send(new ContactConfirmation(
                $validated['name'],
            ));
        } catch (\Throwable $e) {
            // Logueamos el error para poder verlo en logs de Laravel / Render
            Log::error('Error enviando emails de contacto: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return back()->with('status', 'contact.sent');
    }
}
