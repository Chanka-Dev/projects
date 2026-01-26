<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    protected $table = 'recordatorios';
    protected $fillable = [
        'cita_id',
        'mensaje',
        'fecha_envio',
        'tipo',
        'estado',
    ];
    protected $casts = [
        'fecha_envio' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}