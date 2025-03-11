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
            ['nombre_estado' => 'ADQUIRIDO', 'descripcion' => 'Estado cuando el activo ha sido adquirido por primera vez.'],
            ['nombre_estado' => 'PREPARACION', 'descripcion' => 'Estado cuando el activo está en preparación y configuración inicial.'],
            ['nombre_estado' => 'DISPONIBLE', 'descripcion' => 'Estado cuando el activo está disponible uso de una persona.'],
            ['nombre_estado' => 'ASIGNADO', 'descripcion' => 'Estado cuando el activo ha sido asignado a una persona como responsable.'],
            ['nombre_estado' => 'PERDIDO', 'descripcion' => 'Estado cuando el activo se encuentra perdido.'],
            ['nombre_estado' => 'ROBADO', 'descripcion' => 'Estado cuando el activo ha sido robado.'],
            ['nombre_estado' => 'DEVUELTO', 'descripcion' => 'Estado cuando el activo ha sido devuelto a mesa de ayuda.'],
            ['nombre_estado' => 'PARA BAJA', 'descripcion' => 'Estado cuando el activo está para baja.'],
            ['nombre_estado' => 'DONADO', 'descripcion' => 'Estado cuando el activo ha sido donado.'],
            ['nombre_estado' => 'VENDIDO', 'descripcion' => 'Estado cuando el activo ha sido vendido.'],
        ]);
    }
}
