<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaIngrediente extends Model
{
    protected $fillable = ['receta_id', 'receta_parte_id', 'ingrediente_id', 'cantidad', 'notas'];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }

    public function parte()
    {
        return $this->belongsTo(RecetaParte::class, 'receta_parte_id');
    }
}
