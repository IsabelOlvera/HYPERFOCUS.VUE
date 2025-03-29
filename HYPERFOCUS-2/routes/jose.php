<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WeekController;

// Esto es era una prueba
//  Route::get('/week', [WeekController::class, 'index'])->name('week');

// Rutas para la gestión de conjuntos de memoria

use App\Http\Controllers\MemoryController;

// Rutas para la gestión de conjuntos de memoria
Route::get('/memory', [MemoryController::class, 'index'])->name('memory');
Route::post('/memory', [MemoryController::class, 'store'])->name('memory.store');
Route::put('/memory/{id}', [MemoryController::class, 'update'])->name('memory.update');
Route::delete('/memory/{id}', [MemoryController::class, 'destroy'])->name('memory.destroy');
