<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcione extends Model
{
    protected $table = "suscripciones";

    protected $fillable = [
        'cliente_id',
        'plane_id',
        'fecha_registro',
        'sesiones_iniciales',
        'sesiones_restantes',
        'estado',
        'tarifa',
        'monto_total',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime', // Convierte a Carbon
    ];

    // Relación existente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plane::class, 'plane_id');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class, 'suscripcione_id');
    }

    public function primeraSesion()
    {
        return $this->hasOne(Sesione::class, 'suscripcione_id')->orderBy('n_sesion');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'suscripcione_id');
    }

    public function serviciosUtilizados()
    {
        return $this->hasManyThrough(
            Servicio::class,
            Sesione::class,
            'suscripcione_id',
            'id',
            'id',
            'servicio_id'
        )->whereNotNull('sesiones.servicio_id');
    }
}
