<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $course = Course::with(['lessons', 'recipes'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user();

        $hasAccess = false;

        // Admin siempre tiene acceso completo al curso
        if ($user && $user->role === 'admin') {
            $hasAccess = true;
        } elseif ($user) {
            // Usuarios normales: acceso sólo con inscripción pagada
            $hasAccess = $user->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'paid')
                ->exists();
        }

        return view('courses.show', [
            'course'    => $course,
            'hasAccess' => $hasAccess,
        ]);
    }
}
