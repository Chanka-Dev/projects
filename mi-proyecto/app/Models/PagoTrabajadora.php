<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoTrabajadora extends Model
{
    use HasFactory;

    protected $table = 'pago_trabajadoras';
    
    protected $fillable = [
        'trabajadora_id',
        'fecha_inicio_periodo',
        'fecha_fin_periodo',
        'total_servicios',
        'total_comisiones',
        'monto_pagado',
        'estado',
        'fecha_pago',
        'metodo_pago',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio_periodo' => 'date',
        'fecha_fin_periodo' => 'date',
        'fecha_pago' => 'date',
        'total_servicios' => 'decimal:2',
        'total_comisiones' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function trabajadora()
    {
        return $this->belongsTo(Trabajadora::class);
    }

    public function historialServicios()
    {
        return $this->hasMany(HistorialServicio::class);
    }
}
