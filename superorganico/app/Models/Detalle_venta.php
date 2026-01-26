<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_venta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'lote_id',
        'cantidad',
        'precio_unitario',
        'costo_unitario',
        'descuento_unitario',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'descuento_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote_inventario::class, 'lote_id');
    }

    // Métodos auxiliares
    public function utilidad()
    {
        return ($this->precio_unitario - $this->costo_unitario) * $this->cantidad;
    }

    public function margenUtilidad()
    {
        if ($this->costo_unitario == 0) return 0;
        return (($this->precio_unitario - $this->costo_unitario) / $this->costo_unitario) * 100;
    }

    public function totalDescuento()
    {
        return $this->descuento_unitario * $this->cantidad;
    }
}
