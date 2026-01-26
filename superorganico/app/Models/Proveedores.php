<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'nit',
        'ci',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'pais',
        'activo',
    ];

    // Relaciones
    public function compras()
    {
        return $this->hasMany(Compra::class, 'proveedor_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote_inventario::class, 'proveedor_id');
    }

    public function gastosOperativos()
    {
        return $this->hasMany(Gasto_operativo::class, 'proveedor_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->whereHas('compras');
    }

    // Métodos auxiliares
    public function totalCompras()
    {
        return $this->compras()->sum('total');
    }

    public function comprasPendientes()
    {
        return $this->compras()->where('estado', 'pendiente')->count();
    }
}
