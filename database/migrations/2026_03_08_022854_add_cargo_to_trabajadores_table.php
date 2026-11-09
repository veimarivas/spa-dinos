<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_cargo_id_to_trabajadores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->foreignId('cargo_id')->after('persona_id')->nullable()->constrained('cargos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropColumn('cargo_id');
        });
    }
};
