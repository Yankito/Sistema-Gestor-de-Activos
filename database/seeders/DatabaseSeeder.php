<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UbicacionesSeeder::class);
        $this->call(EstadosSeeder::class);
        $this->call(UsuariosSeeder::class);
        $this->call(TipoActivoSeeder::class);
    }
}
