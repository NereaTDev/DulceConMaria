<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_campus(): void
    {
        $response = $this->get('/campus');

        $response->assertRedirect(route('login'));
    }

    public function test_student_with_paid_enrollment_can_see_campus(): void
    {
        $user = User::factory()->create();

        $course = Course::create([
            'title' => 'Curso de prueba',
            'slug' => 'curso-prueba',
            'description' => 'Descripción de prueba',
            'level' => 'beginner',
        ]);

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($user)->get('/campus');

        $response->assertStatus(200);
        $response->assertSee('Mi campus');
        $response->assertSee('Curso actual');
    }
}
