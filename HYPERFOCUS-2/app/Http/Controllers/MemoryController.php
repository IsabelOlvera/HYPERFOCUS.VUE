<?php 

namespace App\Http\Controllers;  

use Inertia\Inertia; 
use Illuminate\Http\Request;
use App\Models\Conjunto;

class MemoryController extends Controller 
{     
    public function index()
{
    $conjuntos = Conjunto::all();
    
    return Inertia::render('Memory', [
        'conjuntos' => $conjuntos,
        'flash' => [
            'success' => session('success')
        ]
    ]);
}
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);
        
        Conjunto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_creacion' => now()
        ]);
        
        return redirect()->route('memory')->with('success', 'Conjunto creado correctamente');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
    ]);
    
    $conjunto = Conjunto::findOrFail($id);
    $conjunto->update([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
    ]);
    
    return redirect()->route('memory');
}
    
    public function destroy($id)
    {
        $conjunto = Conjunto::findOrFail($id);
        $conjunto->delete();
        
        return redirect()->route('memory');
    }
}
