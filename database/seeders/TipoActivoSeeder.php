<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TipoActivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiposActivos = [
            ['nombre' => 'LAPTOP'],
            ['nombre' => 'DESKTOP'],
        ];

        DB::table('tipo_activo')->insert($tiposActivos);
    }
}
