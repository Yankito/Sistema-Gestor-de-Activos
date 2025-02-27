<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignaciones extends Migration
{
    public function up()
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->foreignId('id_persona')->constrained('personas')->onDelete('cascade');
            $table->foreignId('id_activo')->constrained('activos')->onDelete('cascade');
            $table->timestamps(); // Opcional, si quieres registrar cuándo se creó/actualizó la asignación
            $table->primary(['id_persona', 'id_activo']); // Clave primaria compuesta
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignaciones');
    }
};
