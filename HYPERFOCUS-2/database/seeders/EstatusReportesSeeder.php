<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusReportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('estatus_reportes')->insert([
        ['nombre' => 'Pendiente', 'descripcion' => 'El reporte está pendiente de revisión'],
        ['nombre' => 'En proceso', 'descripcion' => 'El reporte está siendo atendido'],
        ['nombre' => 'Finalizado', 'descripcion' => 'El reporte ha sido resuelto'],
    ]);
    }
}
