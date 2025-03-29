<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WeekController;

// Esto es era una prueba
// Route::get('/week', [WeekController::class, 'index'])->name('week');

Route::post('/week/add', [WeekController::class, 'guardarActividad'])->name('week.add');
Route::post('/week/delete/{id}', [WeekController::class, 'eliminarActividad'])->name('week.delete');
Route::get('/week/actividades', [WeekController::class, 'obtenerActividadesActualizadas']);