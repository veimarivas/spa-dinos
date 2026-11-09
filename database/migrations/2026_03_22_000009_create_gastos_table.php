<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_gasto_id');
            $table->string('descripcion');
            $table->decimal('monto_bs', 10, 2);
            $table->date('fecha_gasto');
            $table->string('comprobante')->nullable();
            $table->unsignedBigInteger('compra_id')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('categoria_gasto_id')->references('id')->on('categorias_gastos');
            $table->foreign('compra_id')->references('id')->on('compras')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
