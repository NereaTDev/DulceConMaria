<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetUnknownEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_email_shows_validation_error_and_does_not_redirect_as_if_sent(): void
    {
        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'no-existe@example.com',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/forgot-password')
            ->assertSessionHasErrors('email');
    }
}
