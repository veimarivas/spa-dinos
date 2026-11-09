<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesione extends Model
{
    protected $table = "sesiones";

    protected $fillable = [
        'suscripcione_id',
        'hijo_id',
        'n_sesion',
        'planes_servicio_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'trabajadore_id',
        'informe_sesion',
        'asistio_hijo',
        'reprogramar_id',
        'estado',
        'pago_salario_id',
    ];

    const ESTADO_PROGRAMADA = 'programada';
    const ESTADO_CONFIRMADA = 'confirmada';
    const ESTADO_REALIZADA = 'realizada';
    const ESTADO_CANCELADA = 'cancelada';
    const ESTADO_REPROGRAMADA = 'reprogramada';

    protected $casts = [
        'fecha'       => 'date',
        'hora_inicio' => 'string',
        'hora_fin'    => 'string',
        'asistio_hijo' => 'boolean',
    ];

    // Relación existente
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcione::class, 'suscripcione_id');
    }

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'hijo_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajadore::class, 'trabajadore_id');
    }

    // Relación con el plan_servicio
    public function planServicio()
    {
        return $this->belongsTo(PlanesServicios::class, 'planes_servicio_id');
    }

    // Relación con el servicio a través de planServicio (opcional)
    public function servicio()
    {
        return $this->hasOneThrough(
            Servicio::class,
            PlanesServicios::class,
            'id',
            'id',
            'planes_servicio_id',
            'servicio_id'
        );
    }

    /**
     * Sesión original de la cual se reprogramó esta sesión.
     */
    public function sesionOriginal()
    {
        return $this->belongsTo(self::class, 'reprogramar_id');
    }

    /**
     * Todas las sesiones que se reprogramaron a partir de esta (historial de reprogramaciones).
     */
    public function reprogramaciones()
    {
        return $this->hasMany(self::class, 'reprogramar_id');
    }

    // También puedes agregar un accessor para saber si esta sesión es una reprogramación
    public function getEsReprogramacionAttribute(): bool
    {
        return !is_null($this->reprogramar_id);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'sesione_id');
    }

    public function pagoSalario()
    {
        return $this->belongsTo(Gasto::class, 'pago_salario_id');
    }
}
