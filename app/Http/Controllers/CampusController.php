<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonProgress;
use App\Models\Course;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Cursos visibles para el usuario
        if ($user->role === 'admin') {
            // Admin: ver todos los cursos activos
            $courses = Course::with(['lessons', 'recipes'])
                ->where('is_active', true)
                ->get();
        } else {
            // Alumna: sólo cursos en los que tiene inscripciones activas (no canceladas)
            $enrollments = $user->enrollments()
                ->where('status', '!=', 'cancelled')
                ->with('course.lessons', 'course.recipes')
                ->get();

            $courses = $enrollments->pluck('course')->filter();
        }

        // Determinar curso activo cuando hay varios
        $currentCourse = null;
        if ($courses->isNotEmpty()) {
            $requestedId = $request->integer('course');

            if ($requestedId) {
                // Solo permitimos seleccionar cursos a los que el usuario tiene acceso
                $currentCourse = $courses->firstWhere('id', $requestedId);
                if ($currentCourse) {
                    // Persistimos en sesión la elección del usuario
                    session(['campus.current_course_id' => $currentCourse->id]);
                }
            }

            if (! $currentCourse) {
                $savedId = (int) session('campus.current_course_id');
                if ($savedId) {
                    $currentCourse = $courses->firstWhere('id', $savedId);
                }
            }

            if (! $currentCourse) {
                $currentCourse = $courses->first();
                session(['campus.current_course_id' => $currentCourse->id]);
            }
        }

        $previewLesson = null;
        $currentCourseProgress = null;

        if ($currentCourse) {
            $previewLesson = $currentCourse->lessons()->orderBy('order')->first();

            $courseLessonIds = $currentCourse->lessons()->pluck('id');
            $completedCount = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $courseLessonIds)
                ->count();
            $totalLessons = max($courseLessonIds->count(), 1);

            $currentCourseProgress = [
                'completed' => $completedCount,
                'total'     => $totalLessons,
                'percent'   => round(($completedCount / $totalLessons) * 100),
            ];
        }

        return view('campus.index', [
            'user'                 => $user,
            'courses'              => $courses,
            'currentCourse'        => $currentCourse,
            'previewLesson'        => $previewLesson,
            'currentCourseProgress'=> $currentCourseProgress,
        ]);
    }
}
