<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->unsignedBigInteger('pago_salario_id')->nullable()->after('estado');
            $table->foreign('pago_salario_id')->references('id')->on('gastos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropForeign(['pago_salario_id']);
            $table->dropColumn('pago_salario_id');
        });
    }
};
