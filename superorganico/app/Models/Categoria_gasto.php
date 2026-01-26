<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria_gasto extends Model
{
    use HasFactory;

    protected $table = 'categoria_gastos';

    protected $fillable = [
        'nombre',
        'cuenta_id',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    // Relaciones
    public function cuenta()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_id');
    }

    public function gastos()
    {
        return $this->hasMany(Gasto_operativo::class, 'categoria_gasto_id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    // Métodos auxiliares
    public function totalGastos($fechaInicio = null, $fechaFin = null)
    {
        $query = $this->gastos();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_gasto', [$fechaInicio, $fechaFin]);
        }

        return $query->sum('monto');
    }
}
