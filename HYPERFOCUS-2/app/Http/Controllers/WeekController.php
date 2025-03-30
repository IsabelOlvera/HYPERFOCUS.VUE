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
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Flunes)
        ->get();

        $martes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmartes)
        ->get();

        $miercoles = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmiercoles)
        ->get();

        $jueves = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fjueves)
        ->get();

        $viernes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fviernes)
        ->get();


        $sabado = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fsabado)
        ->get();

        $domingo = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
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
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Flunes)
        ->get();

    $martes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmartes)
        ->get();

    $miercoles = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fmiercoles)
        ->get();

    $jueves = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fjueves)
        ->get();

    $viernes = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fviernes)
        ->get();

    $sabado = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
        ->where('user_id', '=', $us)
        ->whereDate('fecha_inicio', '=', $Fsabado)
        ->get();

    $domingo = DB::table('actividades')
        ->join('users', 'actividades.user_id', '=', 'users.id')
        ->select('actividades.nombre as act','actividades.id', 'completada')
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

// public function updateStatus(Request $request, $id)
// {
//     try {
//         DB::table('actividades')
//                     ->where('id', $id)
//                     ->update(['completada' => 1]);
                    

//         return response()->json(['success' => true]);
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
//     }
// }

public function updateStatus(Request $request, $id)
{


    try {
        $updated = DB::table('actividades')
                    ->where('id', '=', '97')
                    ->update(['completada' => 1]);

        if ($updated) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'No se encontró la actividad'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

public function completarActividades(Request $request)
{
    
    try {
        DB::table('actividades')
        ->whereIn('id', $request->ids)
        ->update(['completada' => 1]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
}

public function descompletarActividades(Request $request)
{
    
    // try {
    //     DB::table('actividades')
    //     ->whereIn('id', $request->ids)
    //     ->update(['completada' => 0]);

    //     return response()->json(['success' => true]);
    // } catch (\Exception $e) {
    //     return response()->json(['success' => false, 'error' => $e->getMessage()]);
    // }

    DB::table('actividades')
        ->whereIn('id', $request->ids)
        ->update(['completada' => 0]);

        return response()->json(['success' => true]);
}


  // Método para actualizar el estado de la actividad
  public function actualizarEstadoActividad($id, Request $request)
  {
      // Validar la entrada
      $request->validate([
          'completada' => 'required',  // Asegúrate de que el valor es un booleano
      ]);

      try {
        //   // Buscar la actividad por su ID
        //   $actividad = Actividad::findOrFail($id);

        //   // Actualizar el estado de la actividad
        //   $actividad->completada = $request->completada;
        //   $actividad->save();

          if ($request->completada == 1) {
                DB::table('actividades')
                    ->where('id', $id)
                    ->update(['completada' => 1]);

                // array_push($validacion, $nombre.' 1');
            }elseif ($request->completada == 0) {
                DB::table('actividades')
                    ->where('id', $id)
                    ->update(['completada' => 0]);

            // array_push($validacion, $nombre.' 0');
        }

          // Devolver una respuesta de éxito
          return response()->json([
              'success' => true,
              'message' => 'Estado de la actividad actualizado con éxito.'
          ]);
      } catch (\Exception $e) {
          // Manejar el error si la actividad no existe o si ocurre algún problema
          return response()->json([
              'success' => false,
              'message' => 'Error al actualizar el estado de la actividad.'
          ], 500);
      }
  }
}


