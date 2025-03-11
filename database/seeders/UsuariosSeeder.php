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
                'correo' => 'admin@iansa.cl',
                'nombres' => 'Admin',
                'primer_apellido' => 'Admin',
                'segundo_apellido' => 'Admin',
                'contrasena' => '$2y$12$a9z60YuAC0bwIste4dHLAewPJHp5qWbdp2B9mXI91/YvbEINyV1wG',
                'es_administrador' => 1,
                'created_at' => null,
                'updated_at' => null
            ]
        ]);
    }
}
