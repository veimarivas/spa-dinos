<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    protected $table = "proveedores";

    protected $fillable = [
        'nombre',
        'nit',
        'contacto_nombre',
        'celular',
        'correo',
        'direccion',
        'estado',
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'proveedore_id');
    }
}
