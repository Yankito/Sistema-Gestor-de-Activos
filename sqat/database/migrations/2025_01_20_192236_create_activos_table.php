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
            $table->string('nroSerie', 50)->unique(); // Define el campo nroSerie como clave primaria
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->string('tipoDeActivo', 50);
            $table->enum('estado', ['ASIGNADO', 'DISPONIBLE', 'ROBADO', 'PARA BAJA', 'DONADO']);
            $table->unsignedBigInteger('usuarioDeActivo')->nullable();
            $table->unsignedBigInteger('responsableDeActivo')->nullable();
            $table->integer('precio');
            $table->unsignedBigInteger('ubicacion')->nullable();
            $table->text('justificacionDobleActivo')->nullable();

            // Relacionar las claves foráneas con la tabla Persona (si es que ya tienes la tabla Persona)
            $table->foreign('usuarioDeActivo')->references('id')->on('personas')->onDelete('set null');
            $table->foreign('responsableDeActivo')->references('id')->on('personas')->onDelete('set null');
            $table->foreign('ubicacion')->references('id')->on('ubicaciones')->onDelete('set null');

            $table->timestamps(); // Añadir campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('activos');
    }
}
