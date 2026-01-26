<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido #{{ $pedido->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nota de Venta</h1>
        <h2>Distribuidora</h2>
    </div>

    <div class="info">
        <p><strong>Pedido ID:</strong> {{ $pedido->id }}</p>
        <p><strong>Cliente:</strong> {{ $pedido->user->name }} {{ $pedido->user->apellido }}</p>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($pedido->estado) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($pedido->productos as $item)
                @php 
                    $subtotal = $item->cantidad * $item->precio_unitario;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->producto->nombre }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>Bs {{ number_format($item->precio_unitario, 2) }}</td>
                    <td>Bs {{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Total</td>
                <td>Bs {{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="info">
        <h3>Detalles de Entrega</h3>
        <p>{{ $pedido->detalle_pedido }}</p>
    </div>
</body>
</html>
