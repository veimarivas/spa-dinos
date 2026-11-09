<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->unsignedTinyInteger('mes_correspondiente')->nullable()->after('fecha_gasto');
            $table->unsignedSmallInteger('anio_correspondiente')->nullable()->after('mes_correspondiente');
        });
    }

    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropColumn(['mes_correspondiente', 'anio_correspondiente']);
        });
    }
};
