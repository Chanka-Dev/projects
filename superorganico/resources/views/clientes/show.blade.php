@extends('layouts.app')

@section('title', 'Detalle Cliente')

@section('content_header')
    <h1>Detalle del Cliente</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Cliente</h3>
                <div class="card-tools">
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th style="width: 150px">ID:</th>
                            <td>{{ $cliente->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $cliente->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Tipo:</th>
                            <td>
                                @if($cliente->tipo == 'persona')
                                    <span class="badge badge-info">Persona Natural</span>
                                @else
                                    <span class="badge badge-primary">Empresa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>NIT:</th>
                            <td>{{ $cliente->nit ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>CI:</th>
                            <td>{{ $cliente->ci ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $cliente->telefono }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $cliente->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dirección:</th>
                            <td>{{ $cliente->direccion ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Ciudad:</th>
                            <td>{{ $cliente->ciudad ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>País:</th>
                            <td>{{ $cliente->pais ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($cliente->activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Registrado:</th>
                            <td>{{ formatearFechaHora($cliente->created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Estadísticas de Ventas</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-shopping-bag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Compras</span>
                                <span class="info-box-number">{{ $cliente->ventas->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Monto Total</span>
                                <span class="info-box-number">{{ formatearBs($cliente->ventas->sum('total')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">Últimas Compras</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Total</th>
                                <th>Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->ventas()->latest('fecha_hora')->limit(5)->get() as $venta)
                            <tr>
                                <td>{{ formatearFechaHora($venta->fecha_hora) }}</td>
                                <td>
                                    @if($venta->tipo_comprobante == 'factura')
                                        <span class="badge badge-primary">Factura</span>
                                    @else
                                        <span class="badge badge-secondary">Nota Venta</span>
                                    @endif
                                </td>
                                <td>{{ formatearBs($venta->total) }}</td>
                                <td>
                                    @if($venta->tipo_pago == 'efectivo')
                                        <span class="badge badge-success">Efectivo</span>
                                    @elseif($venta->tipo_pago == 'tarjeta')
                                        <span class="badge badge-info">Tarjeta</span>
                                    @else
                                        <span class="badge badge-warning">QR</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay compras registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
