@extends('layouts.app')

@section('title', 'Alertas de Inventario')

@section('page_header')
    <h1><i class="fas fa-exclamation-triangle"></i> Alertas de Inventario</h1>
@stop

@section('page_content')
    {{-- Productos por vencer --}}
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-times"></i> Productos Próximos a Vencer</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Fecha Vencimiento</th>
                            <th>Días Restantes</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lotesProximosVencer as $lote)
                            <tr>
                                <td>{{ $lote->producto->nombre }}</td>
                                <td><code>{{ $lote->numero_lote }}</code></td>
                                <td>{{ $lote->cantidad_actual }}</td>
                                <td>{{ \Carbon\Carbon::parse($lote->fecha_caducidad)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $dias = now()->diffInDays($lote->fecha_caducidad, false);
                                    @endphp
                                    {{ $dias }} días
                                </td>
                                <td>
                                    @if($dias < 0)
                                        <span class="badge badge-danger">Vencido</span>
                                    @elseif($dias <= 3)
                                        <span class="badge badge-danger">Crítico</span>
                                    @elseif($dias <= 7)
                                        <span class="badge badge-warning">Urgente</span>
                                    @else
                                        <span class="badge badge-info">Próximo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay productos próximos a vencer</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Stock bajo --}}
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-box-open"></i> Productos con Stock Bajo</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosStockBajo as $producto)
                            <tr>
                                <td><code>{{ $producto->codigo }}</code></td>
                                <td>{{ $producto->nombre }}</td>
                                <td class="text-right">{{ $producto->stock_actual }}</td>
                                <td class="text-right">{{ $producto->stock_minimo }}</td>
                                <td>
                                    @if($producto->stock_actual == 0)
                                        <span class="badge badge-danger">Sin Stock</span>
                                    @else
                                        <span class="badge badge-warning">Stock Bajo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('compras.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i> Comprar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Todos los productos tienen stock suficiente</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
