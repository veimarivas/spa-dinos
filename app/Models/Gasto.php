<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = "gastos";

    protected $fillable = [
        'categoria_gasto_id',
        'descripcion',
        'monto_bs',
        'fecha_gasto',
        'mes_correspondiente',
        'anio_correspondiente',
        'comprobante',
        'compra_id',
        'observacion',
    ];

    protected $casts = [
        'fecha_gasto' => 'date',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaGasto::class, 'categoria_gasto_id');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }
}
