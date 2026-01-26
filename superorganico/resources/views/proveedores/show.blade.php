@extends('layouts.app')

@section('title', 'Detalle Proveedor')

@section('content_header')
    <h1>Detalle del Proveedor</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Proveedor</h3>
                <div class="card-tools">
                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th style="width: 150px">ID:</th>
                            <td>{{ $proveedor->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $proveedor->nombre }}</td>
                        </tr>
                        <tr>
                            <th>NIT:</th>
                            <td>{{ $proveedor->nit ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>CI:</th>
                            <td>{{ $proveedor->ci ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $proveedor->telefono }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $proveedor->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dirección:</th>
                            <td>{{ $proveedor->direccion ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Ciudad:</th>
                            <td>{{ $proveedor->ciudad ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>País:</th>
                            <td>{{ $proveedor->pais ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($proveedor->activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Registrado:</th>
                            <td>{{ formatearFechaHora($proveedor->created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Estadísticas de Compras</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Compras</span>
                                <span class="info-box-number">{{ $proveedor->compras->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Monto Total</span>
                                <span class="info-box-number">{{ formatearBs($proveedor->compras->sum('total')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">Últimas Compras</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>N° Compra</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proveedor->compras()->latest()->limit(5)->get() as $compra)
                            <tr>
                                <td>{{ $compra->numero_compra }}</td>
                                <td>{{ formatearFecha($compra->fecha_compra) }}</td>
                                <td>{{ formatearBs($compra->total) }}</td>
                                <td>
                                    @if($compra->estado == 'recibida')
                                        <span class="badge badge-success">Recibida</span>
                                    @elseif($compra->estado == 'pendiente')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @else
                                        <span class="badge badge-danger">Cancelada</span>
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
