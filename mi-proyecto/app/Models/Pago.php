<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $fillable = [
        'cita_id',
        'monto_total',
        'metodo_pago',
        'estado',
        'fecha_pago',
    ];
    protected $casts = [
        'monto_total' => 'decimal:2',
        'fecha_pago' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}