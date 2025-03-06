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
        Schema::create('valores_adicionales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_activo');
            $table->unsignedBigInteger('id_caracteristica');
            $table->string('valor');

            $table->foreign('id_activo')->references('id')->on('activos')->onDelete('cascade');
            $table->foreign('id_caracteristica')->references('id')->on('caracteristicas_adicionales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valores_adicionales');
    }
};
