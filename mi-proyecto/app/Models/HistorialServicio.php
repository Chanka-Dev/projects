<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialServicio extends Model
{
    use HasFactory;

    protected $table = 'historial_servicios';
    protected $fillable = [
        'cita_id',
        'cliente_id',
        'trabajadora_id',
        'servicio_id',
        'fecha_servicio',
        'precio_cobrado',
        'monto_comision',
        'pago_trabajadora_id',
        'metodo_pago',
    ];
    protected $casts = [
        'fecha_servicio' => 'date',
        'precio_cobrado' => 'decimal:2',
        'porcentaje_comision' => 'decimal:2',
        'monto_comision' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function trabajadora()
    {
        return $this->belongsTo(Trabajadora::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function pagoTrabajadora()
    {
        return $this->belongsTo(PagoTrabajadora::class);
    }
}