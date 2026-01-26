<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'tipo',
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
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->whereHas('ventas');
    }

    // Métodos auxiliares
    public function totalComprado()
    {
        return $this->ventas()->sum('total');
    }

    public function ultimaCompra()
    {
        return $this->ventas()->latest('fecha_hora')->first();
    }

    public function cantidadCompras()
    {
        return $this->ventas()->count();
    }
}
