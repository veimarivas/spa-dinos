<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sesiones', function (Blueprint $table) {
            // 1. Eliminar la foreign key antigua (si existe)
            if (Schema::hasColumn('sesiones', 'servicio_id')) {
                $table->dropForeign(['servicio_id']);
            }
        });

        Schema::table('sesiones', function (Blueprint $table) {
            // 2. Eliminar columna servicio_id
            if (Schema::hasColumn('sesiones', 'servicio_id')) {
                $table->dropColumn('servicio_id');
            }
        });

        Schema::table('sesiones', function (Blueprint $table) {
            // 3. Agregar nueva columna como NULLABLE primero (evita error de FK)
            $table->foreignId('planes_servicio_id')
                ->nullable()
                ->after('suscripcione_id'); // ajusta la posición si lo necesitas
        });

        // 4. Agregar la foreign key por separado (con columna ya existente)
        Schema::table('sesiones', function (Blueprint $table) {
            $table->foreign('planes_servicio_id')
                ->references('id')
                ->on('planes_servicios')
                ->onDelete('cascade');
        });

        // 5. [OPCIONAL] Si quieres que sea NOT NULL después, descomenta:
        /*
        DB::statement('UPDATE sesiones SET planes_servicio_id = 1 WHERE planes_servicio_id IS NULL'); // valor por defecto válido
        Schema::table('sesiones', function (Blueprint $table) {
            $table->foreignId('planes_servicio_id')->nullable(false)->change();
        });
        */
    }

    public function down(): void
    {
        Schema::table('sesiones', function (Blueprint $table) {
            // Eliminar foreign key nueva
            $table->dropForeign(['planes_servicio_id']);
        });

        Schema::table('sesiones', function (Blueprint $table) {
            // Eliminar columna nueva
            $table->dropColumn('planes_servicio_id');

            // Restaurar columna antigua
            $table->foreignId('servicio_id')
                ->nullable()
                ->after('suscripcione_id');
        });

        Schema::table('sesiones', function (Blueprint $table) {
            // Restaurar foreign key antigua
            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicios')
                ->onDelete('cascade');
        });
    }
};
