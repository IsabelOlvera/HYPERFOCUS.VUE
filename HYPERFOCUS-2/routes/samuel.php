<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WeekController;
use App\Http\Controllers\PracticaController;


// Route::get('/week', [WeekController::class, 'index'])->name('week');

Route::get('/practicar/{conjunto}', [PracticaController::class, 'index'])
    ->name('practicar.index');
Route::post('/practicar/{conjunto}', [PracticaController::class, 'store'])
    ->name('practicar.store');
Route::get('/practicas/historial', [PracticaController::class, 'historial'])
    ->name('practicar.historial');




