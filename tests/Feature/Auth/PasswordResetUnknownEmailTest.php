<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetUnknownEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_email_returns_same_success_response_as_known_email(): void
    {
        // Security: we must NOT reveal whether the email exists in the database.
        // The response must always look like a success to prevent user enumeration.
        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'no-existe@example.com',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHas('status')
            ->assertSessionMissing('errors');
    }
}
