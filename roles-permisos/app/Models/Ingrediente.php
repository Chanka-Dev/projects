<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $fillable = ['nombre', 'unidad_default', 'categoria', 'descripcion', 'es_alergeno'];

    protected $casts = ['es_alergeno' => 'boolean'];

    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngrediente::class);
    }
}
