<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('course')->orderByDesc('created_at')->paginate(20);
        return view('admin.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        $lessons = Lesson::with('course')->orderBy('title')->get();
        return view('admin.recipes.create', compact('courses','lessons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'   => ['nullable','exists:courses,id'],
            'title'       => ['required','string','max:255'],
            'ingredients' => ['required','string'],
            'description' => ['required','string'],
            'lesson_ids'  => ['array'],
            'lesson_ids.*'=> ['integer','exists:lessons,id'],
        ]);

        $ingredientsArray = collect(preg_split('/\r?\n/', $data['ingredients']))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $recipe = Recipe::create([
            'course_id'   => $data['course_id'] ?? null,
            'title'       => $data['title'],
            'ingredients' => $ingredientsArray,
            'description' => $data['description'],
            'is_public'   => $request->boolean('is_public'),
        ]);

        $recipe->lessons()->sync($data['lesson_ids'] ?? []);

        return redirect()->route('admin.recipes.index')->with('status', 'Receta creada correctamente');
    }

    public function edit(Recipe $recipe)
    {
        $courses = Course::orderBy('title')->get();
        $lessons = Lesson::with('course')->orderBy('title')->get();
        $ingredientsText = implode("\n", $recipe->ingredients ?? []);

        return view('admin.recipes.edit', compact('recipe','courses','lessons','ingredientsText'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $data = $request->validate([
            'course_id'   => ['nullable','exists:courses,id'],
            'title'       => ['required','string','max:255'],
            'ingredients' => ['required','string'],
            'description' => ['required','string'],
            'lesson_ids'  => ['array'],
            'lesson_ids.*'=> ['integer','exists:lessons,id'],
        ]);

        $ingredientsArray = collect(preg_split('/\r?\n/', $data['ingredients']))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $recipe->update([
            'course_id'   => $data['course_id'] ?? null,
            'title'       => $data['title'],
            'ingredients' => $ingredientsArray,
            'description' => $data['description'],
            'is_public'   => $request->boolean('is_public'),
        ]);

        $recipe->lessons()->sync($data['lesson_ids'] ?? []);

        return redirect()->route('admin.recipes.index')->with('status', 'Receta actualizada');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('admin.recipes.index')->with('status', 'Receta eliminada');
    }
}
