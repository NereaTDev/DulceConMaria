<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\LessonController as FrontLessonController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;

// Endpoint temporal para lanzar migraciones en producción
// PROTEGER con un secreto en la URL y BORRAR después de usar.
Route::get('/__setup-migrate-9f3b7c2d', function () {
    Artisan::call('migrate', ['--force' => true]);
    Artisan::call('db:seed', ['--force' => true]);
    return 'Migrations and seeders executed.';
});

// Home pública
Route::get('/', function () {
    return view('welcome');
});

// Recetario pública
Route::get('/recetario', [RecipeController::class, 'index'])->name('recipes.index');

// Campus privado (área de alumno)
Route::get('/campus', [CampusController::class, 'index'])
    ->middleware(['auth'])
    ->name('campus');

// Rutas que requieren login
Route::middleware('auth')->group(function () {
    // Página privada del curso por slug
    Route::get('/curso/{slug}', [CourseController::class, 'show'])->name('courses.show');

    // Vista privada de una lección
    Route::get('/campus/leccion/{lesson}', [FrontLessonController::class, 'show'])
        ->name('campus.lessons.show');

    // Después de login, redirige al campus
    Route::get('/dashboard', function () {
        return redirect()->route('campus');
    })->name('dashboard');

    // Perfil de usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel de administración protegido
Route::prefix('admin')
    ->middleware(['auth','admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('courses', AdminCourseController::class);
        Route::resource('lessons', AdminLessonController::class)->except(['show']);
        Route::resource('recipes', AdminRecipeController::class)->except(['show']);
        Route::resource('users', AdminUserController::class); // index, create, store, show, edit, update, destroy
        Route::resource('enrollments', AdminEnrollmentController::class)->only(['index','show','update','store']);
    });

require __DIR__.'/auth.php';
