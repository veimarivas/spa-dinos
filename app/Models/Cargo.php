<?php
// app/Models/Cargo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    protected $fillable = ['nombre', 'descripcion'];

    public function trabajadores()
    {
        return $this->hasMany(Trabajadore::class, 'cargo_id');
    }
}
