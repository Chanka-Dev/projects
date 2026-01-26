<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento_contable extends Model
{
    use HasFactory;

    protected $table = 'asiento_contables';

    protected $fillable = [
        'numero_asiento',
        'fecha',
        'tipo_asiento',
        'descripcion',
        'origen',
        'origen_id',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle_asiento::class, 'asiento_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'asiento_id');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'asiento_id');
    }

    public function gastosOperativos()
    {
        return $this->hasMany(Gasto_operativo::class, 'asiento_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeContabilizados($query)
    {
        return $query->where('estado', 'contabilizado');
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_asiento', $tipo);
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    // Métodos auxiliares
    public function totalDebe()
    {
        return $this->detalles()->sum('debe');
    }

    public function totalHaber()
    {
        return $this->detalles()->sum('haber');
    }

    public function estaCuadrado()
    {
        return round($this->totalDebe(), 2) === round($this->totalHaber(), 2);
    }

    public function diferencia()
    {
        return $this->totalDebe() - $this->totalHaber();
    }

    public function puedeContabilizar()
    {
        return $this->estado === 'pendiente' && $this->estaCuadrado();
    }

    public function contabilizar()
    {
        if (!$this->puedeContabilizar()) {
            throw new \Exception('El asiento no puede ser contabilizado');
        }

        $this->estado = 'contabilizado';
        $this->save();

        return $this;
    }

    public function anular()
    {
        $this->estado = 'anulado';
        $this->save();

        return $this;
    }

    public function generarNumeroAsiento()
    {
        // Si no hay fecha, no podemos generar número
        if (!$this->fecha) {
            return null;
        }
        
        // Asegurar que fecha sea un objeto Carbon
        $fecha = $this->fecha instanceof \Carbon\Carbon ? $this->fecha : \Carbon\Carbon::parse($this->fecha);
        $fechaStr = $fecha->format('Ym');
        $ultimo = self::whereYear('fecha', $fecha->year)
                      ->whereMonth('fecha', $fecha->month)
                      ->count() + 1;
        return 'ASI-' . $fechaStr . '-' . str_pad($ultimo, 5, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asiento) {
            if (empty($asiento->numero_asiento)) {
                $asiento->numero_asiento = $asiento->generarNumeroAsiento();
            }
        });
    }
}
