<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concepto;
use App\Models\Conjunto;
use Inertia\Inertia;

class ConceptoController extends Controller
{
    public function index($conjunto_id)
    {
        $conjunto = Conjunto::with('conceptos')->findOrFail($conjunto_id);
        
        return Inertia::render('Conceptos/Index', [
            'conjunto' => $conjunto,
            'conceptos' => $conjunto->conceptos,
            'flash' => [
                'success' => session('success')
            ]
        ]);
    }
    
    public function store(Request $request, $conjunto_id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'definicion' => 'required|string',
        ]);
        
        Concepto::create([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
            'conjunto_id' => $conjunto_id
        ]);
        
        return redirect()->route('conceptos.index', $conjunto_id)
            ->with('success', 'Concepto creado correctamente');
    }
    
    public function update(Request $request, $conjunto_id, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'definicion' => 'required|string',
        ]);
        
        $concepto = Concepto::findOrFail($id);
        $concepto->update([
            'nombre' => $request->nombre,
            'definicion' => $request->definicion,
        ]);
        
        return redirect()->route('conceptos.index', $conjunto_id);
    }
    
    public function destroy($conjunto_id, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->delete();
        
        return redirect()->route('conceptos.index', $conjunto_id);
    }
}