<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaServicio extends Model
{
    use HasFactory;

    protected $table = 'citas_servicios';
    protected $fillable = [
        'cita_id',
        'servicio_id',
        'precio_aplicado',
    ];
    protected $casts = [
        'precio_aplicado' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}