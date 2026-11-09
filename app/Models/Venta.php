<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = "ventas";

    protected $fillable = [
        'cliente_id',
        'sesione_id',
        'trabajadore_id',
        'recibo',
        'fecha_venta',
        'total_bs',
        'descuento_bs',
        'tipo_pago',
        'observacion',
    ];

    protected $casts = [
        'fecha_venta' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }

    public function sesion()
    {
        return $this->belongsTo(Sesione::class, 'sesione_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajadore::class, 'trabajadore_id');
    }

    public function pagoDetalles()
    {
        return $this->hasMany(VentaPagoDetalle::class, 'venta_id');
    }
}
