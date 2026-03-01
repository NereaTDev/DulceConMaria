<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonsViewTest extends TestCase
{
    use RefreshDatabase;

    protected function createUserWithCourseAndLesson(?string $embedUrl = null): array
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

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lección 1',
            'order' => 1,
            'video_url' => $embedUrl,
        ]);

        return [$user, $course, $lesson];
    }

    public function test_lesson_with_youtube_url_renders_player_container(): void
    {
        [$user, $course, $lesson] = $this->createUserWithCourseAndLesson('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this
            ->actingAs($user)
            ->get(route('campus.lessons.show', $lesson));

        $response->assertStatus(200);
        $response->assertSee('id="lesson-video-'.$lesson->id.'"', false);
        $response->assertSee('<iframe', false);
    }

    public function test_lesson_with_non_youtube_url_renders_iframe(): void
    {
        [$user, $course, $lesson] = $this->createUserWithCourseAndLesson('https://player.vimeo.com/video/123456');

        $response = $this
            ->actingAs($user)
            ->get(route('campus.lessons.show', $lesson));

        $response->assertStatus(200);
        $response->assertSee('<iframe', false);
    }

    public function test_lesson_without_video_shows_placeholder(): void
    {
        [$user, $course, $lesson] = $this->createUserWithCourseAndLesson(null);

        $response = $this
            ->actingAs($user)
            ->get(route('campus.lessons.show', $lesson));

        $response->assertStatus(200);
        $response->assertSee('Esta lección todavía no tiene vídeo asociado.', false);
    }
}
