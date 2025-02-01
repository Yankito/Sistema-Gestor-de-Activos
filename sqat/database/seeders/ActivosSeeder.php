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
            ['nroSerie' => 'A12345', 'marca' => 'Dell', 'modelo' => 'Inspiron 3520', 'tipoDeActivo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 700000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12346', 'marca' => 'HP', 'modelo' => 'Pavilion x360', 'tipoDeActivo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 650000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12347', 'marca' => 'Apple', 'modelo' => 'MacBook Pro', 'tipoDeActivo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 1500000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12348', 'marca' => 'Samsung', 'modelo' => 'Galaxy Tab S8', 'tipoDeActivo' => 'TABLET', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 500000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12349', 'marca' => 'Lenovo', 'modelo' => 'ThinkPad X1', 'tipoDeActivo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 1000000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12351', 'marca' => 'Canon', 'modelo' => 'EOS R5', 'tipoDeActivo' => 'CÁMARA', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 600000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12352', 'marca' => 'Nikon', 'modelo' => 'Z7 II', 'tipoDeActivo' => 'CÁMARA', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 550000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12353', 'marca' => 'Microsoft', 'modelo' => 'Surface Pro 8', 'tipoDeActivo' => 'TABLET', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 800000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12354', 'marca' => 'Acer', 'modelo' => 'Aspire 5', 'tipoDeActivo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 450000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12355', 'marca' => 'LG', 'modelo' => 'UltraGear 32GN600', 'tipoDeActivo' => 'MONITOR', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 350000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12357', 'marca' => 'JBL', 'modelo' => 'Charge 5', 'tipoDeActivo' => 'ALTAVOZ', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 120000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12358', 'marca' => 'Seagate', 'modelo' => 'Barracuda 2TB', 'tipoDeActivo' => 'DISCO DURO', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 80000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12359', 'marca' => 'TP-Link', 'modelo' => 'Archer AX50', 'tipoDeActivo' => 'ROUTER', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 60000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12360', 'marca' => 'Asus', 'modelo' => 'ROG STRIX B550-F', 'tipoDeActivo' => 'PLACA MADRE', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 300000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12363', 'marca' => 'Xiaomi', 'modelo' => 'Redmi Note 11', 'tipoDeActivo' => 'SMARTPHONE', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 450000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nroSerie' => 'A12365', 'marca' => 'HP', 'modelo' => 'Proobook', 'tipoDeActivo' => 'LAPTOP', 'estado' => 'DISPONIBLE', 'usuarioDeActivo' => null, 'responsableDeActivo' => null, 'precio' => 350000, 'ubicacion' => rand(1, 13), 'justificacionDobleActivo' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
