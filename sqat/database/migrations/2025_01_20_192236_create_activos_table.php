<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivosTable extends Migration
{
    public function up()
    {
        Schema::create('activos', function (Blueprint $table) {
            $table->id();
            $table->string('nro_serie', 50)->unique(); // Define el campo nroSerie como clave primaria
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->string('tipo_de_activo', 50);
            $table->enum('estado', ['ASIGNADO', 'DISPONIBLE', 'ROBADO', 'PARA BAJA', 'DONADO']);
            $table->unsignedBigInteger('usuario_de_activo')->nullable();
            $table->unsignedBigInteger('responsable_de_activo')->nullable();
            $table->integer('precio');
            $table->unsignedBigInteger('ubicacion')->nullable();
            $table->text('justificacion_doble_activo')->nullable();

            // Relacionar las claves foráneas con la tabla Persona (si es que ya tienes la tabla Persona)
            $table->foreign('usuario_de_activo')->references('id')->on('personas')->onDelete('set null');
            $table->foreign('responsable_de_activo')->references('id')->on('personas')->onDelete('set null');
            $table->foreign('ubicacion')->references('id')->on('ubicaciones')->onDelete('set null');

            $table->timestamps(); // Añadir campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('activos');
    }
}
