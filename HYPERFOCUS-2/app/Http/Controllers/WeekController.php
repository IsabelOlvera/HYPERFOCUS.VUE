<?php
namespace App\Http\Controllers;

//Agregados
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\actividades;

use Inertia\Inertia;
use Illuminate\Http\Request;


class WeekController extends Controller
{
    
    public function index()
    {
        $us = Auth::id();

        $Flunes = Carbon::now()->startOfWeek()->format('y/m/d');
        $Fmartes = Carbon::now()->startOfWeek()->addDay(1)->format('Y/m/d');
        $Fmiercoles = Carbon::now()->startOfWeek()->addDay(2)->format('Y/m/d');
        $Fjueves = Carbon::now()->startOfWeek()->addDay(3)->format('Y/m/d');
        $Fviernes = Carbon::now()->startOfWeek()->addDay(4)->format('Y/m/d');
        $Fsabado = Carbon::now()->startOfWeek()->addDay(5)->format('Y/m/d');
        $Fdomingo = Carbon::now()->startOfWeek()->addDay(6)->format('Y/m/d');

        $lunes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Flunes)
        ->get();

        $martes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmartes)
        ->get();

        $miercoles = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmiercoles)
        ->get();

        $jueves = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fjueves)
        ->get();

        $viernes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fviernes)
        ->get();


        $sabado = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fsabado)
        ->get();

        $domingo = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fdomingo)
        ->get();


        // return view('miSemana', compact( 'actividades', 'fecha'));
        
        
        

        return Inertia::render('Week', [
            'usuario' => $us, 'lunes'=> $lunes, 'martes'=> $martes, 'miercoles'=> $miercoles, 'jueves'=> $jueves, 'viernes'=> $viernes, 'sabado'=> $sabado, 'domingo'=> $domingo // Pasa el usuario como prop a Vue
        ]);
    }

    public function guardarActividad(Request $request)
    {   
        
        
        $us = Auth::id();

        // Hora para creacion y para created y updated
        $fechaHoraActual = Carbon::now();

        // Manejo de hora de fin contenplando la duracion de la act
        $hora = Carbon::createFromFormat('H:i', $request->hora);
        $minutos = intval($request->duration);
        $horaConMinutos = $hora->addMinutes($minutos);


        // Obtener la fecha y hora del inicio de la nueva actividad
        $fechaInicio = $request->fechaIn;
        $horaInicio = $request->hora;

        // Consulta de la existencia de una act a la misma hora
        $actividadExistente = DB::table('actividades')
            ->join('users', 'actividades.user_id', '=', 'users.id')
            ->whereDate('fecha_inicio', '=', $fechaInicio)
            ->where('user_id', '=', $us)
            ->where('hora_inicio', '=', $horaInicio) 
            ->exists();

        // Validacion de la actividad para gener el registro
        if ($actividadExistente) {
            
            return response()->json(['success' => false]);
        } else {
                DB::table('actividades')->insert([
                    'nombre' => $request->name,
                    'descripcion' => 'descripcion',
                    'fecha_creacion' => $fechaHoraActual,
                    'duracion' => $request->duration,
                    'fecha_inicio' =>  $request->fechaIn,
                    'hora_inicio' =>  $request->hora,
                    'fecha_fin' => $fechaHoraActual,
                    'hora_fin' => $horaConMinutos,
                    'completada' => 0,
                    'user_id' => $us,
                    'pioridad_id' => $request->priority,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // $actividad = DB::table('actividades')
                // ->where('nombre', $request->name)
                // ->where('fecha_inicio', $request->fechaIn)
                // ->orderByDesc('created_at')
                // ->first();

            }

             // Usamos la sesión como fallback
            // session()->flash('ActEx', $actEx);
            // return Inertia::render('Week', [
            // ]);
             // Redirigimos a la página deseada
             return response()->json(['success' => true]);
            // return redirect()->route('week')->with('actividad', $actividad);
                // return redirect()->route('week')->with('ActEx', $actEx);
    }


    public function eliminarActividad(string $id)
    {
        DB::table('actividades')
        ->where('id', $id)
        ->delete();
    
        
        return response()->json(['success' => true]);
    }




    public function obtenerActividadesActualizadas()
{
    $us = Auth::id();

    $Flunes = Carbon::now()->startOfWeek()->format('Y/m/d');
    $Fmartes = Carbon::now()->startOfWeek()->addDay(1)->format('Y/m/d');
    $Fmiercoles = Carbon::now()->startOfWeek()->addDay(2)->format('Y/m/d');
    $Fjueves = Carbon::now()->startOfWeek()->addDay(3)->format('Y/m/d');
    $Fviernes = Carbon::now()->startOfWeek()->addDay(4)->format('Y/m/d');
    $Fsabado = Carbon::now()->startOfWeek()->addDay(5)->format('Y/m/d');
    $Fdomingo = Carbon::now()->startOfWeek()->addDay(6)->format('Y/m/d');

    $lunes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Flunes)
        ->get();

    $martes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmartes)
        ->get();

    $miercoles = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmiercoles)
        ->get();

    $jueves = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fjueves)
        ->get();

    $viernes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fviernes)
        ->get();

    $sabado = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fsabado)
        ->get();

    $domingo = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fdomingo)
        ->get();

    return response()->json([
        'lunes' => $lunes,
        'martes' => $martes,
        'miercoles' => $miercoles,
        'jueves' => $jueves,
        'viernes' => $viernes,
        'sabado' => $sabado,
        'domingo' => $domingo
    ]);
}
}

