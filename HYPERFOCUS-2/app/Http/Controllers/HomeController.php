<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function vistaHome(){
        // Usuario (falta consulta)
        $us = 2;
        
        //Obtención fecha con formato
        $fechaconsulta = Carbon::now()->format('y/m/d');
        $fechaHoy = Carbon::now()->format('d/m/y');

        //Obtención de nombre del día
        $nombreDia = Carbon::now()->locale('es')->dayName;

        //Obtencion de fechas para semana
        $inicioS = Carbon::now()->startOfWeek()->format('y/m/d');
        $finS = Carbon::now()->endOfWeek()->format('y/m/d');
        $finS2 = Carbon::now()->yesterday()->format('y/m/d');

        // Consulta de la informacion para llenar la vista home
        $consultaUSER = DB::table('users')
            ->select('name')
            ->where('id', '=', $us)
            ->get();


        $consultaA = DB::table('actividades')
            ->join('users', 'actividades.user_id', '=', 'users.id')
            ->select( 'actividades.nombre', 'actividades.completada', 'actividades.id')
            ->where('user_id', '=', $us)
            ->where('fecha_inicio','=', $fechaconsulta)
            ->get();

        // Numero de actividades segun la cosulta
        $totalActD = count($consultaA);

        //Numero de actividades a la semana 
        $consultaActS = DB::table('actividades')
            ->whereBetween('fecha_inicio', [$inicioS, $finS])
            ->where('user_id','=', $us)
            ->get();

            if ($consultaActS->isEmpty()) {
                $totalActS = 1;
            }else{
                $totalActS = count($consultaActS);
            }

        
        

        // Numero de actividades realizadas
        $consultaActRS = DB::table('actividades')
            ->whereBetween('fecha_inicio', [$inicioS, $finS2])
            ->where('user_id','=', $us)
            ->where('completada','=', '1')
            ->get();

            if ($consultaActRS->isEmpty() AND $consultaActS->isEmpty()) {
                $totalActRS = 1;
            }else{
                $totalActRS = count($consultaActRS);
            }
        
        

            return Inertia::render('Home', [
                'totalActD' => $totalActD,
                'consultaA' => $consultaA,
                'consultaUSER' => $consultaUSER,
                'totalActS' => $totalActS,
                'totalActRS' => $totalActRS,
                'fechaHoy' => $fechaHoy,
                'nombreDia' => $nombreDia
            ]);
    }




    
    public function guardarProgreso( Request  $request){
        //Obtencion de las actividades realizadas/no realizadas
        $valores = $request->all();
        // $validacion = [];

        foreach ($valores as $nombre => $valor) {
            if ($valor == 1) {
                DB::table('actividades')
                    ->where('id', $nombre)
                    ->update(['completada' => 1]);

                // array_push($validacion, $nombre.' 1');
            }elseif ($valor == 0) {
                DB::table('actividades')
                    ->where('id', $nombre)
                    ->update(['completada' => 0]);

                // array_push($validacion, $nombre.' 0');
            }
        }
        
        
        session()->flash('progresoGC');

        return redirect()->route('rutahome');
    }
}
