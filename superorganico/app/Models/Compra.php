<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'numero_compra',
        'numero_factura',
        'proveedor_id',
        'usuario_id',
        'fecha',
        'fecha_recepcion',
        'subtotal',
        'impuestos',
        'credito_fiscal',
        'total',
        'observaciones',
        'estado',
        'estado_contable',
        'asiento_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_recepcion' => 'date',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'credito_fiscal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle_compra::class, 'compra_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote_inventario::class, 'compra_id');
    }

    public function asientoContable()
    {
        return $this->belongsTo(Asiento_contable::class, 'asiento_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeRecibidas($query)
    {
        return $query->where('estado', 'recibida');
    }

    public function scopeNoContabilizadas($query)
    {
        return $query->where('estado_contable', 'no_contabilizado');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_compra', [$fechaInicio, $fechaFin]);
    }

    // Métodos auxiliares
    public function estaContabilizada()
    {
        return $this->estado_contable === 'contabilizado' && $this->asiento_id !== null;
    }

    public function puedeContabilizar()
    {
        return $this->estado === 'recibida' && $this->estado_contable === 'no_contabilizado';
    }

    /**
     * Genera asiento contable automáticamente usando servicio
     */
    public function contabilizar()
    {
        if ($this->asiento_id) {
            throw new \Exception("Esta compra ya tiene asiento contable generado");
        }

        $servicio = new \App\Services\ContabilidadService();
        $asiento = $servicio->generarAsientoCompra($this);
        
        return $asiento;
    }

    public function generarNumeroCompra()
    {
        $fecha = $this->fecha->format('Ymd');
        $ultimo = self::whereDate('fecha', $this->fecha)->count() + 1;
        return 'COMP-' . $fecha . '-' . str_pad($ultimo, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($compra) {
            if (empty($compra->numero_compra)) {
                $compra->numero_compra = $compra->generarNumeroCompra();
            }
        });
    }
}
