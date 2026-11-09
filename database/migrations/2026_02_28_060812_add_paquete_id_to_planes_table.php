<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            // Agrega la columna foreign key
            $table->foreignId('paquete_id')
                ->nullable()  // Opcional: si un plan puede existir sin paquete
                ->constrained('paquetes', 'id');
        });
    }

    public function down(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropForeign(['paquete_id']);
            $table->dropColumn('paquete_id');
        });
    }
};
