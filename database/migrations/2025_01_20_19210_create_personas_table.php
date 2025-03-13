<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            // Definir la estructura de la tabla
            $table->id();
            $table->string('user', 20)->unique();
            $table->string('rut', 15);
            $table->string('nombre_completo', 100);
            $table->string('nombre_empresa', 100);
            $table->boolean('estado_empleado')->default(true);
            $table->date('fecha_ing');
            $table->date('fecha_ter')->nullable();
            $table->string('cargo', 100);
            $table->unsignedBigInteger('ubicacion')->nullable();
            $table->string('correo', 100);
            // Definir la relación con la tabla 'ubicaciones'
            $table->foreign('ubicacion')->references('id')->on('ubicaciones')->onDelete('set null');

            // Definir las marcas de tiempo (created_at y updated_at)
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar la tabla 'personas' si existe
        Schema::dropIfExists('personas');
    }
}
