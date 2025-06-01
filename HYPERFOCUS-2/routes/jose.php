<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeekController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CentroAyudaController;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\UsuariosController;
use Inertia\Inertia;

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



// Rutas para la gestión de conjuntos de memoria
Route::get('/memory', [MemoryController::class, 'index'])->name('memory');
Route::post('/memory', [MemoryController::class, 'store'])->name('memory.store');
Route::put('/memory/{id}', [MemoryController::class, 'update'])->name('memory.update');
Route::delete('/memory/{id}', [MemoryController::class, 'destroy'])->name('memory.destroy');

//rutas de CRM
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/centro-ayuda', [ReporteController::class, 'index'])->name('centro-ayuda');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
//rutas ADMIN DEL CRM
    Route::get('/admin/reportes', [ReporteController::class, 'adminIndex'])->name('admin.reportes');
        Route::put('reportes/{reporte}/actualizar-estatus', [ReporteController::class, 'actualizarEstatus'])
         ->name('reportes.actualizar-estatus');
});

/*Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
});*/


