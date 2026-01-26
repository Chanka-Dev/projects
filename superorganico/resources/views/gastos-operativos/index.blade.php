@extends('layouts.app')

@section('title', 'Gastos Operativos')

@section('content_header')
    <h1><i class="fas fa-money-bill-wave"></i> Gastos Operativos</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Control de Gastos</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('gastos-operativos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Gasto
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label>Fecha Desde:</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ $fecha_desde }}">
                </div>
                <div class="col-md-4">
                    <label>Fecha Hasta:</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ $fecha_hasta }}">
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <div class="alert alert-info">
            <strong>Total de Gastos del Período:</strong> {{ formatearBs($totalGastos) }}
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 100px;">Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Comprobante</th>
                        <th class="text-right">Monto</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gastos as $gasto)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($gasto->fecha_gasto)->format('d/m/Y') }}</td>
                        <td>{{ $gasto->descripcion }}</td>
                        <td>
                            @if($gasto->categoria)
                                <span class="badge badge-secondary">{{ $gasto->categoria->nombre }}</span>
                            @else
                                <span class="text-muted">Sin categoría</span>
                            @endif
                        </td>
                        <td>{{ $gasto->proveedor->nombre ?? '-' }}</td>
                        <td>{{ $gasto->tipo_comprobante ?? '-' }}</td>
                        <td class="text-right"><strong>{{ formatearBs($gasto->monto) }}</strong></td>
                        <td class="text-center">
                            @if($gasto->asiento_id)
                                <span class="badge badge-success">Contabilizado</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('gastos-operativos.show', $gasto) }}" class="btn btn-info btn-sm" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$gasto->asiento_id)
                            <a href="{{ route('gastos-operativos.edit', $gasto) }}" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('gastos-operativos.destroy', $gasto) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este gasto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay gastos registrados en este período</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $gastos->links() }}
        </div>
    </div>
</div>
@stop
