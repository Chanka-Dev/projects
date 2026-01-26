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
        'telefono',
        'email',
        'direccion',
        'observaciones',
        'servicios_totales', 
        'total_gastado', 
        'fecha_registro', 
        'ultima_visita'
    ];
    protected $casts = [
        'fecha_registro' => 'date',
        'ultima_visita' => 'date',
        'total_gastado' => 'decimal:2',
        'servicios_totales' => 'integer'
    ];    

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function historialServicios()
    {
        return $this->hasMany(HistorialServicio::class);
    }
}