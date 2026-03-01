<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEnrollmentsVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_grant_all_courses_admin_sees_new_enrollment_button(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'grant_all_courses' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/enrollments');

        $response->assertStatus(200);
        $response->assertSee('Nueva inscripción');
    }

    public function test_regular_admin_without_grant_all_courses_does_not_see_new_enrollment_button(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'grant_all_courses' => false,
        ]);

        $response = $this->actingAs($admin)->get('/admin/enrollments');

        $response->assertStatus(200);
        $response->assertDontSee('Nueva inscripción');
    }

    public function test_non_admin_never_sees_new_enrollment_button(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'grant_all_courses' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/enrollments');

        // middleware de admin debería bloquear, pero en cualquier caso nos aseguramos
        $response->assertStatus(403)->assertDontSee('Nueva inscripción');
    }
}
