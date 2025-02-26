<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionesSeeder extends Seeder
{
    public function run()
    {
        DB::table('ubicaciones')->insert([
            ['sitio' => 'CHILLAN', 'soporte_ti' => 'BENJAMIN AVILA', 'latitud' => -36.517633, 'longitud' => -72.056876],
            ['sitio' => 'COSMITO', 'soporte_ti' => 'ESTEBAN ITURRA', 'latitud' => -36.775020, 'longitud' => -73.018704],
            ['sitio' => 'LINARES', 'soporte_ti' => 'CRISTIAN URBINA', 'latitud' => -35.839558, 'longitud' => -71.602430],
            ['sitio' => 'LOS ANGELES', 'soporte_ti' => 'ESTEBAN ITURRA', 'latitud' => -37.484550, 'longitud' => -72.365131],
            ['sitio' => 'MOLINA', 'soporte_ti' => 'PATRICIO GONZALEZ', 'latitud' => -35.094153, 'longitud' => -71.316382],
            ['sitio' => 'OSORNO', 'soporte_ti' => 'ESTEBAN ITURRA', 'latitud' => -40.581797, 'longitud' => -73.122334],
            ['sitio' => 'PAINE', 'soporte_ti' => 'CRISTIAN URBINA', 'latitud' => -33.896404, 'longitud' => -70.732758],
            ['sitio' => 'PARRAL', 'soporte_ti' => 'CRISTIAN URBINA', 'latitud' => -36.136087, 'longitud' => -71.810023],
            ['sitio' => 'QUEPE', 'soporte_ti' => 'ESTEBAN ITURRA', 'latitud' => -38.852556, 'longitud' => -72.621369],
            ['sitio' => 'QUILICURA', 'soporte_ti' => 'CRISTIAN URBINA', 'latitud' => -33.374775, 'longitud' => -70.727324],
            ['sitio' => 'RAPACO', 'soporte_ti' => 'ESTEBAN ITURRA', 'latitud' => -40.250542, 'longitud' => -73.012832],
            ['sitio' => 'ROSARIO NORTE', 'soporte_ti' => 'CRISTIAN URBINA', 'latitud' => -33.404088, 'longitud' => -70.572684],
            ['sitio' => 'SAN FERNANDO', 'soporte_ti' => 'PATRICIO GONZALEZ', 'latitud' => -34.570463, 'longitud' => -70.972826],
            ['sitio' => 'CONCEPCION', 'soporte_ti' => 'ESTEBAN ITURRA', 'latitud' => -36.77141, 'longitud' => -73.01976],
            ['sitio' => 'NOVICIADO', 'soporte_ti' => 'CRISTIAN URBINA', 'latitud' => -33.41313, 'longitud' => -70.84082],   
        ]);
    }
}
