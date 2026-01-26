<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'tipo',
        'unidad_medida',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'requiere_lote',
        'dias_caducidad',
        'dias_alerta_vencimiento',
        'perecedero',
        'activo',
        'cuenta_inventario_id',
        'cuenta_costo_venta_id',
        'cuenta_ingreso_id',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'requiere_lote' => 'boolean',
        'perecedero' => 'boolean',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function cuentaInventario()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_inventario_id');
    }

    public function cuentaCostoVenta()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_costo_venta_id');
    }

    public function cuentaIngreso()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_ingreso_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote_inventario::class, 'producto_id');
    }

    public function lotesDisponibles()
    {
        return $this->hasMany(Lote_inventario::class, 'producto_id')
                    ->where('estado', 'disponible')
                    ->where('cantidad_actual', '>', 0)
                    ->orderBy('fecha_caducidad', 'asc');
    }

    public function detallesVenta()
    {
        return $this->hasMany(Detalle_venta::class, 'producto_id');
    }

    public function detallesCompra()
    {
        return $this->hasMany(Detalle_compra::class, 'producto_id');
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopePerecibles($query)
    {
        return $query->where('perecedero', true);
    }

    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopeBajoStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    // Métodos auxiliares
    public function margenUtilidad()
    {
        $costo = $this->costoPromedioPEPS();
        if ($costo == 0) return 0;
        return (($this->precio_venta - $costo) / $costo) * 100;
    }

    public function utilidadPorUnidad()
    {
        return $this->precio_venta - $this->costoPromedioPEPS();
    }

    /**
     * Calcula el precio de factura aplicando la tasa efectiva 14.91%
     * Ejemplo: Si precio_venta = 150, precio_factura = 150 * 1.1491 = 172.365
     * 
     * @return float
     */
    public function precioFactura()
    {
        return round($this->precio_venta * 1.1491, 2);
    }

    /**
     * Calcula el precio de venta a partir de un costo y un margen
     * Ejemplo: costo = 100, margen = 50% => precio_venta = 150
     * 
     * @param float $costo
     * @param float $margenPorcentaje
     * @return float
     */
    public static function calcularPrecioVenta($costo, $margenPorcentaje)
    {
        return round($costo * (1 + ($margenPorcentaje / 100)), 2);
    }

    /**
     * Calcula precio de factura desde costo aplicando margen + tasa efectiva
     * Ejemplo: costo = 100, margen = 50% => precio_factura = 172.065
     * 
     * @param float $costo
     * @param float $margenPorcentaje
     * @return float
     */
    public static function calcularPrecioFacturaDesde($costo, $margenPorcentaje)
    {
        $precioVenta = self::calcularPrecioVenta($costo, $margenPorcentaje);
        return round($precioVenta * 1.1471, 2);
    }

    public function stockDisponible()
    {
        if ($this->requiere_lote) {
            return $this->lotesDisponibles()->sum('cantidad_actual');
        }
        return $this->stock;
    }

    public function costoPromedioPEPS()
    {
        $lotes = $this->lotesDisponibles()->get();
        if ($lotes->isEmpty()) return $this->precio_compra;

        $totalCosto = 0;
        $totalCantidad = 0;

        foreach ($lotes as $lote) {
            $totalCosto += $lote->costo_unitario * $lote->cantidad_actual;
            $totalCantidad += $lote->cantidad_actual;
        }

        return $totalCantidad > 0 ? $totalCosto / $totalCantidad : $this->precio_compra;
    }
}
