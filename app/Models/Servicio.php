<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = "servicios";

    protected $fillable = [
        'nombre',
        'descripcion',
        'pago_con_sesion',
        'pago_sin_sesion',
        'pago_especial'
    ];

    // Relación existente
    public function sesiones()
    {
        return $this->hasMany(Sesione::class, 'servicio_id');
    }
}
