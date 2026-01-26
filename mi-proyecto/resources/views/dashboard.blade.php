@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><i class="fas fa-spa text-purple"></i> Panel de Control</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
    <style>
        .info-box-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .info-box-purple .info-box-icon {
            background: rgba(255,255,255,0.2);
        }
        .small-box.bg-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        .card-purple .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
    </style>
@endsection

@section('content')
    {{-- Resumen de Hoy --}}
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-calendar-day"></i> Resumen de Hoy - {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</h5>
                <p>Tienes <strong>{{ $citasHoy->count() }}</strong> cita(s) programada(s) para hoy con ingresos de <strong>${{ number_format($ingresosHoy, 2) }}</strong></p>
            </div>
        </div>
    </div>

    {{-- KPIs Principales --}}
    <div class="row">
        <!-- Ingresos del Mes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-purple">
                <div class="inner">
                    <h3>${{ number_format($ingresosMes, 0) }}</h3>
                    <p>Ingresos del Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('pagos.index') }}" class="small-box-footer">Ver detalle <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Citas del Mes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $citasCompletadasMes }}<sup style="font-size: 20px">/{{ $citasMes }}</sup></h3>
                    <p>Citas Completadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href="{{ route('citas.index') }}" class="small-box-footer">Ver citas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Comisiones Pendientes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>${{ number_format($comisionesPendientes, 0) }}</h3>
                    <p>Comisiones por Pagar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <a href="{{ route('pago-trabajadoras.index') }}" class="small-box-footer">Gestionar pagos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Alertas -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $citasSinPago + $pagosPendientes }}</h3>
                    <p>Pagos Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('pagos.index') }}" class="small-box-footer">Revisar <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Citas de Hoy --}}
        <div class="col-md-8">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-day"></i> Citas de Hoy</h3>
                    <div class="card-tools">
                        <span class="badge badge-light">{{ $citasHoy->count() }} cita(s)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($citasHoy->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Trabajadora</th>
                                    <th>Servicios</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasHoy as $cita)
                                    <tr>
                                        <td><strong>{{ $cita->cliente->nombre }}</strong></td>
                                        <td>{{ $cita->trabajadora->nombre }}</td>
                                        <td>
                                            @foreach($cita->servicios as $servicio)
                                                <span class="badge badge-info">{{ $servicio->nombre }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($cita->estado == 'completada')
                                                <span class="badge badge-success">Completada</span>
                                            @elseif($cita->estado == 'pendiente')
                                                <span class="badge badge-warning">Pendiente</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($cita->estado) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>No hay citas programadas para hoy</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Próximas Citas --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Próximas Citas (Próximos 7 días)</h3>
                </div>
                <div class="card-body p-0">
                    @if($citasProximas->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Trabajadora</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasProximas as $cita)
                                    <tr>
                                        <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                        <td>{{ $cita->cliente->nombre }}</td>
                                        <td>{{ $cita->trabajadora->nombre }}</td>
                                        <td>
                                            @if($cita->estado == 'completada')
                                                <span class="badge badge-success">Completada</span>
                                            @elseif($cita->estado == 'pendiente')
                                                <span class="badge badge-warning">Pendiente</span>
                                            @elseif($cita->estado == 'confirmada')
                                                <span class="badge badge-info">Confirmada</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($cita->estado) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('citas.show', $cita) }}" class="btn btn-xs btn-info">Ver</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-3 text-center text-muted">
                            <p class="mb-0">No hay citas programadas en los próximos 7 días</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Estadísticas del Lado Derecho --}}
        <div class="col-md-4">
            {{-- Info Boxes --}}
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Clientes</span>
                    <span class="info-box-number">{{ $totalClientes }}</span>
                </div>
            </div>

            <div class="info-box info-box-purple">
                <span class="info-box-icon"><i class="fas fa-user-tie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Trabajadoras Activas</span>
                    <span class="info-box-number">{{ $totalTrabajadoras }}</span>
                </div>
            </div>

            @if($recordatoriosPendientes > 0)
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-bell"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Recordatorios Próximos</span>
                    <span class="info-box-number">{{ $recordatoriosPendientes }}</span>
                    <a href="{{ route('recordatorios.index') }}" class="text-white"><small>Ver todos →</small></a>
                </div>
            </div>
            @endif

            {{-- Servicios Más Solicitados --}}
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-star"></i> Top Servicios (Este Mes)</h3>
                </div>
                <div class="card-body p-0">
                    @if($serviciosPopulares->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($serviciosPopulares as $servicio)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $servicio->nombre }}
                                    <span class="badge badge-success badge-pill">{{ $servicio->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-center text-muted">
                            <small>No hay datos este mes</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Top Trabajadoras --}}
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h3 class="card-title"><i class="fas fa-trophy"></i> Top Trabajadoras (Este Mes)</h3>
                </div>
                <div class="card-body p-0">
                    @if($trabajadorasTop->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($trabajadorasTop as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->trabajadora->nombre }}
                                    <span class="badge badge-primary badge-pill">{{ $item->total_citas }} citas</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-center text-muted">
                            <small>No hay datos este mes</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas de Pagos Pendientes --}}
    @if($citasSinPago > 0 || $pagosPendientes > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Atención - Pagos Pendientes</h5>
                @if($citasSinPago > 0)
                    <p class="mb-1">• Hay <strong>{{ $citasSinPago }}</strong> cita(s) completada(s) sin registro de pago</p>
                @endif
                @if($pagosPendientes > 0)
                    <p class="mb-1">• Hay <strong>{{ $pagosPendientes }}</strong> pago(s) marcado(s) como pendiente por un total de <strong>${{ number_format($montoPendiente, 2) }}</strong></p>
                @endif
                <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-warning mt-2">
                    <i class="fas fa-dollar-sign"></i> Ir a gestionar pagos
                </a>
            </div>
        </div>
    </div>
    @endif
@endsection