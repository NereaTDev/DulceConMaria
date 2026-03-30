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
            'image'       => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'],
            'lesson_ids'  => ['array'],
            'lesson_ids.*'=> ['integer','exists:lessons,id'],
        ]);

        $ingredientsArray = collect(preg_split('/\r?\n/', $data['ingredients']))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $result = cloudinary()->upload($request->file('image')->getRealPath(), [
                'folder' => 'dulceconmaria/recipes',
            ]);
            $imageUrl = $result->getSecurePath();
        }

        $recipe = Recipe::create([
            'course_id'   => $data['course_id'] ?? null,
            'title'       => $data['title'],
            'ingredients' => $ingredientsArray,
            'description' => $data['description'],
            'image_path'  => $imageUrl,
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

        $imageUrl = $recipe->image_path;

        if ($request->hasFile('image')) {
            if ($imageUrl) $this->deleteFromCloudinary($imageUrl);
            $result = cloudinary()->upload($request->file('image')->getRealPath(), [
                'folder' => 'dulceconmaria/recipes',
            ]);
            $imageUrl = $result->getSecurePath();
        } elseif ($request->boolean('remove_image') && $imageUrl) {
            $this->deleteFromCloudinary($imageUrl);
            $imageUrl = null;
        }

        $recipe->update([
            'course_id'   => $data['course_id'] ?? null,
            'title'       => $data['title'],
            'ingredients' => $ingredientsArray,
            'description' => $data['description'],
            'image_path'  => $imageUrl,
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
            $this->deleteFromCloudinary($recipe->image_path);
        }
        $recipe->delete();
        return redirect()->route('admin.recipes.index')->with('status', 'Receta eliminada');
    }

    private function deleteFromCloudinary(string $secureUrl): void
    {
        // Extrae el public_id de la URL de Cloudinary
        // Ejemplo: https://res.cloudinary.com/cloud/image/upload/v123/dulceconmaria/recipes/abc.jpg
        // → public_id: dulceconmaria/recipes/abc
        if (preg_match('/\/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $secureUrl, $m)) {
            cloudinary()->destroy($m[1]);
        }
    }
}
