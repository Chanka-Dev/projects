@extends('adminlte::page')

@section('title', 'Pagos a Trabajadoras')

@section('content_header')
    <h1>Gestión de Pagos a Trabajadoras</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    {{-- Comisiones Pendientes de Generar Pago --}}
    @if($comisionesPendientes->count() > 0)
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Comisiones Pendientes de Agrupar en Pago</h3>
            <div class="card-tools">
                <span class="badge badge-warning">{{ $estadisticas['servicios_pendientes'] }} servicio(s) - Bs {{ number_format($estadisticas['total_pendiente'], 2) }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Trabajadora</th>
                        <th>Servicios</th>
                        <th>Periodo</th>
                        <th>Total Comisiones</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comisionesPendientes as $item)
                        <tr>
                            <td><strong>{{ $item['trabajadora']->nombre }}</strong></td>
                            <td>{{ $item['total_servicios'] }} servicio(s)</td>
                            <td>{{ \Carbon\Carbon::parse($item['fecha_inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item['fecha_fin'])->format('d/m/Y') }}</td>
                            <td class="font-weight-bold text-success">Bs {{ number_format($item['total_comisiones'], 2) }}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalDetalle{{ $item['trabajadora']->id }}">
                                    <i class="fas fa-eye"></i> Ver Detalle
                                </button>
                                <form action="{{ route('pago-trabajadoras.generar-rapido', $item['trabajadora']->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('¿Generar pago para {{ $item['trabajadora']->nombre }}?')">
                                        <i class="fas fa-money-bill-wave"></i> Generar Pago
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modales de Detalle --}}
    @foreach($comisionesPendientes as $item)
    <div class="modal fade" id="modalDetalle{{ $item['trabajadora']->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Detalle de Servicios - {{ $item['trabajadora']->nombre }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Precio</th>
                                <th>Comisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item['servicios'] as $indexServ => $servicio)
                                <tr>
                                    <td>{{ $indexServ + 1 }}</td>
                                    <td>{{ $servicio->fecha_servicio->format('d/m/Y') }}</td>
                                    <td>{{ $servicio->cita->cliente->nombre }}</td>
                                    <td>{{ $servicio->servicio->nombre }}</td>
                                    <td>Bs {{ number_format($servicio->precio_cobrado, 2) }}</td>
                                    <td class="font-weight-bold">Bs {{ number_format($servicio->monto_comision, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                <td class="font-weight-bold text-success">Bs {{ number_format($item['total_comisiones'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <form action="{{ route('pago-trabajadoras.generar-rapido', $item['trabajadora']->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="return confirm('¿Generar pago para {{ $item['trabajadora']->nombre }}?')">
                            <i class="fas fa-money-bill-wave"></i> Generar Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Pagos Generados --}}
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-list"></i> Historial de Pagos Generados</h3>
            <div class="card-tools">
                <a href="{{ route('pago-trabajadoras.create') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-plus"></i> Nuevo Pago Manual
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($pagos->count() > 0)
            <!-- Filtros -->
            <form method="GET" action="{{ route('pago-trabajadoras.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small">Trabajadora</label>
                        <select name="trabajadora_id" class="form-control">
                            <option value="">Todas las trabajadoras</option>
                            @foreach($trabajadoras as $trabajadora)
                                <option value="{{ $trabajadora->id }}" {{ request('trabajadora_id') == $trabajadora->id ? 'selected' : '' }}>
                                    {{ $trabajadora->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small">Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="small">Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small">&nbsp;</label>
                        <div class="btn-group d-block">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <a href="{{ route('pago-trabajadoras.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros rápidos -->
                <div class="row mt-2">
                    <div class="col-md-12">
                        <small class="text-muted">Filtros rápidos:</small>
                        <div class="btn-group btn-group-sm ml-2">
                            <a href="{{ route('pago-trabajadoras.index', ['fecha_desde' => \Carbon\Carbon::now()->startOfWeek(), 'fecha_hasta' => \Carbon\Carbon::now()->endOfWeek()]) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-calendar"></i> Esta semana
                            </a>
                            <a href="{{ route('pago-trabajadoras.index', ['fecha_desde' => \Carbon\Carbon::now()->subWeek()->startOfWeek(), 'fecha_hasta' => \Carbon\Carbon::now()->subWeek()->endOfWeek()]) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-calendar"></i> Semana pasada
                            </a>
                            <a href="{{ route('pago-trabajadoras.index', ['fecha_desde' => \Carbon\Carbon::now()->startOfMonth(), 'fecha_hasta' => \Carbon\Carbon::now()->endOfMonth()]) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-calendar-alt"></i> Este mes
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Trabajadora</th>
                            <th>Periodo</th>
                            <th>Total Servicios</th>
                            <th>Total Comisiones</th>
                            <th>Monto Pagado</th>
                            <th>Estado</th>
                            <th>Fecha Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $index => $pago)
                            <tr>
                                <td>{{ $pagos->firstItem() + $index }}</td>
                                <td>{{ $pago->trabajadora->nombre }}</td>
                                <td>
                                    {{ $pago->fecha_inicio_periodo->format('d/m/Y') }} - 
                                    {{ $pago->fecha_fin_periodo->format('d/m/Y') }}
                                </td>
                                <td>Bs {{ number_format($pago->total_servicios, 2) }}</td>
                                <td>Bs {{ number_format($pago->total_comisiones, 2) }}</td>
                                <td>Bs {{ number_format($pago->monto_pagado, 2) }}</td>
                                <td>
                                    @if($pago->estado == 'pendiente')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @elseif($pago->estado == 'pagado')
                                        <span class="badge badge-success">Pagado</span>
                                    @else
                                        <span class="badge badge-danger">Cancelado</span>
                                    @endif
                                </td>
                                <td>{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('pago-trabajadoras.show', $pago) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($pago->estado == 'pendiente')
                                        <a href="{{ route('pago-trabajadoras.edit', $pago) }}" class="btn btn-sm btn-primary" title="Registrar Pago">
                                            <i class="fas fa-dollar-sign"></i>
                                        </a>
                                        <form action="{{ route('pago-trabajadoras.destroy', $pago) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este pago?')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No hay pagos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <div class="text-center p-4 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No hay pagos generados aún</p>
                </div>
            @endif
            </div>
            
            @if($pagos->count() > 0)
            <div class="mt-3">
                {{ $pagos->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
