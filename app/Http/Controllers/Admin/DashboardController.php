<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Recipe;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_courses'   => Course::where('is_active', true)->count(),
            'users'            => User::count(),
            'paid_enrollments' => Enrollment::where('status', 'paid')->count(),
            'recipes'          => Recipe::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
