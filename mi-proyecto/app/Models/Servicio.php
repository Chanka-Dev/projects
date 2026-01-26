<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_base',
        'monto_comision',
        'activo',
    ];
    protected $casts = [
        'precio_base' => 'decimal:2',
        'monto_comision' => 'decimal:2',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'citas_servicios')
                    ->withPivot('precio_aplicado')
                    ->withTimestamps();
    }

    public function historialServicios()
    {
        return $this->hasMany(HistorialServicio::class);
    }
}