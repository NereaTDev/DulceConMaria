<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────────────────
    // 1. HTTP Security Headers
    // ──────────────────────────────────────────────────────────────────────────

    public function test_security_headers_present_on_public_pages(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeaderMissing('X-Powered-By');
    }

    public function test_csp_header_present(): void
    {
        $response = $this->get('/');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertNotNull($csp, 'Content-Security-Policy header must be present');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("frame-src 'none'", $csp);
    }

    public function test_csp_header_present_on_auth_pages(): void
    {
        $response = $this->get('/login');

        $this->assertNotNull(
            $response->headers->get('Content-Security-Policy'),
            'CSP must be present on auth pages too'
        );
    }

    public function test_hsts_header_not_set_outside_production(): void
    {
        // In test environment, HSTS should NOT be sent (only in production)
        $response = $this->get('/');

        $response->assertHeaderMissing('Strict-Transport-Security');
    }

    public function test_permissions_policy_header_disables_sensitive_features(): void
    {
        $response = $this->get('/');

        $policy = $response->headers->get('Permissions-Policy');
        $this->assertNotNull($policy);
        $this->assertStringContainsString('camera=()', $policy);
        $this->assertStringContainsString('microphone=()', $policy);
        $this->assertStringContainsString('geolocation=()', $policy);
        $this->assertStringContainsString('payment=()', $policy);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 2. Dangerous routes are gone
    // ──────────────────────────────────────────────────────────────────────────

    public function test_migration_route_does_not_exist(): void
    {
        $response = $this->get('/__run-migrations-20260227');

        $response->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 3. User enumeration — password reset
    // ──────────────────────────────────────────────────────────────────────────

    public function test_password_reset_returns_same_response_for_unknown_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        // Must NOT expose that the email doesn't exist
        $response->assertSessionMissing('errors');
        $response->assertSessionHas('status');
    }

    public function test_password_reset_returns_same_response_for_known_email(): void
    {
        User::factory()->create(['email' => 'real@example.com']);

        $response = $this->post('/forgot-password', [
            'email' => 'real@example.com',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_password_reset_responses_are_indistinguishable(): void
    {
        User::factory()->create(['email' => 'exists@example.com']);

        $knownResponse   = $this->post('/forgot-password', ['email' => 'exists@example.com']);
        $unknownResponse = $this->post('/forgot-password', ['email' => 'nobody@example.com']);

        // Both should have a 'status' flash — no error differences that reveal existence
        $knownResponse->assertSessionHas('status');
        $unknownResponse->assertSessionHas('status');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 4. Verification code — stored hashed, not plain text
    // ──────────────────────────────────────────────────────────────────────────

    public function test_email_verification_code_is_stored_hashed(): void
    {
        $user = User::factory()->unverified()->create();

        $plainCode = $user->generateEmailVerificationCode();

        $user->refresh();

        // The stored value must NOT be the plain 6-digit code
        $this->assertNotEquals($plainCode, $user->email_verification_code);

        // The stored value must be a SHA-256 hex hash (64 chars)
        $this->assertEquals(64, strlen($user->email_verification_code));
    }

    public function test_email_verification_accepts_correct_code(): void
    {
        $user = User::factory()->unverified()->create();
        $code = $user->generateEmailVerificationCode();

        $result = $user->verifyEmailWithCode($code);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->email_verification_code);
    }

    public function test_email_verification_rejects_wrong_code(): void
    {
        $user = User::factory()->unverified()->create();
        $user->generateEmailVerificationCode();

        $result = $user->verifyEmailWithCode('000000');

        $this->assertFalse($result);
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_rejects_empty_code(): void
    {
        $user = User::factory()->unverified()->create();
        $user->generateEmailVerificationCode();

        $this->assertFalse($user->verifyEmailWithCode(''));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 5. Rate limiting — verification code endpoint
    // ──────────────────────────────────────────────────────────────────────────

    public function test_verification_code_endpoint_is_rate_limited(): void
    {
        $user = User::factory()->unverified()->create();
        $user->generateEmailVerificationCode();

        // Hit the endpoint 5 times (max allowed per minute)
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)->post('/verify-email/code', ['code' => '000000']);
        }

        // 6th request should be rate limited (429)
        $response = $this->actingAs($user)->post('/verify-email/code', ['code' => '000000']);
        $response->assertStatus(429);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 6. Access control — campus and private routes
    // ──────────────────────────────────────────────────────────────────────────

    public function test_campus_requires_authentication(): void
    {
        $this->get('/campus')->assertRedirect('/login');
    }

    public function test_campus_requires_verified_email(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/campus')->assertRedirect();
    }

    public function test_verified_user_can_access_campus(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->get('/campus')->assertStatus(200);
    }

    public function test_profile_requires_authentication(): void
    {
        $this->get('/campus/profile')->assertRedirect('/login');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 7. Access control — admin panel
    // ──────────────────────────────────────────────────────────────────────────

    public function test_guest_cannot_access_any_admin_route(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
        $this->get('/admin/courses')->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin')->assertStatus(403);
        $this->actingAs($user)->get('/admin/users')->assertStatus(403);
        $this->actingAs($user)->get('/admin/courses')->assertStatus(403);
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 8. CSRF middleware is registered for web routes
    // ──────────────────────────────────────────────────────────────────────────

    public function test_csrf_middleware_is_registered_in_web_stack(): void
    {
        $kernel     = app(\Illuminate\Contracts\Http\Kernel::class);
        $middleware = $kernel->getMiddlewareGroups()['web'] ?? [];

        $hasCsrf = collect($middleware)->contains(fn ($m) =>
            is_string($m) && str_contains($m, 'CsrfToken')
        );

        $this->assertTrue($hasCsrf, 'A CSRF token middleware must be in the web middleware group');
    }

    public function test_contact_form_validates_required_fields(): void
    {
        // name and email are required; message is nullable
        $response = $this->post('/contacto', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'email']);
        $response->assertSessionDoesntHaveErrors(['message']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 9. Legal pages are public (smoke test — no auth wall on public content)
    // ──────────────────────────────────────────────────────────────────────────

    public function test_legal_pages_are_publicly_accessible(): void
    {
        $this->get('/privacidad')->assertStatus(200);
        $this->get('/aviso-legal')->assertStatus(200);
        $this->get('/cookies')->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 10. Admin cannot expose error messages to the browser
    // ──────────────────────────────────────────────────────────────────────────

    public function test_admin_user_update_does_not_expose_exceptions_in_response(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'user']);

        // Send invalid data to trigger a validation error (not exception, but ensures no raw dump)
        $response = $this->actingAs($admin)->patch("/admin/users/{$target->id}", [
            'name'  => '',    // required — will fail validation
            'email' => 'bad', // invalid email
            'role'  => 'user',
        ]);

        // Must NOT contain any stack trace or PHP exception text
        $responseBody = $response->getContent();
        $this->assertStringNotContainsString('Exception', $responseBody);
        $this->assertStringNotContainsString('Stack trace', $responseBody);
        $this->assertStringNotContainsString('getMessage', $responseBody);
    }
}
