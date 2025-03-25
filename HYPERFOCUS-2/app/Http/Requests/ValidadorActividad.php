<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validadorActividad extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|max:15|string',
            'prioridad' => 'required',
            'fechaInicio' => 'required',
            'horaInicio' => 'required',
            'duracion' => 'required||integer|min:1',
            'descripcion' => 'required',
        ];
    }
}