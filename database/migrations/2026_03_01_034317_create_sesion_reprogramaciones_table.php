<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sesion_reprogramaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained('sesiones')->onDelete('cascade');
            $table->date('fecha_anterior');
            $table->time('hora_inicio_anterior');
            $table->time('hora_fin_anterior');
            $table->date('fecha_nueva');
            $table->time('hora_inicio_nueva');
            $table->time('hora_fin_nueva');
            $table->string('motivo')->nullable();
            $table->foreignId('reprogramado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesion_reprogramaciones');
    }
};
