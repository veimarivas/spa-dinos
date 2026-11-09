<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parentesco extends Model
{
    protected $table = "parentescos";

    protected $fillable = [
        'cliente_id',
        'hijo_id',
        'relacion'
    ];

    // Relación existente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function hijo()
    {
        return $this->belongsTo(Hijo::class, 'hijo_id');
    }
}
