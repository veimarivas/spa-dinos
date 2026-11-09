<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = "compras";

    protected $fillable = [
        'proveedore_id',
        'fecha_compra',
        'total_bs',
        'observacion',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedore::class, 'proveedore_id');
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class, 'compra_id');
    }

    public function gasto()
    {
        return $this->hasOne(Gasto::class, 'compra_id');
    }
}
