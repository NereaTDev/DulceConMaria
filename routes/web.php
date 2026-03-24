<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LessonController as FrontLessonController;
use App\Http\Controllers\OnboardingController;
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

// Dossier del curso
Route::view('/dossier', 'dossier')->name('dossier.show');

// Página de contacto
Route::get('/contacto', [ContactController::class, 'show'])->name('contact.show');
// Protegemos el formulario con un throttle suave para evitar abusos automatizados
Route::post('/contacto', [ContactController::class, 'store'])
    ->middleware('throttle:5,1') // máx. 5 envíos por minuto desde la misma IP
    ->name('contact.store');

// Páginas legales
Route::view('/privacidad', 'legal.privacy')->name('privacy');
Route::view('/aviso-legal', 'legal.legal')->name('legal');
Route::view('/cookies', 'legal.cookies')->name('cookies');

// Recetario pública
Route::get('/recetario', [RecipeController::class, 'index'])->name('recipes.index');

// Campus privado (área de alumno)
Route::get('/campus', [CampusController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('campus');

// Rutas que requieren login y email verificado
Route::middleware(['auth', 'verified'])->group(function () {
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
        Route::patch('recipes/{recipe}/toggle-public', [AdminRecipeController::class, 'togglePublic'])->name('recipes.toggle-public');
        Route::resource('users', AdminUserController::class); // index, create, store, show, edit, update, destroy
        // Inscripciones: CRUD completo en admin
        Route::resource('enrollments', AdminEnrollmentController::class);
    });

// Onboarding del campus: marcar como completado u omitido
Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])
    ->middleware(['auth', 'verified'])
    ->name('onboarding.complete');

require __DIR__.'/auth.php';

