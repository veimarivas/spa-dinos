<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = "detalles";

    protected $fillable = [
        'pago_id',
        'pago_bs',
        'tipo_pago'
    ];

    // Relación existente
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
