@extends('layouts.app')

@section('title', 'Ventas')

@section('page_header')
    <h1><i class="fas fa-shopping-cart"></i> Gestión de Ventas</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Ventas</h3>
            <div class="card-tools">
                <a href="{{ route('ventas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Venta
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ventas.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="fecha_desde" class="form-control" placeholder="Desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha_hasta" class="form-control" placeholder="Hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por cliente..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">IVA</th>
                            <th class="text-right">IT</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y H:i') }}</td>
                                <td>{{ $venta->cliente->nombre }}</td>
                                <td>
                                    <span class="badge badge-{{ $venta->tipo_comprobante == 'factura' ? 'success' : 'info' }}">
                                        {{ ucfirst($venta->tipo_comprobante) }}
                                    </span>
                                </td>
                                <td class="text-right">{{ formatearBs($venta->subtotal) }}</td>
                                <td class="text-right">{{ formatearBs($venta->iva) }}</td>
                                <td class="text-right">{{ formatearBs($venta->it) }}</td>
                                <td class="text-right"><strong>{{ formatearBs($venta->total) }}</strong></td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-secondary btn-sm" title="Imprimir">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de eliminar esta venta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No se encontraron ventas</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <th colspan="3" class="text-right">TOTALES:</th>
                            <th class="text-right">{{ formatearBs($ventas->sum('subtotal')) }}</th>
                            <th class="text-right">{{ formatearBs($ventas->sum('iva')) }}</th>
                            <th class="text-right">{{ formatearBs($ventas->sum('it')) }}</th>
                            <th class="text-right">{{ formatearBs($ventas->sum('total')) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $ventas->links() }}
        </div>
    </div>
@stop
