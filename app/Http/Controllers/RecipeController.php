<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        // Receta destacada (la más reciente marcada como pública) para el recetario público
        $recipe = Recipe::where('is_public', true)
            ->orderByDesc('created_at')
            ->first();

        return view('recipes.index', [
            'recipe' => $recipe,
        ]);
    }
}
