<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conjunto extends Model
{
    use HasFactory;
    
    protected $table = 'conjuntos';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_creacion'
    ];
}

