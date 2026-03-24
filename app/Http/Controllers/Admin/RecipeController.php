<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image'       => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'],
            'lesson_ids'  => ['array'],
            'lesson_ids.*'=> ['integer','exists:lessons,id'],
        ]);

        $ingredientsArray = collect(preg_split('/\r?\n/', $data['ingredients']))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('recipes', 'public')
            : null;

        $recipe = Recipe::create([
            'course_id'   => $data['course_id'] ?? null,
            'title'       => $data['title'],
            'ingredients' => $ingredientsArray,
            'description' => $data['description'],
            'image_path'  => $imagePath,
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
            'image'       => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'],
            'remove_image'=> ['nullable','boolean'],
            'lesson_ids'  => ['array'],
            'lesson_ids.*'=> ['integer','exists:lessons,id'],
        ]);

        $ingredientsArray = collect(preg_split('/\r?\n/', $data['ingredients']))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $imagePath = $recipe->image_path;

        if ($request->hasFile('image')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('recipes', 'public');
        } elseif ($request->boolean('remove_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        $recipe->update([
            'course_id'   => $data['course_id'] ?? null,
            'title'       => $data['title'],
            'ingredients' => $ingredientsArray,
            'description' => $data['description'],
            'image_path'  => $imagePath,
            'is_public'   => $request->boolean('is_public'),
        ]);

        $recipe->lessons()->sync($data['lesson_ids'] ?? []);

        return redirect()->route('admin.recipes.index')->with('status', 'Receta actualizada');
    }

    public function togglePublic(Recipe $recipe)
    {
        $recipe->update(['is_public' => ! $recipe->is_public]);
        $label = $recipe->is_public ? 'visible en el recetario' : 'oculta del recetario';
        return back()->with('status', "«{$recipe->title}» ahora está {$label}.");
    }

    public function destroy(Recipe $recipe)
    {
        if ($recipe->image_path) {
            Storage::disk('public')->delete($recipe->image_path);
        }
        $recipe->delete();
        return redirect()->route('admin.recipes.index')->with('status', 'Receta eliminada');
    }
}
