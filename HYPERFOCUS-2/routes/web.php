<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

use App\Http\Controllers\WeekController;
Route::get('/week', [WeekController::class, 'index'])->name('week');

use App\Http\Controllers\FocusController;
Route::get('/focus', [FocusController::class, 'index'])->name('focus');

use App\Http\Controllers\MemoryController;
Route::get('/memory', [MemoryController::class, 'index'])->name('memory');

use App\Http\Controllers\AchievementsController;
Route::get('/achievements', [AchievementsController::class, 'index'])->name('achievements');

// Ruta para la página de Planes
Route::get('/planes', function () {
    return Inertia::render('Planes');
})->name('planes');

use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'vistaHome'])->name('rutahome');
Route::post('/guardar-progreso', [HomeController::class, 'guardarProgreso'])->name('guardarProgreso');

Route::get('/preguntas', function () {
    return Inertia::render('Preguntas');
})->name('preguntas');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

