<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_asiento extends Model
{
    use HasFactory;

    protected $table = 'detalle_asientos';

    protected $fillable = [
        'asiento_id',
        'cuenta_id',
        'debe',
        'haber',
        'descripcion',
    ];

    protected $casts = [
        'debe' => 'decimal:2',
        'haber' => 'decimal:2',
    ];

    // Relaciones
    public function asiento()
    {
        return $this->belongsTo(Asiento_contable::class, 'asiento_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_id');
    }

    // Scopes
    public function scopeDebitos($query)
    {
        return $query->where('debe', '>', 0);
    }

    public function scopeCreditos($query)
    {
        return $query->where('haber', '>', 0);
    }

    // Métodos auxiliares
    public function esDebe()
    {
        return $this->debe > 0;
    }

    public function esHaber()
    {
        return $this->haber > 0;
    }

    public function monto()
    {
        return $this->debe > 0 ? $this->debe : $this->haber;
    }
}
