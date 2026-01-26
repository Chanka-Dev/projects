<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'image_path',
        'categoria_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('nombre', 'like', '%' . request('search') . '%')
                  ->orWhere('descripcion', 'like', '%' . request('search') . '%');
        }

        if ($filters['categoria'] ?? false) {
            $query->where('categoria_id', request('categoria'));
        }
    }
}
