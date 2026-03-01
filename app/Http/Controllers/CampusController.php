<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonProgress;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Todos los cursos en los que el usuario tiene inscripciones activas (no canceladas)
        $enrollments = $user->enrollments()
            ->where('status', '!=', 'cancelled')
            ->with('course.lessons', 'course.recipes')
            ->get();

        $courses = $enrollments->pluck('course')->filter();
        $currentCourse = $courses->first();
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
