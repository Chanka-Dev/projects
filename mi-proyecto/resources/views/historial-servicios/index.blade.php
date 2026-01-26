@extends('adminlte::page')

@section('title', 'Historial de Servicios')

@section('content_header')
    <h1>Historial de Servicios</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <!-- Tarjetas de estadísticas -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-history"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Servicios</span>
                    <span class="info-box-number">{{ $totalServicios }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ingresos Totales</span>
                    <span class="info-box-number">{{ number_format($totalIngresos, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card principal -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros de Búsqueda</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('historial-servicios.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Buscar</label>
                            <input type="text" name="search" class="form-control" 
                                placeholder="Cliente, servicio, trabajadora..." 
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cliente</label>
                            <select name="cliente_id" class="form-control">
                                <option value="">Todos los clientes</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" 
                                        {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Fecha Desde</label>
                            <input type="date" name="fecha_desde" class="form-control" 
                                value="{{ request('fecha_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Fecha Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control" 
                                value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('historial-servicios.index') }}" class="btn btn-secondary btn-block mt-1">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de historial -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registros de Servicios</h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ $historialServicios->total() }} registros</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($historialServicios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Trabajadora</th>
                                <th>Precio</th>
                                <th>Método Pago</th>
                                <th style="width: 120px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historialServicios as $historial)
                                <tr>
                                    <td>
                                        <strong>{{ $historial->fecha_servicio->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $historial->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $historial->cliente->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $historial->cliente->telefono }}</small>
                                    </td>
                                    <td>{{ $historial->servicio->nombre }}</td>
                                    <td>{{ $historial->trabajadora->nombre }}</td>
                                    <td>
                                        <span class="badge badge-success">
                                            ${{ number_format($historial->precio_cobrado, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($historial->metodo_pago == 'efectivo')
                                            <span class="badge badge-info">
                                                <i class="fas fa-money-bill"></i> Efectivo
                                            </span>
                                        @elseif($historial->metodo_pago == 'tarjeta')
                                            <span class="badge badge-primary">
                                                <i class="fas fa-credit-card"></i> Tarjeta
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-exchange-alt"></i> Transferencia
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('historial-servicios.show', $historial) }}" 
                                                class="btn btn-sm btn-outline-info d-flex align-items-center justify-content-center" 
                                                title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="4" class="text-right"><strong>Total en esta página:</strong></td>
                                <td colspan="3">
                                    <strong class="text-success">
                                        ${{ number_format($historialServicios->sum('precio_cobrado'), 2) }}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No se encontraron registros en el historial</p>
                    <small>Los servicios aparecerán aquí automáticamente cuando se completen citas o pagos</small>
                </div>
            @endif
        </div>
        @if($historialServicios->hasPages())
            <div class="card-footer">
                {{ $historialServicios->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
@endsection

@section('css')
    <style>
        .info-box {
            min-height: 80px;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endsection