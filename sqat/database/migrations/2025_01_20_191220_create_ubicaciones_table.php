<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionesTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            // Definir la estructura de la tabla
            $table->id();  // 'id' es el campo de la clave primaria
            $table->string('sitio', 255);  // 'sitio' (no nulo)
            $table->string('soporteTI', 255);  // 'soporteTI' (no nulo)
            $table->timestamps();  // Timestamps para created_at y updated_at
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar la tabla 'ubicaciones' si existe
        Schema::dropIfExists('ubicaciones');
    }
}
