<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesServicios extends Model
{
    protected $table = 'planes_servicios';

    protected $fillable = ['plane_id', 'servicio_id', 'cantidad_sesiones'];

    public function plan()
    {
        return $this->belongsTo(Plane::class, 'plane_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class, 'planes_servicio_id');
    }
}
