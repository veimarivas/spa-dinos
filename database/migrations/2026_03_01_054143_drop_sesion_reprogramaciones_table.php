<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sesion_reprogramaciones');
    }

    public function down()
    {
        Schema::create('sesion_reprogramaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained()->onDelete('cascade');
            $table->date('fecha_anterior');
            $table->string('hora_inicio_anterior', 5);
            $table->string('hora_fin_anterior', 5);
            $table->date('fecha_nueva');
            $table->string('hora_inicio_nueva', 5);
            $table->string('hora_fin_nueva', 5);
            $table->string('motivo')->nullable();
            $table->foreignId('reprogramado_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
};
