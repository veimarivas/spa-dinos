<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->enum('tarifa', ['normal', 'especial'])
                ->default('normal')
                ->after('estado');
            $table->decimal('monto_total', 10, 2)->nullable()->after('tarifa');
        });
    }

    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropColumn(['tarifa', 'monto_total']);
        });
    }
};
