<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajadore extends Model
{
    protected $table = "trabajadores";

    protected $fillable = [
        'persona_id',
        'cargo_id',
        'color'
    ];

    // Relación existente
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class, 'trabajadore_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pago_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    // Accessor para nombre completo (útil en vistas)
    public function getNombreCompletoAttribute()
    {
        return $this->persona->nombres . ' ' . $this->persona->apellido_paterno . ($this->persona->apellido_materno ? ' ' . $this->persona->apellido_materno : '');
    }
}
