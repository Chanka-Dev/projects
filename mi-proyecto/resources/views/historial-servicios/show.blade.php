@extends('adminlte::page')

@section('title', 'Detalle del Servicio')

@section('content_header')
    <h1>Detalle del Servicio</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i> Información del Servicio
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Cliente:</dt>
                        <dd class="col-sm-8">
                            <strong>{{ $historialServicio->cliente->nombre }}</strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-phone"></i> {{ $historialServicio->cliente->telefono }}
                            </small>
                        </dd>

                        <dt class="col-sm-4">Trabajadora:</dt>
                        <dd class="col-sm-8">{{ $historialServicio->trabajadora->nombre }}</dd>

                        <dt class="col-sm-4">Servicio:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-primary">{{ $historialServicio->servicio->nombre }}</span>
                        </dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Fecha del Servicio:</dt>
                        <dd class="col-sm-8">{{ $historialServicio->fecha_servicio->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Precio Cobrado:</dt>
                        <dd class="col-sm-8">
                            <strong class="text-success">${{ number_format($historialServicio->precio_cobrado, 2) }}</strong>
                        </dd>

                        <dt class="col-sm-4">Método de Pago:</dt>
                        <dd class="col-sm-8">
                            @if($historialServicio->metodo_pago == 'efectivo')
                                <span class="badge badge-info">
                                    <i class="fas fa-money-bill"></i> Efectivo
                                </span>
                            @elseif($historialServicio->metodo_pago == 'tarjeta')
                                <span class="badge badge-primary">
                                    <i class="fas fa-credit-card"></i> Tarjeta
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-exchange-alt"></i> Transferencia
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Registrado:</dt>
                        <dd class="col-sm-8">
                            <small class="text-muted">
                                {{ $historialServicio->created_at->format('d/m/Y H:i') }}
                                <br>
                                ({{ $historialServicio->created_at->diffForHumans() }})
                            </small>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('historial-servicios.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Historial
            </a>
            <a href="{{ route('clientes.show', $historialServicio->cliente) }}" class="btn btn-info">
                <i class="fas fa-user"></i> Ver Cliente
            </a>
        </div>
    </div>
@endsection