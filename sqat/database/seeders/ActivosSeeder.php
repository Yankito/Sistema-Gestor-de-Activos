<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activos')->insert([
            ['nro_serie' => 'A12345', 'marca' => 'Dell', 'modelo' => 'Inspiron 3520', 'tipo_de_activo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 700000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12346', 'marca' => 'HP', 'modelo' => 'Pavilion x360', 'tipo_de_activo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 650000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12347', 'marca' => 'Apple', 'modelo' => 'MacBook Pro', 'tipo_de_activo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 1500000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12348', 'marca' => 'Samsung', 'modelo' => 'Galaxy Tab S8', 'tipo_de_activo' => 'TABLET', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 500000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12349', 'marca' => 'Lenovo', 'modelo' => 'ThinkPad X1', 'tipo_de_activo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 1000000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12351', 'marca' => 'Canon', 'modelo' => 'EOS R5', 'tipo_de_activo' => 'CÁMARA', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 600000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12352', 'marca' => 'Nikon', 'modelo' => 'Z7 II', 'tipo_de_activo' => 'CÁMARA', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 550000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12353', 'marca' => 'Microsoft', 'modelo' => 'Surface Pro 8', 'tipo_de_activo' => 'TABLET', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 800000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12354', 'marca' => 'Acer', 'modelo' => 'Aspire 5', 'tipo_de_activo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 450000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12355', 'marca' => 'LG', 'modelo' => 'UltraGear 32GN600', 'tipo_de_activo' => 'MONITOR', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 350000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12357', 'marca' => 'JBL', 'modelo' => 'Charge 5', 'tipo_de_activo' => 'ALTAVOZ', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 120000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12358', 'marca' => 'Seagate', 'modelo' => 'Barracuda 2TB', 'tipo_de_activo' => 'DISCO DURO', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 80000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12359', 'marca' => 'TP-Link', 'modelo' => 'Archer AX50', 'tipo_de_activo' => 'ROUTER', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 60000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12360', 'marca' => 'Asus', 'modelo' => 'ROG STRIX B550-F', 'tipo_de_activo' => 'PLACA MADRE', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 300000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12363', 'marca' => 'Xiaomi', 'modelo' => 'Redmi Note 11', 'tipo_de_activo' => 'SMARTPHONE', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 450000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nro_serie' => 'A12365', 'marca' => 'HP', 'modelo' => 'Proobook', 'tipo_de_activo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuario_de_activo' => null, 'responsable_de_activo' => null, 'precio' => 350000, 'ubicacion' => rand(1, 13), 'justificacion_doble_activo' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
