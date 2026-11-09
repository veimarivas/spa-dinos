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
        Schema::create('planes_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plane_id');
            $table->unsignedBigInteger('servicio_id');
            $table->integer('cantidad_sesiones');

            $table->timestamps();

            $table->foreign('plane_id')->references('id')->on('planes');
            $table->foreign('servicio_id')->references('id')->on('servicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_servicios');
    }
};
