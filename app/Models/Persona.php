<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "personas";

    protected $fillable = [
        'carnet',
        'nit',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'direccion',
        'fecha_nacimiento',
        'celular',
        'telefono'
    ];

    //relaciones
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'persona_id'); // Relación 1 a 1
    }

    public function trabajador()
    {
        return $this->hasOne(Trabajadore::class, 'persona_id'); // Relación 1 a 1
    }

    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

    public function hijo()
    {
        return $this->hasOne(Hijo::class, 'persona_id');
    }
}
