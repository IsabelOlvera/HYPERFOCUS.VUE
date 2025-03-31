<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practica extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'aciertos',
        'fallos',
        'intentos',
        'conjunto_id',
        'user_id'
    ];

    // Relación con Conjunto
    public function conjunto()
    {
        return $this->belongsTo(Conjunto::class);
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
