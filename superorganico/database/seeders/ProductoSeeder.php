<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['nombre' => 'Manzana Orgánica', 'categoria' => 'frutas', 'precio_compra' => 20.00, 'precio_venta' => 25.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 15, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Banana Orgánica', 'categoria' => 'frutas', 'precio_compra' => 15.00, 'precio_venta' => 18.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 7, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Naranja Orgánica', 'categoria' => 'frutas', 'precio_compra' => 16.00, 'precio_venta' => 20.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 12, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Lechuga Orgánica', 'categoria' => 'verduras', 'precio_compra' => 8.00, 'precio_venta' => 12.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 5, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Tomate Orgánico', 'categoria' => 'verduras', 'precio_compra' => 18.00, 'precio_venta' => 22.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 10, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Zanahoria Orgánica', 'categoria' => 'verduras', 'precio_compra' => 12.00, 'precio_venta' => 16.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 20, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Quinua Orgánica', 'categoria' => 'granos', 'precio_compra' => 28.00, 'precio_venta' => 35.00, 'stock' => 0, 'requiere_lote' => false, 'dias_caducidad' => 365, 'perecedero' => false, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Arroz Integral Orgánico', 'categoria' => 'granos', 'precio_compra' => 22.00, 'precio_venta' => 28.00, 'stock' => 0, 'requiere_lote' => false, 'dias_caducidad' => 365, 'perecedero' => false, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Leche Orgánica', 'categoria' => 'lacteos', 'precio_compra' => 12.00, 'precio_venta' => 15.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 5, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
            ['nombre' => 'Yogurt Orgánico Natural', 'categoria' => 'lacteos', 'precio_compra' => 20.00, 'precio_venta' => 25.00, 'stock' => 0, 'requiere_lote' => true, 'dias_caducidad' => 10, 'perecedero' => true, 'cuenta_inventario_id' => 9, 'cuenta_costo_venta_id' => 40, 'cuenta_ingreso_id' => 35],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        echo "✓ 10 productos creados\n";
    }
}
