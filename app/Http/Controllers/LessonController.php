<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function show(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $course = $lesson->course;

        // Admin siempre tiene acceso
        if ($user->role !== 'admin') {
            // Usuarios normales: necesitan Enrollment paid para ese curso
            $hasAccess = $user->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'paid')
                ->exists();

            if (! $hasAccess) {
                abort(403);
            }
        }

        // Cargar recetas asociadas a la lección
        $lesson->load('recipes');

        // Obtener todas las lecciones del curso ordenadas
        $lessons = $course->lessons()->orderBy('order')->get();

        // Localizar el índice de la actual
        $currentIndex = $lessons->search(fn ($l) => $l->id === $lesson->id);

        $prevLesson = $currentIndex > 0
            ? $lessons[$currentIndex - 1]
            : null;

        $nextLesson = $currentIndex < $lessons->count() - 1
            ? $lessons[$currentIndex + 1]
            : null;

        // Calcular progreso de curso para el usuario actual
        $completedCount = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();

        $totalLessons = max($lessons->count(), 1);
        $courseProgress = round(($completedCount / $totalLessons) * 100);

        return view('lessons.show', [
            'lesson'          => $lesson,
            'course'          => $course,
            'prevLesson'      => $prevLesson,
            'nextLesson'      => $nextLesson,
            'courseProgress'  => $courseProgress,
            'completedCount'  => $completedCount,
            'totalLessons'    => $totalLessons,
        ]);
    }

    public function markProgress(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $course = $lesson->course;

        // Verificar acceso igual que en show
        if ($user->role !== 'admin') {
            $hasAccess = $user->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'paid')
                ->exists();

            if (! $hasAccess) {
                abort(403);
            }
        }

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id'   => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        Log::info('Lesson progress marked as completed', [
            'user_id'    => $user->id,
            'lesson_id'  => $lesson->id,
            'course_id'  => $course->id,
            'progress_id'=> $progress->id,
            'env'        => app()->environment(),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
