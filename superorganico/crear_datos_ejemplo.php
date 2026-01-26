#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Producto;
use App\Models\Plan_cuenta;

echo "Creando productos de ejemplo...\n";

// Obtener cuentas contables
$cuentaInv = Plan_cuenta::where('codigo', 'LIKE', '115%')->first();
$cuentaCosto = Plan_cuenta::where('codigo', 'LIKE', '51%')->first();
$cuentaIngreso = Plan_cuenta::where('codigo', 'LIKE', '41%')->first();

if (!$cuentaInv || !$cuentaCosto || !$cuentaIngreso) {
    echo "Error: No se encontraron las cuentas necesarias\n";
    exit(1);
}

// Crear productos
$productos = [
    [
        'codigo' => 'VER001',
        'nombre' => 'Lechuga Orgánica',
        'descripcion' => 'Lechuga fresca orgánica',
        'categoria' => 'Verduras',
        'tipo' => 'verdura',
        'unidad_medida' => 'unidad',
        'precio_compra' => 3.50,
        'precio_venta' => 7.00,
        'stock_minimo' => 20,
        'dias_caducidad' => 7,
    ],
    [
        'codigo' => 'FRU001',
        'nombre' => 'Manzana Orgánica',
        'descripcion' => 'Manzana roja orgánica',
        'categoria' => 'Frutas',
        'tipo' => 'fruta',
        'unidad_medida' => 'kg',
        'precio_compra' => 15.00,
        'precio_venta' => 28.00,
        'stock_minimo' => 50,
        'dias_caducidad' => 30,
    ],
    [
        'codigo' => 'GRA001',
        'nombre' => 'Quinua Orgánica',
        'descripcion' => 'Quinua real del Altiplano',
        'categoria' => 'Granos',
        'tipo' => 'grano',
        'unidad_medida' => 'kg',
        'precio_compra' => 18.00,
        'precio_venta' => 32.00,
        'stock_minimo' => 100,
        'dias_caducidad' => 365,
    ],
];

foreach ($productos as $prodData) {
    $prod = Producto::firstOrCreate(
        ['codigo' => $prodData['codigo']],
        array_merge($prodData, [
            'stock_actual' => 0,
            'requiere_lote' => true,
            'dias_alerta_vencimiento' => 7,
            'perecedero' => true,
            'activo' => true,
            'cuenta_inventario_id' => $cuentaInv->id,
            'cuenta_costo_venta_id' => $cuentaCosto->id,
            'cuenta_ingreso_id' => $cuentaIngreso->id,
        ])
    );
    echo "✓ Producto creado: {$prod->nombre}\n";
}

echo "\nTotal productos: " . Producto::count() . "\n";
echo "Listo!\n";
