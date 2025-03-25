<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'nombre' => 'usuario',
                'descripcion' => 'Usuario básico',
                'privilegios' => 'Usos limitados',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nombre' => 'usuariopremium',
                'descripcion' => 'Usuario de pago, acceso total como usuario',
                'privilegios' => 'Uso total',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nombre' => 'administrador',
                'descripcion' => 'Verifica el funcionamiento',
                'privilegios' => 'Acceso total al sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
