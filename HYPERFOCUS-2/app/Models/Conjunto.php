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
    
    public function conceptos()
    {
        return $this->hasMany(Concepto::class);
    }

    public function practicas()
    {
    return $this->hasMany(Practica::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    
}