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
            $table->string('nro_serie', 100)->unique(); // Define el campo nroSerie como clave primaria
            $table->string('marca', 100);
            $table->string('modelo', 100);
            $table->unsignedBigInteger('estado');
            $table->unsignedBigInteger('responsable_de_activo')->nullable();
            $table->integer('precio')->nullable();
            $table->unsignedBigInteger('tipo_de_activo');
            $table->unsignedBigInteger('ubicacion')->nullable();
            $table->text('justificacion_doble_activo')->nullable();

            // Relacionar las claves foráneas con la tabla Persona (si es que ya tienes la tabla Persona)
            $table->foreign('responsable_de_activo')->references('id')->on('personas')->onDelete('set null');
            $table->foreign('ubicacion')->references('id')->on('ubicaciones')->onDelete('set null');
            $table->foreign('estado')->references('id')->on('estados')->onDelete('cascade');
            $table->foreign('tipo_de_activo')->references('id')->on('tipo_activo')->onDelete('cascade');

            $table->timestamps(); // Añadir campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('activos');
    }
}
