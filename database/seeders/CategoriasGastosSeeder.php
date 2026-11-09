<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasGastosSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Salarios', 'descripcion' => 'Pago de sueldos y salarios a trabajadores'],
            ['nombre' => 'Servicios Básicos', 'descripcion' => 'Luz, agua, internet, teléfono'],
            ['nombre' => 'Alquiler', 'descripcion' => 'Alquiler de local o espacio'],
            ['nombre' => 'Compra de Productos', 'descripcion' => 'Compras de productos para inventario'],
            ['nombre' => 'Mantenimiento', 'descripcion' => 'Mantenimiento de equipos e instalaciones'],
            ['nombre' => 'Otros', 'descripcion' => 'Gastos varios no categorizados'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias_gastos')->updateOrInsert(
                ['nombre' => $cat['nombre']],
                array_merge($cat, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
