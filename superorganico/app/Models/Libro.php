<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'cuenta_id',
        'periodo',
        'saldo_inicial',
        'debe_periodo',
        'haber_periodo',
        'saldo_final',
    ];

    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'debe_periodo' => 'decimal:2',
        'haber_periodo' => 'decimal:2',
        'saldo_final' => 'decimal:2',
    ];

    // Relaciones
    public function cuenta()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_id');
    }

    // Scopes
    public function scopePeriodo($query, $periodo)
    {
        return $query->where('periodo', $periodo);
    }

    // Métodos auxiliares
    public function calcularSaldoFinal()
    {
        if ($this->cuenta->esDeudora()) {
            $this->saldo_final = $this->saldo_inicial + $this->debe_periodo - $this->haber_periodo;
        } else {
            $this->saldo_final = $this->saldo_inicial + $this->haber_periodo - $this->debe_periodo;
        }

        return $this;
    }

    public function actualizarMovimientos()
    {
        // Calcular movimientos del periodo
        $detalles = $this->cuenta->detalleAsientos()
            ->whereHas('asiento', function($query) {
                $query->where('estado', 'contabilizado')
                      ->whereRaw("DATE_FORMAT(fecha, '%Y-%m') = ?", [$this->periodo]);
            });

        $this->debe_periodo = $detalles->sum('debe');
        $this->haber_periodo = $detalles->sum('haber');
        $this->calcularSaldoFinal();
        $this->save();

        return $this;
    }
}
