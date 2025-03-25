<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidadorEditAct extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'sometimes|required|date',
            'completada' => 'sometimes|required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la actividad es obligatorio.',
            'fecha_inicio.required' => 'Debes especificar una fecha válida.',
            'completada.boolean' => 'El estado de completado debe ser verdadero o falso.',
        ];
    }
}

