<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaParte extends Model
{
    protected $fillable = ['receta_id', 'titulo', 'orden', 'instrucciones'];

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }

    public function ingredientes()
    {
        return $this->hasMany(RecetaIngrediente::class);
    }
}
