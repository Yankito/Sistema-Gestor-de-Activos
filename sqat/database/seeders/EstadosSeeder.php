<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados')->insert([
            ['nombre_estado' => 'ADQUIRIDO'],
            ['nombre_estado' => 'PREPARACION'],
            ['nombre_estado' => 'DISPONIBLE'],
            ['nombre_estado' => 'ASIGNADO'],
            ['nombre_estado' => 'PERDIDO'],
            ['nombre_estado' => 'ROBADO'],
            ['nombre_estado' => 'DEVUELTO'],
            ['nombre_estado' => 'PARA BAJA'],
            ['nombre_estado' => 'DONADO'],
            ['nombre_estado' => 'VENDIDO'],
        ]);
    }
}
