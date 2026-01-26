<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_compra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compras';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'lote_id',
        'cantidad',
        'costo_unitario',
        'subtotal',
    ];

    protected $casts = [
        'costo_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote_inventario::class, 'lote_id');
    }
}
