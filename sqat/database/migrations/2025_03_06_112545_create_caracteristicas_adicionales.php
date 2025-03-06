<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('caracteristicas_adicionales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_activo_id');
            $table->string('nombre_caracteristica');

            $table->foreign('tipo_activo_id')->references('id')->on('tipo_activo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracteristicas_adicionales');
    }
};
