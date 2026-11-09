<?php
// database/seeders/CargoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    public function run()
    {
        $cargos = ['Administrador', 'Cobrador', 'Fisioterapeuta'];
        foreach ($cargos as $nombre) {
            Cargo::create(['nombre' => $nombre]);
        }
    }
}
