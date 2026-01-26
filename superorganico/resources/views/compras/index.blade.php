@extends('layouts.app')

@section('title', 'Compras')

@section('page_header')
    <h1><i class="fas fa-truck"></i> Gestión de Compras</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Compras</h3>
            <div class="card-tools">
                <a href="{{ route('compras.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nueva Compra
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('compras.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar..." value="{{ request('buscar') }}">
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
                            <th>Proveedor</th>
                            <th>Nº Factura</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Crédito Fiscal</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td>{{ $compra->fecha->format('d/m/Y') }}</td>
                                <td>{{ $compra->proveedor->nombre }}</td>
                                <td><code>{{ $compra->numero_factura }}</code></td>
                                <td class="text-right">{{ formatearBs($compra->total) }}</td>
                                <td class="text-right">{{ formatearBs($compra->credito_fiscal) }}</td>
                                <td class="text-center">
                                    @if($compra->estado == 'pendiente')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @elseif($compra->estado == 'recibida')
                                        <span class="badge badge-success">Recibida</span>
                                    @else
                                        <span class="badge badge-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('compras.show', $compra->id) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('compras.edit', $compra->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($compra->estado == 'pendiente')
                                        <form action="{{ route('compras.recibir', $compra->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Recibir">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('compras.destroy', $compra->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de eliminar esta compra?');">
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
                                <td colspan="7" class="text-center text-muted">No se encontraron compras</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $compras->links() }}
        </div>
    </div>
@stop
