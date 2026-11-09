<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->foreignId('reprogramar_id')
                ->nullable()
                ->after('id')
                ->constrained('sesiones')
                ->nullOnDelete();
            // Opcional: índice para búsquedas
            $table->index('reprogramar_id');
        });
    }

    public function down()
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropForeign(['reprogramar_id']);
            $table->dropColumn('reprogramar_id');
        });
    }
};
