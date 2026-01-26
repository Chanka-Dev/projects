@extends('layouts.app')

@section('title', 'Dashboard')

@section('page_header')
    <h1><i class="fas fa-chart-line"></i> Dashboard - SuperOrgánico</h1>
@stop

@section('page_content')
    {{-- Resumen de métricas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ formatearBs($totalVentasHoy) }}</h3>
                    <p>Ventas Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('ventas.index') }}" class="small-box-footer">
                    Ver todas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $productosActivos }}</h3>
                    <p>Productos Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <a href="{{ route('productos.index') }}" class="small-box-footer">
                    Ver productos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 style="color: white;">{{ $productosVencimiento }}</h3>
                    <p style="color: white;">Próximos a Vencer</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('inventario.alertas') }}" class="small-box-footer" style="color: white;">
                    Ver alertas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box {{ $saldoIVA['saldo'] < 0 ? 'bg-success' : 'bg-danger' }}">
                <div class="inner">
                    <h3>{{ formatearBs(abs($saldoIVA['saldo'])) }}</h3>
                    <p>Saldo IVA ({{ $saldoIVA['tipo'] }})</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('reportes.iva') }}" class="small-box-footer" style="color: white;">
                    Ver reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Gráficos y tablas --}}
    <div class="row">
        {{-- Últimas ventas --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-cash-register"></i> Últimas Ventas</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasVentas as $venta)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
                                    <td class="text-right">{{ formatearBs($venta->total) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No hay ventas registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Productos más vendidos --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Productos Más Vendidos</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productosMasVendidos as $item)
                                <tr>
                                    <td>{{ $item->nombre }}</td>
                                    <td class="text-center">{{ $item->total_vendido }}</td>
                                    <td class="text-right">{{ formatearBs($item->total_ingresos) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> Accesos Rápidos</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('ventas.create') }}" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-cart-plus fa-3x mb-2"></i><br>
                                Nueva Venta
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('compras.create') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-truck fa-3x mb-2"></i><br>
                                Nueva Compra
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('productos.create') }}" class="btn btn-info btn-lg btn-block">
                                <i class="fas fa-leaf fa-3x mb-2"></i><br>
                                Nuevo Producto
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('reportes.libro-diario') }}" class="btn btn-warning btn-lg btn-block">
                                <i class="fas fa-book fa-3x mb-2"></i><br>
                                Libro Diario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function formatearBs(monto) {
        return 'Bs. ' + parseFloat(monto).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
</script>
@stop
