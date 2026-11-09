<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes";

    protected $fillable = [
        'persona_id',
        'color'
    ];

    // Relación existente
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function parentescos()
    {
        return $this->hasMany(Parentesco::class, 'cliente_id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcione::class, 'cliente_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
}
