<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    protected $table = "paquetes";

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación existente
    public function planes()
    {
        return $this->hasMany(Plane::class, 'paquete_id');
    }
}
