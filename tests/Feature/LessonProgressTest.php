<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonProgressTest extends TestCase
{
    use RefreshDatabase;

    protected function createCourseWithLesson(): array
    {
        $course = Course::create([
            'title' => 'Curso de prueba',
            'slug' => 'curso-prueba',
            'description' => 'Descripción de prueba',
            'level' => 'beginner',
        ]);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lección 1',
            'order' => 1,
        ]);

        return [$course, $lesson];
    }

    public function test_student_with_paid_enrollment_can_mark_lesson_progress(): void
    {
        [$course, $lesson] = $this->createCourseWithLesson();
        $user = User::factory()->create();

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'paid',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('campus.lessons.progress', $lesson), ['completed' => true]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_student_without_enrollment_cannot_mark_lesson_progress(): void
    {
        [$course, $lesson] = $this->createCourseWithLesson();
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('campus.lessons.progress', $lesson), ['completed' => true]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_admin_can_mark_lesson_progress_without_enrollment(): void
    {
        [$course, $lesson] = $this->createCourseWithLesson();
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('campus.lessons.progress', $lesson), ['completed' => true]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $admin->id,
            'lesson_id' => $lesson->id,
        ]);
    }
}
