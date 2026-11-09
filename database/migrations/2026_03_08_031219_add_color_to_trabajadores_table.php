<?php
// database/migrations/xxxx_add_color_to_trabajadores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('cargo_id')->default('#405189');
        });
    }

    public function down()
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
