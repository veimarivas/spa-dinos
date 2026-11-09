<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plane extends Model
{
    protected $table = "planes";

    protected $fillable = [
        'nombre_plan',
        'cantidad_sesiones',
        'precio',
        'precio_especial',
        'descripcion',
        'paquete_id'
    ];

    // Relación existente
    public function suscripciones()
    {
        return $this->hasMany(Suscripcione::class, 'plane_id');
    }

    public function paquete()
    {
        return $this->belongsTo(Paquete::class, 'paquete_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'planes_servicios', 'plane_id', 'servicio_id')
            ->withPivot('cantidad_sesiones')
            ->withTimestamps();
    }
}
