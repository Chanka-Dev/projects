<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Lote_inventario;
use App\Models\Movimiento_inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * Servicio de Inventario con método PEPS
 * Para gestión de productos orgánicos perecederos
 */
class InventarioService
{
    /**
     * Descuenta stock de un producto usando PEPS
     * Consume automáticamente de los lotes más antiguos
     * 
     * @param Producto $producto
     * @param float $cantidad
     * @param string $tipoMovimiento
     * @param mixed $referencia (Venta, Compra, etc)
     * @return Collection Lotes consumidos con cantidades
     * @throws \Exception
     */
    public function descontarStock(
        Producto $producto, 
        float $cantidad, 
        string $tipoMovimiento = 'salida',
        $referencia = null
    ): Collection {
        return DB::transaction(function () use ($producto, $cantidad, $tipoMovimiento, $referencia) {
            $cantidadRestante = $cantidad;
            $lotesConsumidos = collect();

            // Obtener lotes disponibles ordenados por PEPS
            $lotes = $producto->lotes()
                ->disponibles()
                ->ordenarPEPS()
                ->get();

            // Verificar stock suficiente
            $stockTotal = $lotes->sum('cantidad_actual');
            if ($stockTotal < $cantidad) {
                throw new \Exception(
                    "Stock insuficiente para {$producto->nombre}. " .
                    "Disponible: {$stockTotal}, Requerido: {$cantidad}"
                );
            }

            // Consumir lotes en orden PEPS
            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0) break;

                $cantidadAConsumir = min($cantidadRestante, $lote->cantidad_actual);

                // Consumir del lote pasando usuario_id
                $usuarioId = auth()->id() ?? 1;
                $referenciaClase = $referencia ? class_basename(get_class($referencia)) : null;
                $referenciaId = $referencia?->id;
                
                $lote->consumir($cantidadAConsumir, $referenciaClase, $referenciaId, $usuarioId);

                // Obtener el movimiento que creó consumir()
                $movimiento = Movimiento_inventario::where('lote_id', $lote->id)
                    ->orderBy('id', 'desc')
                    ->first();

                // Ya no necesitamos crear otro movimiento, el método consumir() ya lo hizo

                $lotesConsumidos->push([
                    'lote' => $lote,
                    'cantidad' => $cantidadAConsumir,
                    'costo_unitario' => $lote->costo_unitario,
                    'movimiento' => $movimiento,
                ]);

                $cantidadRestante -= $cantidadAConsumir;
            }

            return $lotesConsumidos;
        });
    }

    /**
     * Ingresa stock de un nuevo lote
     * 
     * @param array $datos
     * @return Lote_inventario
     */
    public function ingresarStock(array $datos): Lote_inventario
    {
        return DB::transaction(function () use ($datos) {
            // Crear lote
            $lote = Lote_inventario::create($datos);

            // Crear movimiento de entrada
            Movimiento_inventario::create([
                'lote_id' => $lote->id,
                'tipo_movimiento' => 'entrada',
                'cantidad' => $lote->cantidad_inicial,
                'costo_unitario' => $lote->costo_unitario,
                'fecha_movimiento' => $lote->fecha_ingreso,
                'referencia' => $lote->compra ? 'Compra' : null,
                'referencia_id' => $lote->compra_id,
                'usuario_id' => auth()->id() ?? 1,
            ]);

            return $lote;
        });
    }

    /**
     * Alerta productos próximos a vencer
     * 
     * @param int $dias Días de antelación (default: 7)
     * @return Collection
     */
    public function alertarVencimientos(int $dias = 7): Collection
    {
        return Lote_inventario::disponibles()
            ->porVencer($dias)
            ->with('producto')
            ->get();
    }

    /**
     * Calcula mermas por productos vencidos
     * 
     * @return Collection
     */
    public function calcularMermas(): Collection
    {
        return Lote_inventario::vencidos()
            ->where('cantidad_actual', '>', 0)
            ->with('producto')
            ->get()
            ->map(function ($lote) {
                $costoMerma = $lote->cantidad_actual * $lote->costo_unitario;

                return [
                    'lote_id' => $lote->id,
                    'producto' => $lote->producto->nombre,
                    'numero_lote' => $lote->numero_lote,
                    'cantidad_vencida' => $lote->cantidad_actual,
                    'costo_unitario' => $lote->costo_unitario,
                    'costo_merma' => $costoMerma,
                    'fecha_caducidad' => $lote->fecha_caducidad,
                ];
            });
    }

    /**
     * Registra merma por caducidad
     * 
     * @param Lote_inventario $lote
     * @param string $observacion
     * @return Movimiento_inventario
     */
    public function registrarMerma(Lote_inventario $lote, string $observacion = ''): Movimiento_inventario
    {
        return DB::transaction(function () use ($lote, $observacion) {
            $cantidadMerma = $lote->cantidad_actual;

            // Crear movimiento de merma
            $movimiento = Movimiento_inventario::create([
                'lote_id' => $lote->id,
                'tipo_movimiento' => 'merma',
                'cantidad' => -$cantidadMerma,
                'costo_unitario' => $lote->costo_unitario,
                'fecha_movimiento' => now(),
                'observaciones' => $observacion ?: "Merma por caducidad - Vencido el {$lote->fecha_caducidad}",
                'usuario_id' => auth()->id() ?? 1,
            ]);

            // Reducir stock a cero
            $lote->update(['cantidad_actual' => 0]);

            return $movimiento;
        });
    }

    /**
     * Obtiene stock actual de un producto
     * 
     * @param Producto $producto
     * @return array
     */
    public function obtenerStockActual(Producto $producto): array
    {
        $lotes = $producto->lotes()->disponibles()->get();

        return [
            'producto_id' => $producto->id,
            'producto' => $producto->nombre,
            'cantidad_total' => $lotes->sum('cantidad_actual'),
            'costo_promedio_peps' => $producto->costoPromedioPEPS(),
            'lotes_disponibles' => $lotes->count(),
            'lotes' => $lotes->map(function ($lote) {
                return [
                    'numero_lote' => $lote->numero_lote,
                    'cantidad' => $lote->cantidad_actual,
                    'costo' => $lote->costo_unitario,
                    'fecha_ingreso' => $lote->fecha_ingreso,
                    'fecha_caducidad' => $lote->fecha_caducidad,
                    'dias_vencer' => $lote->diasParaVencer(),
                ];
            }),
        ];
    }

    /**
     * Reporte de movimientos de inventario
     * 
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param int|null $productoId
     * @return Collection
     */
    public function reporteMovimientos(
        string $fechaInicio, 
        string $fechaFin, 
        ?int $productoId = null
    ): Collection {
        // Asegurar que las fechas incluyan el rango completo del día
        $fechaInicioCompleta = $fechaInicio . ' 00:00:00';
        $fechaFinCompleta = $fechaFin . ' 23:59:59';
        
        $query = Movimiento_inventario::whereBetween('fecha_movimiento', [$fechaInicioCompleta, $fechaFinCompleta])
            ->with(['producto', 'lote']);

        if ($productoId) {
            // Filtrar por producto a través de la relación con lote
            $query->whereHas('lote', function ($q) use ($productoId) {
                $q->where('producto_id', $productoId);
            });
        }

        return $query->orderBy('fecha_movimiento')
            ->get()
            ->map(function ($movimiento) {
                $cantidad = abs($movimiento->cantidad);
                
                // Usar directamente el tipo_movimiento de la BD
                $tipo = $movimiento->tipo_movimiento;
                
                return [
                    'fecha' => $movimiento->fecha_movimiento,
                    'tipo' => $tipo,
                    'detalle' => $movimiento->referencia 
                        ? $movimiento->referencia . ($movimiento->referencia_id ? ' #' . $movimiento->referencia_id : '') 
                        : ($movimiento->observaciones ?? 'N/A'),
                    'producto' => $movimiento->lote->producto->nombre ?? 'N/A',
                    'lote' => $movimiento->lote->numero_lote ?? 'N/A',
                    'cantidad' => $cantidad,
                    'precio_unitario' => $movimiento->costo_unitario,
                    'total' => $cantidad * $movimiento->costo_unitario,
                ];
            });
    }

    /**
     * Productos con bajo stock (alerta de reorden)
     * 
     * @return Collection
     */
    public function productosBajoStock(): Collection
    {
        return Producto::bajoStock()
            ->with('lotes')
            ->get()
            ->each(function ($producto) {
                $stockActual = $producto->lotes()->disponibles()->sum('cantidad_actual');
                $producto->stock_actual = $stockActual;
                $producto->deficit = $producto->stock_minimo - $stockActual;
            });
    }

    /**
     * Ajuste manual de inventario (para correcciones)
     * 
     * @param Lote_inventario $lote
     * @param float $nuevaCantidad
     * @param string $motivo
     * @return Movimiento_inventario
     */
    public function ajustarInventario(
        Lote_inventario $lote, 
        float $nuevaCantidad, 
        string $motivo
    ): Movimiento_inventario {
        return DB::transaction(function () use ($lote, $nuevaCantidad, $motivo) {
            $cantidadActual = $lote->cantidad_actual;
            $diferencia = $nuevaCantidad - $cantidadActual;

            // Crear movimiento de ajuste
            $movimiento = Movimiento_inventario::create([
                'lote_id' => $lote->id,
                'producto_id' => $lote->producto_id,
                'tipo_movimiento' => 'ajuste',
                'cantidad' => $diferencia,
                'costo_unitario' => $lote->costo_unitario,
                'fecha_movimiento' => now(),
                'observacion' => "Ajuste manual: {$motivo}",
            ]);

            // Actualizar cantidad en lote
            $lote->update(['cantidad_actual' => $nuevaCantidad]);

            return $movimiento;
        });
    }
}
