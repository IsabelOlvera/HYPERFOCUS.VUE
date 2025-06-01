<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\EstatusReporte; // Agrega esto
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Reporte::with(['estatus']); // Asegura la relación para mostrar el nombre del estatus

    if ($request->filled('estatus_reportes_id')) {
        $query->where('estatus_reportes_id', $request->estatus_reportes_id);
    }

    if ($request->filled('usuario_id')) {
        $query->where('usuario_id', $request->usuario_id);
    }

    $reportes = $query->latest()->paginate(10)->withQueryString();
    $estatusDisponibles = EstatusReporte::all();

    return Inertia::render('Admin/Reportes/Index', [
        'reportes' => $reportes,
        'filtros' => $request->only('estatus_reportes_id', 'usuario_id'),
        'estatus_reportes' => $estatusDisponibles,
    ]);
}





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'archivo' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
    ]);

    $archivoPath = null;
    if ($request->hasFile('archivo')) {
        $archivoPath = $request->file('archivo')->store('archivos', 'public');
    }

    Reporte::create([
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'archivo_adjunto' => $archivoPath,
        'fecha_generacion' => now(),
        'fecha_solucion' => now()->addDays(5), // Fecha actual + 5 días
        'estatus_reportes_id' => 1, // ID por defecto (ej. "Pendiente")
        'estatus_reportes_id' => 1, // ID por defecto o dinámico
        'usuario_id' => auth()->id(),
        'asignado_a_id' => auth()->id(), // o asignación dinámica
    ]);

    return redirect()->route('centro-ayuda')->with('success', 'Sugerencia enviada correctamente');
}

    /**
     * Display the specified resource.
     */
    public function show(Reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporte $reporte)
    {
        //
    }

public function adminIndex(Request $request)
{
    $query = Reporte::with(['usuario', 'asignadoA', 'estatus']);

    // ✅ Filtro por estatus (estatus_reportes_id)
    if ($request->filled('estatus_reportes_id')) {
        $query->where('estatus_reportes_id', $request->estatus_reportes_id);
    }

    // ✅ Filtro por usuario
    if ($request->filled('usuario_nombre')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->usuario_nombre . '%');
            });
        }


    // 🔍 Obtener los reportes filtrados
    $reportes = $query->get()->map(function ($reporte) {
        return [
            ...$reporte->toArray(),
            'solucion' => $reporte->solucion ?? null,
        ];
    });

    // 📊 Cargar estatus disponibles para el <select>
    $estatusDisponibles = EstatusReporte::all();

    // 📈 Calcular estadísticas con base en los reportes filtrados
    $estadisticas = [
        'total' => $reportes->count(),
        'pendientes' => $reportes->where('estatus.nombre', 'Pendiente')->count(),
        'en_proceso' => $reportes->where('estatus.nombre', 'En proceso')->count(),
        'finalizados' => $reportes->where('estatus.nombre', 'Finalizado')->count(),
        'usuarios' => $reportes->pluck('usuario_id')->unique()->count()
    ];

    // 📦 Retornar los datos a la vista con filtros actuales
    return Inertia::render('Admin/reportes', [
        'reportes' => $reportes,
        'estadisticas' => $estadisticas,
        'estatus_reportes' => $estatusDisponibles,
        'filtros' => $request->only('estatus_reportes_id', 'usuario_id'), // importante para mantener estado
    ]);
}

public function agregarSolucion(Request $request, Reporte $reporte)
{
    $request->validate([
        'solucion' => 'required|string|max:1000'
    ]);

    $reporte->update([
        'solucion' => $request->solucion,
        'estatus_reportes_id' => 3 // Asume que 3 es el ID para "Finalizado"
    ]);

    return back()->with('success', 'Solución agregada correctamente');
}

public function actualizarEstatus(Request $request, Reporte $reporte)
{
    $request->validate([
        'estatus_reportes_id' => 'required|exists:estatus_reportes,id'
    ]);

    $reporte->update(['estatus_reportes_id' => $request->estatus_reportes_id]);
    $reporte->save();

    return back()->with('success', 'Estatus actualizado.');
}

}
