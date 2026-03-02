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
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

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
