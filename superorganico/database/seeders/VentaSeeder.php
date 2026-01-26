<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\Detalle_venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Services\ContabilidadService;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    protected $contabilidadService;

    public function __construct()
    {
        $this->contabilidadService = app(ContabilidadService::class);
    }

    public function run(): void
    {
        // Venta 1 - hace 12 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(12)->format('Y-m-d'),
            cliente_id: 1,
            productos: [
                ['producto_id' => 1, 'cantidad' => 5], // Manzanas
                ['producto_id' => 3, 'cantidad' => 3], // Naranjas
            ]
        );

        // Venta 2 - hace 10 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(10)->format('Y-m-d'),
            cliente_id: 3,
            productos: [
                ['producto_id' => 4, 'cantidad' => 20], // Lechugas
                ['producto_id' => 5, 'cantidad' => 15], // Tomates
                ['producto_id' => 6, 'cantidad' => 10], // Zanahorias
            ]
        );

        // Venta 3 - hace 8 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(8)->format('Y-m-d'),
            cliente_id: 4,
            productos: [
                ['producto_id' => 7, 'cantidad' => 10], // Quinua
                ['producto_id' => 8, 'cantidad' => 15], // Arroz
                ['producto_id' => 9, 'cantidad' => 12], // Leche
            ]
        );

        // Venta 4 - hace 6 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(6)->format('Y-m-d'),
            cliente_id: 2,
            productos: [
                ['producto_id' => 2, 'cantidad' => 8], // Bananas
                ['producto_id' => 9, 'cantidad' => 10], // Leche
                ['producto_id' => 10, 'cantidad' => 5], // Yogurt
            ]
        );

        // Venta 5 - hace 5 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(5)->format('Y-m-d'),
            cliente_id: 6,
            productos: [
                ['producto_id' => 1, 'cantidad' => 25], // Manzanas
                ['producto_id' => 2, 'cantidad' => 20], // Bananas
                ['producto_id' => 4, 'cantidad' => 30], // Lechugas
                ['producto_id' => 5, 'cantidad' => 20], // Tomates
            ]
        );

        // Venta 6 - hace 4 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(4)->format('Y-m-d'),
            cliente_id: 5,
            productos: [
                ['producto_id' => 3, 'cantidad' => 10], // Naranjas
                ['producto_id' => 6, 'cantidad' => 8], // Zanahorias
            ]
        );

        // Venta 7 - hace 3 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(3)->format('Y-m-d'),
            cliente_id: 8,
            productos: [
                ['producto_id' => 9, 'cantidad' => 15], // Leche
                ['producto_id' => 10, 'cantidad' => 10], // Yogurt
                ['producto_id' => 1, 'cantidad' => 5], // Manzanas
            ]
        );

        // Venta 8 - hace 2 días
        $this->crearVenta(
            fecha: Carbon::now()->subDays(2)->format('Y-m-d'),
            cliente_id: 7,
            productos: [
                ['producto_id' => 4, 'cantidad' => 10], // Lechugas
                ['producto_id' => 5, 'cantidad' => 12], // Tomates
                ['producto_id' => 2, 'cantidad' => 6], // Bananas
            ]
        );

        // Venta 9 - ayer
        $this->crearVenta(
            fecha: Carbon::now()->subDay()->format('Y-m-d'),
            cliente_id: 4,
            productos: [
                ['producto_id' => 7, 'cantidad' => 20], // Quinua
                ['producto_id' => 8, 'cantidad' => 25], // Arroz
            ]
        );

        // Venta 10 - hoy
        $this->crearVenta(
            fecha: Carbon::now()->format('Y-m-d'),
            cliente_id: 3,
            productos: [
                ['producto_id' => 1, 'cantidad' => 8], // Manzanas
                ['producto_id' => 3, 'cantidad' => 6], // Naranjas
                ['producto_id' => 9, 'cantidad' => 10], // Leche
            ]
        );
    }

    private function crearVenta($fecha, $cliente_id, $productos)
    {
        // El precio_venta YA incluye IVA (calculado con tasa efectiva 14.91%)
        // Ejemplo: precio_compra 100 + 50% ganancia = 150 × 1.1491 = 172.365
        
        // Calcular PRECIO FACTURA (lo que paga el cliente)
        $precioFactura = 0;
        foreach ($productos as $prod) {
            $producto = Producto::find($prod['producto_id']);
            $precioFactura += $producto->precio_venta * $prod['cantidad'];
        }

        // Calcular impuestos para DECLARACIÓN (no se cobran aparte al cliente):
        // 1. IVA (13%) - desglosado del precio factura para declarar al SIN
        $iva = $precioFactura * 0.13;
        
        // 2. IT (3%) - responsabilidad del negocio, NO se cobra al cliente
        $it = $precioFactura * 0.03;
        
        // 3. Total que paga el cliente = Precio Factura (ya incluye IVA)
        $subtotal = $precioFactura;
        $total = $precioFactura;

        // Crear venta
        $venta = Venta::create([
            'cliente_id' => $cliente_id,
            'usuario_id' => 1,
            'fecha_hora' => $fecha . ' 10:00:00',
            'tipo_comprobante' => 'factura',
            'subtotal' => $subtotal,
            'iva' => $iva,
            'it' => $it,
            'impuesto' => 0,
            'descuento' => 0,
            'total' => $total,
            'tipo_pago' => 'efectivo',
            'observaciones' => 'Venta de prueba',
            'estado_contable' => 'contabilizado',
        ]);

        // Crear detalles
        foreach ($productos as $prod) {
            $producto = Producto::find($prod['producto_id']);
            $precioUnitario = $producto->precio_venta;
            $subtotalDetalle = $precioUnitario * $prod['cantidad'];

            Detalle_venta::create([
                'venta_id' => $venta->id,
                'producto_id' => $prod['producto_id'],
                'cantidad' => $prod['cantidad'],
                'precio_unitario' => $precioUnitario,
                'subtotal' => $subtotalDetalle,
            ]);
        }

        // TODO: Contabilizar venta cuando el método esté disponible
        // $this->contabilidadService->contabilizarVenta($venta);

        echo "✓ Venta V-" . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . " creada con " . count($productos) . " productos (Total: Bs. " . number_format($total, 2) . ")\n";
    }
}
