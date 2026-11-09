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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suscripcione_id');
            $table->unsignedBigInteger('servicio_id');
            $table->unsignedBigInteger('hijo_id');
            $table->unsignedBigInteger('trabajadore_id');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('n_sesion');
            $table->text('informe_sesion')->nullable();
            $table->timestamps();

            $table->foreign('suscripcione_id')->references('id')->on('suscripciones');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->foreign('hijo_id')->references('id')->on('hijos');
            $table->foreign('trabajadore_id')->references('id')->on('trabajadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};
