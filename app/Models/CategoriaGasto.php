<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaGasto extends Model
{
    protected $table = "categorias_gastos";

    protected $fillable = [
        'nombre',
        'descripcion',
        'parent_id',
    ];

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'categoria_gasto_id');
    }

    public function children()
    {
        return $this->hasMany(CategoriaGasto::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(CategoriaGasto::class, 'parent_id');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function subCategorias()
    {
        return $this->hasMany(CategoriaGasto::class, 'parent_id')
            ->with('subCategorias');
    }

    public function isParent()
    {
        return is_null($this->parent_id);
    }
}
