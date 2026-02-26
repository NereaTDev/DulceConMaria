<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderByDesc('created_at')->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => ['required','string','max:255'],
            'slug'              => ['nullable','string','max:255','unique:courses,slug'],
            'short_description' => ['nullable','string'],
            'description'       => ['nullable','string'],
            'price_eur'         => ['required','numeric','min:0'],
            'currency'          => ['required','string','size:3'],
            'level'             => ['required','in:beginner,intermediate,advanced'],
            // 'is_active'      -> no lo validamos; lo interpretamos con boolean()
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        Course::create([
            'title'             => $data['title'],
            'slug'              => $data['slug'],
            'short_description' => $data['short_description'] ?? null,
            'description'       => $data['description'] ?? null,
            'price_cents'       => (int) round($data['price_eur'] * 100),
            'currency'          => $data['currency'],
            'level'             => $data['level'],
            'is_active'         => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.courses.index')->with('status', 'Curso creado correctamente');
    }

    public function edit(Course $course)
    {
        $priceEur = $course->price_cents / 100;
        return view('admin.courses.edit', compact('course','priceEur'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'             => ['required','string','max:255'],
            'slug'              => ['nullable','string','max:255','unique:courses,slug,' . $course->id],
            'short_description' => ['nullable','string'],
            'description'       => ['nullable','string'],
            'price_eur'         => ['required','numeric','min:0'],
            'currency'          => ['required','string','size:3'],
            'level'             => ['required','in:beginner,intermediate,advanced'],
            // 'is_active'      -> no lo validamos; lo interpretamos con boolean()
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $course->update([
            'title'             => $data['title'],
            'slug'              => $data['slug'],
            'short_description' => $data['short_description'] ?? null,
            'description'       => $data['description'] ?? null,
            'price_cents'       => (int) round($data['price_eur'] * 100),
            'currency'          => $data['currency'],
            'level'             => $data['level'],
            'is_active'         => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.courses.index')->with('status', 'Curso actualizado');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('status', 'Curso eliminado');
    }
}
