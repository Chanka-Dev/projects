<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $fillable = ['titulo', 'etiqueta_id', 'user_id', 'fuente', 'link', 'imagen', 'instrucciones'];

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function partes()
    {
        return $this->hasMany(RecetaParte::class)->orderBy('orden');
    }

    public function ingredientes()
    {
        return $this->hasMany(RecetaIngrediente::class);
    }
}
