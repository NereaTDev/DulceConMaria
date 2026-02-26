<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('course')->orderBy('course_id')->orderBy('order')->paginate(20);
        return view('admin.lessons.index', compact('lessons'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.lessons.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required','exists:courses,id'],
            'title'     => ['required','string','max:255'],
            'order'     => ['required','integer','min:1'],
            'video_url' => ['nullable','string','max:255'],
            'summary'   => ['nullable','string'],
            // 'is_free_preview' -> no se valida como booleano porque llega como "on"
        ]);

        Lesson::create([
            'course_id'       => $data['course_id'],
            'title'           => $data['title'],
            'order'           => $data['order'],
            'video_url'       => $data['video_url'] ?? null,
            'summary'         => $data['summary'] ?? null,
            'is_free_preview' => $request->boolean('is_free_preview'),
        ]);

        return redirect()->route('admin.lessons.index')->with('status', 'Lección creada correctamente');
    }

    public function edit(Lesson $lesson)
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.lessons.edit', compact('lesson','courses'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'course_id' => ['required','exists:courses,id'],
            'title'     => ['required','string','max:255'],
            'order'     => ['required','integer','min:1'],
            'video_url' => ['nullable','string','max:255'],
            'summary'   => ['nullable','string'],
            // 'is_free_preview' -> fuera de validación estricta
        ]);

        $lesson->update([
            'course_id'       => $data['course_id'],
            'title'           => $data['title'],
            'order'           => $data['order'],
            'video_url'       => $data['video_url'] ?? null,
            'summary'         => $data['summary'] ?? null,
            'is_free_preview' => $request->boolean('is_free_preview'),
        ]);

        return redirect()->route('admin.lessons.index')->with('status', 'Lección actualizada');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('admin.lessons.index')->with('status', 'Lección eliminada');
    }
}
