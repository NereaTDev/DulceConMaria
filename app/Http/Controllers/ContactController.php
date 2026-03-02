<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Mail\ContactConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $apiKey  = env('BREVO_API_KEY');

        try {
            if (! $apiKey) {
                throw new \RuntimeException('BREVO_API_KEY no configurada');
            }

            $fromEmail = env('MAIL_FROM_ADDRESS', 'no-reply@dulceconmaria.com');
            $fromName  = env('MAIL_FROM_NAME', 'DulceConMaría');

            // 1) Email para ti: resumen del mensaje
            $adminResponse = Http::withHeaders([
                'api-key'      => $apiKey,
                'accept'       => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'email' => $fromEmail,
                    'name'  => $fromName,
                ],
                'to' => [[
                    'email' => $toEmail,
                    'name'  => $fromName,
                ]],
                'subject' => 'Nuevo mensaje de contacto desde DulceConMaría',
                'htmlContent' => view('emails.contact-message', [
                    'name'        => $validated['name'],
                    'email'       => $validated['email'],
                    'messageText' => $validated['message'] ?? null,
                ])->render(),
            ]);

            if ($adminResponse->failed()) {
                throw new \RuntimeException('Brevo API error (admin) '.$adminResponse->status().': '.$adminResponse->body());
            }

            // 2) Email de confirmación para la persona que escribe
            $userResponse = Http::withHeaders([
                'api-key'      => $apiKey,
                'accept'       => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'email' => $fromEmail,
                    'name'  => $fromName,
                ],
                'to' => [[
                    'email' => $validated['email'],
                    'name'  => $validated['name'],
                ]],
                'subject' => 'Hemos recibido tu mensaje – DulceConMaría',
                'htmlContent' => view('emails.contact-confirmation', [
                    'name' => $validated['name'],
                ])->render(),
            ]);

            if ($userResponse->failed()) {
                throw new \RuntimeException('Brevo API error (user) '.$userResponse->status().': '.$userResponse->body());
            }
        } catch (\Throwable $e) {
            // Logueamos el error para poder verlo en logs de Laravel / Render
            Log::error('Error enviando emails de contacto: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return back()->with('status', 'contact.sent');
    }
}
