<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->decimal('pago_con_sesion', 6, 2)->nullable()->after('descripcion');
            $table->decimal('pago_sin_sesion', 6, 2)->nullable()->after('pago_con_sesion');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['pago_con_sesion', 'pago_sin_sesion']);
        });
    }
};
