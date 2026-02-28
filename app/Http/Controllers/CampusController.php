<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        if ($currentCourse) {
            $previewLesson = $currentCourse->lessons()->orderBy('order')->first();
        }

        return view('campus.index', [
            'user'          => $user,
            'courses'       => $courses,
            'currentCourse' => $currentCourse,
            'previewLesson' => $previewLesson,
        ]);
    }
}
