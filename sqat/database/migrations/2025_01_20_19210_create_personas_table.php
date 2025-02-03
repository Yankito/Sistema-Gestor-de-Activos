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
            $table->string('rut', 15)->unique();  // 'rut' como clave primaria
            $table->string('nombre_usuario', 50);   // 'nombreUsuario' (no nulo)
            $table->string('nombres', 50);         // 'nombres' (no nulo)
            $table->string('primer_apellido', 25);  // 'primerApellido' (no nulo)
            $table->string('segundo_apellido', 25)->nullable();  // 'segundoApellido' (opcional)
            $table->string('supervisor', 60)->nullable();  // 'supervisor' (opcional)
            $table->string('empresa', 60);         // 'empresa' (no nulo)
            $table->boolean('estado_empleado')->default(true);  // 'estadoEmpleado' (no nulo)
            $table->string('centro_costo', 50);     // 'centroCosto' (no nulo)
            $table->string('denominacion', 60);    // 'denominacion' (no nulo)
            $table->string('titulo_puesto', 60);    // 'tituloPuesto' (no nulo)
            $table->date('fecha_inicio');           // 'fechaInicio' (no nulo)
            $table->boolean('usuario_ti');          // 'usuarioTI' (no nulo)
            $table->unsignedBigInteger('ubicacion')->nullable();  // 'ubicacion' (referencia a la tabla 'ubicaciones')

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
