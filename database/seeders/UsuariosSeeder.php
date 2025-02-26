<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'correo' => 'yanko.acuna@iansa.cl',
                'nombres' => 'Yanko',
                'primer_apellido' => 'Acuna',
                'segundo_apellido' => 'Villaseca',
                'contrasena' => '$2y$12$BF714FsjRvep5VHORmfTheF2CndKFKKMzgAZ2raVmxDmvzgwMg9/C',
                'es_administrador' => 1,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 2,
                'correo' => 'usuario@iansa.cl',
                'nombres' => 'Usuario',
                'primer_apellido' => 'Usando',
                'segundo_apellido' => 'Usando',
                'contrasena' => '$2y$12$xG/i2P/hZG1MKgS6YGQiSu1gTYgbf9lQXLg5yclyPgZUIjtM.WA2C',
                'es_administrador' => 0,
                'created_at' => '2025-01-20 15:33:36',
                'updated_at' => '2025-01-20 15:33:36'
            ],
            [
                'id' => 3,
                'correo' => 'rodrigo.domnigueza@iansa.cl',
                'nombres' => 'Rodrigo',
                'primer_apellido' => 'Dominguez',
                'segundo_apellido' => 'Araya',
                'contrasena' => '$2y$12$4QjLDl3yFBZ7uDj48qIoLeVcAReRKqi7H2KIlz.XOjyW4VF0xvgZm',
                'es_administrador' => 1,
                'created_at' => '2025-01-23 19:00:29',
                'updated_at' => '2025-01-23 19:00:29'
            ],
            [
                'id' => 4,
                'correo' => 'noadmin@correo.com',
                'nombres' => 'juan',
                'primer_apellido' => 'perez',
                'segundo_apellido' => 'moco',
                'contrasena' => '$2y$12$CrSP5omvhhzebboW4G7ghe6Y8DJSAn5oAdFxcKeoohSjpJRuSr3yy',
                'es_administrador' => 0,
                'created_at' => '2025-01-23 20:15:59',
                'updated_at' => '2025-01-23 20:15:59'
            ]
        ]);
    }
}
