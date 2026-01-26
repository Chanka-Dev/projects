@extends('adminlte::page')

@section('title', 'Detalle de Pago')

@section('content_header')
    <h1>Detalle de Pago a Trabajadora</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header bg-info">
                    <h3 class="card-title">Información del Pago</h3>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> #{{ $pagoTrabajadora->id }}</p>
                    <p><strong>Trabajadora:</strong> {{ $pagoTrabajadora->trabajadora->nombre }}</p>
                    <p><strong>Periodo:</strong><br>
                        {{ $pagoTrabajadora->fecha_inicio_periodo->format('d/m/Y') }} - 
                        {{ $pagoTrabajadora->fecha_fin_periodo->format('d/m/Y') }}
                    </p>
                    <hr>
                    <p><strong>Total por Servicios:</strong><br>
                        <span class="h4">${{ number_format($pagoTrabajadora->total_servicios, 2) }}</span>
                    </p>
                    <p><strong>Total Comisiones:</strong><br>
                        <span class="h3 text-success">${{ number_format($pagoTrabajadora->total_comisiones, 2) }}</span>
                    </p>
                    <hr>
                    <p><strong>Monto Pagado:</strong><br>
                        <span class="h4 text-primary">${{ number_format($pagoTrabajadora->monto_pagado, 2) }}</span>
                    </p>
                    <p><strong>Estado:</strong><br>
                        @if($pagoTrabajadora->estado == 'pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                        @elseif($pagoTrabajadora->estado == 'pagado')
                            <span class="badge badge-success">Pagado</span>
                        @else
                            <span class="badge badge-danger">Cancelado</span>
                        @endif
                    </p>
                    @if($pagoTrabajadora->fecha_pago)
                        <p><strong>Fecha de Pago:</strong> {{ $pagoTrabajadora->fecha_pago->format('d/m/Y') }}</p>
                        <p><strong>Método de Pago:</strong> {{ ucfirst($pagoTrabajadora->metodo_pago) }}</p>
                    @endif
                    @if($pagoTrabajadora->observaciones)
                        <p><strong>Observaciones:</strong><br>{{ $pagoTrabajadora->observaciones }}</p>
                    @endif
                </div>
                <div class="card-footer">
                    @if($pagoTrabajadora->estado == 'pendiente')
                        <a href="{{ route('pago-trabajadoras.edit', $pagoTrabajadora) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-dollar-sign"></i> Registrar Pago
                        </a>
                    @endif
                    <a href="{{ route('pago-trabajadoras.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Detalle de Servicios</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Precio</th>
                                <th>Comisión %</th>
                                <th>Monto Comisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagoTrabajadora->historialServicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->fecha_servicio->format('d/m/Y') }}</td>
                                    <td>{{ $servicio->cliente->nombre }}</td>
                                    <td>{{ $servicio->servicio->nombre }}</td>
                                    <td>${{ number_format($servicio->precio_cobrado, 2) }}</td>
                                    <td>{{ number_format($servicio->porcentaje_comision, 2) }}%</td>
                                    <td class="text-success font-weight-bold">
                                        ${{ number_format($servicio->monto_comision, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay servicios registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="5" class="text-right">TOTAL:</th>
                                <th class="text-success">${{ number_format($pagoTrabajadora->total_comisiones, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
