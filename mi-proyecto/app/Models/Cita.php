<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $fillable = [
        'cliente_id',
        'trabajadora_id',
        'fecha',
        'estado',
        'observaciones',
    ];
    protected $casts = [
        'fecha' => 'date',
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

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'citas_servicios')
                    ->withPivot('precio_aplicado')
                    ->withTimestamps();
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function recordatorios()
    {
        return $this->hasOne(Recordatorio::class);
    }
}