<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hijo extends Model
{
    protected $table = "hijos";

    protected $fillable = [
        'persona_id'
    ];

    // Relación existente
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function parentescos()
    {
        return $this->hasMany(Parentesco::class, 'hijo_id');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class, 'hijo_id');
    }
}
