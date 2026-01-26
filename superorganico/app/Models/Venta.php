<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'fecha_hora',
        'tipo_comprobante',
        'subtotal',
        'iva',
        'it',
        'descuento',
        'impuesto',
        'total',
        'tipo_pago',
        'efectivo_recibido',
        'cambio',
        'observaciones',
        'estado_contable',
        'asiento_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'it' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total' => 'decimal:2',
        'efectivo_recibido' => 'decimal:2',
        'cambio' => 'decimal:2',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle_venta::class, 'venta_id');
    }

    public function asientoContable()
    {
        return $this->belongsTo(Asiento_contable::class, 'asiento_id');
    }

    // Scopes
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_hora', today());
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
    }

    public function scopeNoContabilizadas($query)
    {
        return $query->where('estado_contable', 'no_contabilizado');
    }

    public function scopeContabilizadas($query)
    {
        return $query->where('estado_contable', 'contabilizado');
    }

    public function scopeTipoPago($query, $tipo)
    {
        return $query->where('tipo_pago', $tipo);
    }

    // Métodos auxiliares
    public function estaContabilizada()
    {
        return $this->estado_contable === 'contabilizado' && $this->asiento_id !== null;
    }

    public function puedeContabilizar()
    {
        return $this->estado_contable === 'no_contabilizado';
    }

    /**
     * Genera asiento contable automáticamente usando servicio
     */
    public function contabilizar()
    {
        if ($this->asiento_id) {
            throw new \Exception("Esta venta ya tiene asiento contable generado");
        }

        $servicio = new \App\Services\ContabilidadService();
        $asiento = $servicio->generarAsientoVenta($this);
        
        return $asiento;
    }

    public function totalCosto()
    {
        return $this->detalles()->sum('costo_unitario');
    }

    public function utilidadBruta()
    {
        return $this->total - $this->totalCosto();
    }

    public function margenUtilidad()
    {
        if ($this->total == 0) return 0;
        return ($this->utilidadBruta() / $this->total) * 100;
    }

    public function cantidadItems()
    {
        return $this->detalles()->sum('cantidad');
    }
}
