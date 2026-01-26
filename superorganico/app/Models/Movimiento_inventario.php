<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento_inventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'lote_id',
        'tipo_movimiento',
        'cantidad',
        'fecha_movimiento',
        'costo_unitario',
        'referencia',
        'referencia_id',
        'observaciones',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'costo_unitario' => 'decimal:2',
    ];

    // Relaciones
    public function lote()
    {
        return $this->belongsTo(Lote_inventario::class, 'lote_id');
    }

    public function producto()
    {
        return $this->hasOneThrough(
            Producto::class,
            Lote_inventario::class,
            'id', // Foreign key on lotes_inventario table
            'id', // Foreign key on productos table
            'lote_id', // Local key on movimientos_inventario table
            'producto_id' // Local key on lotes_inventario table
        );
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scopes
    public function scopeEntradas($query)
    {
        return $query->where('tipo_movimiento', 'entrada');
    }

    public function scopeSalidas($query)
    {
        return $query->where('tipo_movimiento', 'salida');
    }

    public function scopeAjustes($query)
    {
        return $query->where('tipo_movimiento', 'ajuste');
    }

    public function scopeMermas($query)
    {
        return $query->where('tipo_movimiento', 'merma');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    // Métodos auxiliares
    public function esEntrada()
    {
        return $this->tipo_movimiento === 'entrada';
    }

    public function esSalida()
    {
        return $this->tipo_movimiento === 'salida';
    }

    public function valorTotal()
    {
        return $this->cantidad * $this->costo_unitario;
    }

    public function getReferenciaFormateada()
    {
        if (!$this->referencia) return 'N/A';

        return ucfirst($this->referencia) . ' #' . $this->referencia_id;
    }
}
