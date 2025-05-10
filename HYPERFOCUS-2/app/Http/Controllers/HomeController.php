<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function vistaHome()
    {
        $usuario = Auth::user(); // Usuario autenticado

        // Fechas
        $fechaconsulta = Carbon::now()->format('y/m/d');
        $fechaHoy = Carbon::now()->format('d/m/y');
        $nombreDia = Carbon::now()->locale('es')->dayName;
        $inicioS = Carbon::now()->startOfWeek()->format('y/m/d');
        $finS = Carbon::now()->endOfWeek()->format('y/m/d');
        $finS2 = Carbon::now()->yesterday()->format('y/m/d');

        // Consulta de actividades del día
        $consultaA = DB::table('actividades')
            ->select('id', 'nombre', 'completada')
            ->where('user_id', $usuario->id)
            ->where('fecha_inicio', $fechaconsulta)
            ->get();

        // Total actividades del día
        $totalActD = $consultaA->count();

        // Consulta de actividades de la semana
        $consultaActS = DB::table('actividades')
            ->whereBetween('fecha_inicio', [$inicioS, $finS])
            ->where('user_id', $usuario->id)
            ->get();
        $totalActS = $consultaActS->count() ?: 1;

        // Actividades completadas en la semana
        $consultaActRS = DB::table('actividades')
            ->whereBetween('fecha_inicio', [$inicioS, $finS2])
            ->where('user_id', $usuario->id)
            ->where('completada', 1)
            ->get();
        $totalActRS = $consultaActRS->count() ?: 1;

        return Inertia::render('Home', [
            'usuario' => $usuario, // Enviar el usuario autenticado a la vista
            'consultaA' => $consultaA,
            'totalActD' => $totalActD,
            'totalActS' => $totalActS,
            'totalActRS' => $totalActRS,
            'fechaHoy' => $fechaHoy,
            'nombreDia' => $nombreDia,
        ]);
    }

    public function guardarProgreso(Request $request)
    {
        $usuario = Auth::user();

        foreach ($request->all() as $actividadId => $completada) {
            DB::table('actividades')
                ->where('id', $actividadId)
                ->where('user_id', $usuario->id) // Asegurar que la actividad pertenece al usuario
                ->update(['completada' => $completada]);
        }

        return redirect()->back()->with('success', 'Progreso guardado correctamente');
    }
}
