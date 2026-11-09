<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proveedore_id');
            $table->date('fecha_compra');
            $table->decimal('total_bs', 10, 2);
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('proveedore_id')->references('id')->on('proveedores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
