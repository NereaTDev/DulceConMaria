<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

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

        return view('lessons.show', [
            'lesson'     => $lesson,
            'course'     => $course,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
        ]);
    }
}
