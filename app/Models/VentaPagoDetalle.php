<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaPagoDetalle extends Model
{
    protected $table = 'venta_pago_detalles';

    protected $fillable = [
        'venta_id',
        'pago_bs',
        'tipo_pago',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }
}
