<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practica;
use App\Models\Conjunto;
use App\Models\Concepto;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PracticaController extends Controller
{
    /**
     * Mostrar la vista para realizar una práctica
     */
    public function index(Conjunto $conjunto)
    {
        // Cargar el conjunto con sus conceptos
        $conjunto->load('conceptos');
        
        // Verificar si hay suficientes conceptos para hacer la práctica
        if ($conjunto->conceptos->count() < 3) {
            return redirect()->back()->with('error', 'El conjunto debe tener al menos 3 conceptos para realizar una práctica.');
        }
        
        return Inertia::render('Practicar/Index', [
            'conjunto' => $conjunto,
            'conceptos' => $conjunto->conceptos
        ]);
    }

    /**
     * Guardar los resultados de la práctica
     */
    public function store(Request $request, Conjunto $conjunto)
    {
        $request->validate([
            'fecha_hora_inicio' => 'required',
            'fecha_hora_fin' => 'required',
            'aciertos' => 'required|integer',
            'fallos' => 'required|integer',
            'intentos' => 'required|integer'
        ]);
        
        // Convertir las fechas al formato correcto usando Carbon
        $fechaInicio = Carbon::parse($request->fecha_hora_inicio)->format('Y-m-d H:i:s');
        $fechaFin = Carbon::parse($request->fecha_hora_fin)->format('Y-m-d H:i:s');
        
        // Guardar la práctica
        Practica::create([
            'fecha_hora_inicio' => $fechaInicio,
            'fecha_hora_fin' => $fechaFin,
            'aciertos' => $request->aciertos,
            'fallos' => $request->fallos,
            'intentos' => $request->intentos,
            'conjunto_id' => $conjunto->id,
            'user_id' => Auth::id()  // Usuario autenticado
        ]);
        
        return redirect()->route('practicar.index', $conjunto->id)
                         ->with('success', 'Práctica guardada exitosamente');
    }
    
    /**
     * Mostrar el historial de prácticas del usuario
     */
    public function historial()
    {
        // Obtener las prácticas del usuario ordenadas por fecha (más recientes primero)
        $practicas = Practica::with('conjunto')
                            ->where('user_id', Auth::id())
                            ->orderBy('fecha_hora_inicio', 'desc')
                            ->get();
        
        // Calcular estadísticas adicionales para cada práctica
        $practicas->each(function ($practica) {
            // Calcular duración en minutos
            $inicio = Carbon::parse($practica->fecha_hora_inicio);
            $fin = Carbon::parse($practica->fecha_hora_fin);
            $practica->duracion = $inicio->diffInMinutes($fin);
            
            // Calcular porcentaje de aciertos
            $total = $practica->aciertos + $practica->fallos;
            $practica->porcentaje = $total > 0 ? round(($practica->aciertos / $total) * 100) : 0;
        });
        
        return Inertia::render('Practicar/Historial', [
            'practicas' => $practicas
        ]);
    }
}