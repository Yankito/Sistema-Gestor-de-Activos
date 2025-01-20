<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivosTable extends Migration
{
    public function up()
    {
        Schema::create('activos', function (Blueprint $table) {
            $table->string('nroSerie', 50)->primary(); // Define el campo nroSerie como clave primaria
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->enum('estado', ['ASIGNADO', 'DISPONIBLE', 'ROBADO', 'PARA BAJA', 'DONADO']);
            $table->string('usuarioDeActivo', 15)->nullable();
            $table->string('responsableDeActivo', 15)->nullable();
            $table->boolean('docking');
            $table->boolean('parlanteJabra');
            $table->boolean('discoDuroExt');
            $table->boolean('impresoraExclusiva');
            $table->boolean('monitor');
            $table->boolean('mouse');
            $table->boolean('teclado');
            $table->text('justificacionDobleActivo')->nullable();
            $table->integer('precio');

            // Relacionar las claves foráneas con la tabla Persona (si es que ya tienes la tabla Persona)
            $table->foreign('usuarioDeActivo')->references('rut')->on('persona')->onDelete('set null');
            $table->foreign('responsableDeActivo')->references('rut')->on('persona')->onDelete('set null');

            $table->timestamps(); // Añadir campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('activos');
    }
}
