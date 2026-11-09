<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->enum('estado', ['programada', 'confirmada', 'realizada', 'cancelada', 'reprogramada'])
                ->default('programada')
                ->after('informe_sesion');
        });
    }

    public function down()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
