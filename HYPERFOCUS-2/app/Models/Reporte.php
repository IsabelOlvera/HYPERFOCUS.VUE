<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EstatusReporte; // Agrega esto

class Reporte extends Model
{
    protected $fillable = [
    'titulo',
    'descripcion',
    'archivo_adjunto',
    'fecha_generacion',
    'fecha_solucion',
    'estatus_reportes_id',
    'usuario_id',
    'asignado_a_id',
];

public function usuario() {
    return $this->belongsTo(User::class, 'usuario_id');
}

public function asignadoA() {
    return $this->belongsTo(User::class, 'asignado_a_id');
}

public function estatus() {
    return $this->belongsTo(EstatusReporte::class, 'estatus_reportes_id');
}

}
