<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compra;
use App\Models\Detalle_compra;
use App\Models\Producto;
use App\Models\Proveedores;
use App\Services\ContabilidadService;
use App\Services\InventarioService;
use Carbon\Carbon;

class CompraSeeder extends Seeder
{
    protected $contabilidadService;
    protected $inventarioService;

    public function __construct()
    {
        $this->contabilidadService = app(ContabilidadService::class);
        $this->inventarioService = app(InventarioService::class);
    }

    public function run(): void
    {
        // Compra 1: Frutas - hace 15 días
        $this->crearCompra(
            fecha: Carbon::now()->subDays(15)->format('Y-m-d'),
            proveedor_id: 2,
            numero_factura: 'FAC-001',
            productos: [
                ['producto_id' => 1, 'cantidad' => 50, 'precio_factura' => 20.00], // Manzanas
                ['producto_id' => 2, 'cantidad' => 40, 'precio_factura' => 15.00], // Bananas
                ['producto_id' => 3, 'cantidad' => 35, 'precio_factura' => 16.00], // Naranjas
            ],
            dias_caducidad: [25, 15, 20]
        );

        // Compra 2: Verduras - hace 10 días
        $this->crearCompra(
            fecha: Carbon::now()->subDays(10)->format('Y-m-d'),
            proveedor_id: 1,
            numero_factura: 'FAC-002',
            productos: [
                ['producto_id' => 4, 'cantidad' => 100, 'precio_factura' => 8.00], // Lechugas
                ['producto_id' => 5, 'cantidad' => 60, 'precio_factura' => 18.00], // Tomates
                ['producto_id' => 6, 'cantidad' => 45, 'precio_factura' => 12.00], // Zanahorias
            ],
            dias_caducidad: [10, 15, 25]
        );

        // Compra 3: Granos - hace 7 días
        $this->crearCompra(
            fecha: Carbon::now()->subDays(7)->format('Y-m-d'),
            proveedor_id: 4,
            numero_factura: 'FAC-003',
            productos: [
                ['producto_id' => 7, 'cantidad' => 100, 'precio_factura' => 28.00], // Quinua
                ['producto_id' => 8, 'cantidad' => 80, 'precio_factura' => 22.00], // Arroz
            ],
            dias_caducidad: [730, 730] // 2 años para granos
        );

        // Compra 4: Lácteos - hace 5 días
        $this->crearCompra(
            fecha: Carbon::now()->subDays(5)->format('Y-m-d'),
            proveedor_id: 3,
            numero_factura: 'FAC-004',
            productos: [
                ['producto_id' => 9, 'cantidad' => 80, 'precio_factura' => 12.00], // Leche
                ['producto_id' => 10, 'cantidad' => 50, 'precio_factura' => 20.00], // Yogurt
            ],
            dias_caducidad: [8, 15]
        );

        // Compra 5: Mix de productos - hace 3 días
        $this->crearCompra(
            fecha: Carbon::now()->subDays(3)->format('Y-m-d'),
            proveedor_id: 5,
            numero_factura: 'FAC-005',
            productos: [
                ['producto_id' => 1, 'cantidad' => 30, 'precio_factura' => 20.00], // Manzanas
                ['producto_id' => 4, 'cantidad' => 50, 'precio_factura' => 8.00], // Lechugas
                ['producto_id' => 5, 'cantidad' => 40, 'precio_factura' => 18.00], // Tomates
                ['producto_id' => 9, 'cantidad' => 60, 'precio_factura' => 12.00], // Leche
            ],
            dias_caducidad: [25, 10, 15, 8]
        );
    }

    private function crearCompra($fecha, $proveedor_id, $numero_factura, $productos, $dias_caducidad)
    {
        // Calcular totales
        $subtotalCompra = 0;
        foreach ($productos as $prod) {
            $subtotalCompra += $prod['cantidad'] * $prod['precio_factura'];
        }

        $creditoFiscal = $subtotalCompra * 0.13;
        $totalCompra = $subtotalCompra;

        // Crear compra
        $compra = Compra::create([
            'numero_compra' => 'C-' . str_pad(Compra::count() + 1, 6, '0', STR_PAD_LEFT),
            'numero_factura' => $numero_factura,
            'proveedor_id' => $proveedor_id,
            'usuario_id' => 1,
            'fecha' => $fecha,
            'fecha_compra' => $fecha,
            'fecha_recepcion' => $fecha,
            'subtotal' => $subtotalCompra - $creditoFiscal,
            'impuestos' => 0,
            'credito_fiscal' => $creditoFiscal,
            'total' => $totalCompra,
            'observaciones' => 'Compra de prueba',
            'estado' => 'recibida',
            'estado_contable' => 'contabilizado',
        ]);

        // Crear detalles y generar lotes
        foreach ($productos as $index => $prod) {
            $producto = Producto::find($prod['producto_id']);
            $costoUnitario = $prod['precio_factura'] - ($prod['precio_factura'] * 0.13);
            $subtotalDetalle = $costoUnitario * $prod['cantidad'];

            // Crear detalle
            Detalle_compra::create([
                'compra_id' => $compra->id,
                'producto_id' => $prod['producto_id'],
                'cantidad' => $prod['cantidad'],
                'costo_unitario' => $costoUnitario,
                'subtotal' => $subtotalDetalle,
            ]);

            // Generar lote manualmente
            if ($producto->requiere_lote) {
                $fechaCaducidad = Carbon::parse($fecha)->addDays($dias_caducidad[$index])->format('Y-m-d');
                
                \App\Models\Lote_inventario::create([
                    'numero_lote' => 'LOTE-' . $compra->numero_compra . '-' . $prod['producto_id'],
                    'compra_id' => $compra->id,
                    'producto_id' => $prod['producto_id'],
                    'proveedor_id' => $proveedor_id,
                    'cantidad_inicial' => $prod['cantidad'],
                    'cantidad_actual' => $prod['cantidad'],
                    'costo_unitario' => $costoUnitario,
                    'fecha_ingreso' => $fecha,
                    'fecha_caducidad' => $fechaCaducidad,
                    'estado' => 'disponible',
                ]);
                
                // Actualizar stock del producto
                $producto->increment('stock', $prod['cantidad']);
            } else {
                // Para productos sin lote, solo actualizar stock
                $producto->increment('stock', $prod['cantidad']);
            }
        }

        // TODO: Contabilizar compra cuando el método esté disponible
        // $this->contabilidadService->contabilizarCompra($compra);

        echo "✓ Compra {$numero_factura} creada con " . count($productos) . " productos\n";
    }
}
