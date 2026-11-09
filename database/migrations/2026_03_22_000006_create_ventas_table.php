<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->date('fecha_venta');
            $table->decimal('total_bs', 10, 2);
            $table->decimal('descuento_bs', 8, 2)->default(0);
            $table->enum('tipo_pago', ['efectivo', 'qr', 'parcial'])->default('efectivo');
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
