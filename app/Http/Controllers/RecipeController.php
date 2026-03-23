<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::where('is_public', true)
            ->orderBy('title')
            ->get();

        return view('recipes.index', compact('recipes'));
    }
}
