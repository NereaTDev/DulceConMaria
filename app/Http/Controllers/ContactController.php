<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        Mail::to($toEmail)->send(new ContactMessage(
            $validated['name'],
            $validated['email'],
            $validated['message'] ?? null,
        ));

        return back()->with('status', 'contact.sent');
    }
}
