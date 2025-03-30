<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WeekController;
// Importa el controlador al inicio del archivo (si no lo has hecho)
use App\Http\Controllers\ConceptoController;

// Luego añade estas rutas
Route::get('/memory/{conjunto_id}/conceptos', [ConceptoController::class, 'index'])->name('conceptos.index');
Route::post('/memory/{conjunto_id}/conceptos', [ConceptoController::class, 'store'])->name('conceptos.store');
Route::put('/memory/{conjunto_id}/conceptos/{id}', [ConceptoController::class, 'update'])->name('conceptos.update');
Route::delete('/memory/{conjunto_id}/conceptos/{id}', [ConceptoController::class, 'destroy'])->name('conceptos.destroy');
// Esto es era una prueba
//  Route::get('/week', [WeekController::class, 'index'])->name('week');

// Rutas para la gestión de conjuntos de memoria

use App\Http\Controllers\MemoryController;

// Rutas para la gestión de conjuntos de memoria
Route::get('/memory', [MemoryController::class, 'index'])->name('memory');
Route::post('/memory', [MemoryController::class, 'store'])->name('memory.store');
Route::put('/memory/{id}', [MemoryController::class, 'update'])->name('memory.update');
Route::delete('/memory/{id}', [MemoryController::class, 'destroy'])->name('memory.destroy');
