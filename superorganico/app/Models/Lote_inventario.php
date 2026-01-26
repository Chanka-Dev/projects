<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lote_inventario extends Model
{
    use HasFactory;

    protected $table = 'lote_inventarios';

    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'compra_id',
        'numero_lote',
        'fecha_ingreso',
        'fecha_caducidad',
        'cantidad_inicial',
        'cantidad_actual',
        'costo_unitario',
        'estado',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_caducidad' => 'date',
        'costo_unitario' => 'decimal:2',
    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'proveedor_id');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento_inventario::class, 'lote_id');
    }

    public function detallesVenta()
    {
        return $this->hasMany(Detalle_venta::class, 'lote_id');
    }

    public function detallesCompra()
    {
        return $this->hasMany(Detalle_compra::class, 'lote_id');
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible')
                     ->where('cantidad_actual', '>', 0);
    }

    public function scopePorVencer($query, $dias = 7)
    {
        return $query->where('estado', 'disponible')
                     ->whereNotNull('fecha_caducidad')
                     ->whereBetween('fecha_caducidad', [now(), now()->addDays($dias)]);
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'disponible')
                     ->whereNotNull('fecha_caducidad')
                     ->where('fecha_caducidad', '<', now());
    }

    public function scopeOrdenarPEPS($query)
    {
        return $query->orderBy('fecha_ingreso', 'asc')
                     ->orderBy('fecha_caducidad', 'asc');
    }

    // Métodos auxiliares
    public function estaVencido()
    {
        if (!$this->fecha_caducidad) return false;
        return $this->fecha_caducidad->isPast();
    }

    public function diasParaVencer()
    {
        if (!$this->fecha_caducidad) return null;
        return now()->diffInDays($this->fecha_caducidad, false);
    }

    public function porcentajeUtilizado()
    {
        if ($this->cantidad_inicial == 0) return 0;
        return (($this->cantidad_inicial - $this->cantidad_actual) / $this->cantidad_inicial) * 100;
    }

    public function consumir($cantidad, $referencia = null, $referenciaId = null, $usuarioId = null)
    {
        if ($cantidad > $this->cantidad_actual) {
            throw new \Exception('Cantidad insuficiente en lote');
        }

        $this->cantidad_actual -= $cantidad;
        $this->save();

        // Registrar movimiento
        Movimiento_inventario::create([
            'lote_id' => $this->id,
            'tipo_movimiento' => 'salida',
            'cantidad' => $cantidad,
            'fecha_movimiento' => now(),
            'costo_unitario' => $this->costo_unitario,
            'referencia' => $referencia,
            'referencia_id' => $referenciaId,
            'usuario_id' => $usuarioId,
        ]);

        return $this;
    }

    public function generarNumeroLote()
    {
        $fecha = now()->format('Ymd');
        $producto = substr(str_replace(' ', '', $this->producto->nombre), 0, 3);
        $ultimo = self::whereDate('fecha_ingreso', now())->count() + 1;
        return 'LOTE-' . strtoupper($producto) . '-' . $fecha . '-' . str_pad($ultimo, 3, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lote) {
            if (empty($lote->numero_lote)) {
                $lote->numero_lote = $lote->generarNumeroLote();
            }
        });
    }
}
