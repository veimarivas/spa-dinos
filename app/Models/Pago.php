<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = "pagos";

    protected $fillable = [
        'recibo',
        'pago_bs',
        'descuento_bs',
        'fecha_pago',
        'tipo_pago',
        'suscripcione_id',
        'trabajadore_id'
    ];

    // Relación existente
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcione::class, 'suscripcione_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajadore::class, 'trabajadore_id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'pago_id');
    }
}
