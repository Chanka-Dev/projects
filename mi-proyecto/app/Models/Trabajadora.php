<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajadora extends Model
{
    use HasFactory;

    protected $table = 'trabajadoras';
    protected $fillable = [
        'nombre',
        'tipo_contrato',
        'porcentaje_comision',
        'activo',
    ];
    protected $casts = [
        'porcentaje_comision' => 'decimal:2',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function historialServicios()
    {
        return $this->hasMany(HistorialServicio::class);
    }

    public function pagosTrabajadora()
    {
        return $this->hasMany(PagoTrabajadora::class);
    }
}