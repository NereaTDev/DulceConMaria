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

        // Admin siempre tiene acceso
        if ($user && $user->role === 'admin') {
            return view('courses.show', ['course' => $course]);
        }

        // Usuarios normales: sólo con inscripción pagada
        if (! $user || ! $user->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'paid')
                ->exists()) {
            abort(403);
        }

        return view('courses.show', ['course' => $course]);
    }
}
