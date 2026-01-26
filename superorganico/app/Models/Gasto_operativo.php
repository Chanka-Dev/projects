<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto_operativo extends Model
{
    use HasFactory;

    protected $table = 'gasto_operativos';

    protected $fillable = [
        'numero_gasto',
        'categoria_gasto_id',
        'cuenta_id',
        'descripcion',
        'monto',
        'fecha_gasto',
        'proveedor_id',
        'tipo_comprobante',
        'estado',
        'estado_contable',
        'usuario_id',
        'asiento_id',
    ];

    protected $casts = [
        'fecha_gasto' => 'date',
        'monto' => 'decimal:2',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria_gasto::class, 'categoria_gasto_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
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

    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    public function scopeNoContabilizados($query)
    {
        return $query->where('estado_contable', 'no_contabilizado');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_gasto', [$fechaInicio, $fechaFin]);
    }

    // Métodos auxiliares
    public function estaContabilizado()
    {
        return $this->estado_contable === 'contabilizado' && $this->asiento_id !== null;
    }

    public function puedeContabilizar()
    {
        return $this->estado === 'pagado' && $this->estado_contable === 'no_contabilizado';
    }

    /**
     * Genera asiento contable automáticamente usando servicio
     */
    public function contabilizar()
    {
        if ($this->asiento_id) {
            throw new \Exception("Este gasto ya tiene asiento contable generado");
        }

        $servicio = new \App\Services\ContabilidadService();
        $asiento = $servicio->generarAsientoGasto($this);
        
        return $asiento;
    }

    public function generarNumeroGasto()
    {
        $fecha = $this->fecha_gasto->format('Ym');
        $ultimo = self::whereYear('fecha_gasto', $this->fecha_gasto->year)
                      ->whereMonth('fecha_gasto', $this->fecha_gasto->month)
                      ->count() + 1;
        return 'GAS-' . $fecha . '-' . str_pad($ultimo, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($gasto) {
            if (empty($gasto->numero_gasto)) {
                $gasto->numero_gasto = $gasto->generarNumeroGasto();
            }
            
            // Auto-asignar usuario autenticado
            if (empty($gasto->usuario_id) && auth()->check()) {
                $gasto->usuario_id = auth()->id();
            }
        });
    }
}
