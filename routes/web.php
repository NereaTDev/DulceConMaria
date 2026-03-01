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
    // Página privada del curso por slug (dentro del campus)
    Route::get('/campus/curso/{slug}', [CourseController::class, 'show'])->name('campus.courses.show');

    // Vista privada de una lección
    Route::get('/campus/leccion/{lesson}', [FrontLessonController::class, 'show'])
        ->name('campus.lessons.show');

    // Marcar progreso de una lección (AJAX desde el reproductor)
    Route::post('/campus/leccion/{lesson}/progreso', [FrontLessonController::class, 'markProgress'])
        ->name('campus.lessons.progress');

    // Alias temporal para no romper enlaces antiguos a /curso/{slug}
    Route::get('/curso/{slug}', function (string $slug) {
        return redirect()->route('campus.courses.show', ['slug' => $slug]);
    })->name('courses.show');

    // Después de login, redirige al campus
    Route::get('/dashboard', function () {
        return redirect()->route('campus');
    })->name('dashboard');

    // Perfil de usuario (dentro del campus)
    Route::get('/campus/profile', [ProfileController::class, 'edit'])->name('campus.profile.edit');
    Route::patch('/campus/profile', [ProfileController::class, 'update'])->name('campus.profile.update');
    Route::delete('/campus/profile', [ProfileController::class, 'destroy'])->name('campus.profile.destroy');

    // Alias temporal para no romper enlaces antiguos a /profile
    Route::get('/profile', function () {
        return redirect()->route('campus.profile.edit');
    })->name('profile.edit');
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
        // Inscripciones: CRUD completo en admin
        Route::resource('enrollments', AdminEnrollmentController::class);
    });

require __DIR__.'/auth.php';

// Ruta temporal para ejecutar migraciones en producción (2026-02-27)
// IMPORTANTE: eliminarla cuando las migraciones estén aplicadas.
Route::get('/__run-migrations-20260227', function () {
    \Artisan::call('migrate', ['--force' => true]);
    return 'Migrations executed.<br><pre>'.e(\Artisan::output()).'</pre>';
});
