<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('recibo');
            $table->decimal('pago_bs', 8, 2);
            $table->decimal('descuento_bs', 8, 2)->nullable();
            $table->date('fecha_pago');
            $table->enum('tipo_pago', ['efectivo', 'qr', 'parcial'])->default('efectivo');
            $table->unsignedBigInteger('suscripcione_id');
            $table->unsignedBigInteger('trabajadore_id');
            $table->timestamps();

            $table->foreign('suscripcione_id')->references('id')->on('suscripciones');
            $table->foreign('trabajadore_id')->references('id')->on('trabajadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
