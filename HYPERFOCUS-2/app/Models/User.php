<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Practica;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // Indicamos que la tabla es 'usuarios'
    
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'foto_perfil',
        'edad',
        'rol_id',
        'estatus',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email' => 'string',
        'estatus' => 'boolean',
    ];


    public function practicas()
{
    return $this->hasMany(Practica::class);
}

}
