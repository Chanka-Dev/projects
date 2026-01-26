@extends('layouts.app')

@section('title', 'Detalle de Gasto')

@section('content_header')
    <h1><i class="fas fa-money-bill-wave"></i> Detalle del Gasto Operativo</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header {{ $gastosOperativo->asiento_id ? 'bg-success' : 'bg-warning' }}">
        <h3 class="card-title">Gasto del {{ \Carbon\Carbon::parse($gastosOperativo->fecha_gasto)->format('d/m/Y') }}</h3>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%;">Fecha:</th>
                        <td>{{ \Carbon\Carbon::parse($gastosOperativo->fecha_gasto)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Monto:</th>
                        <td><strong class="text-danger">{{ formatearBs($gastosOperativo->monto) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Categoría:</th>
                        <td>
                            @if($gastosOperativo->categoria)
                                <span class="badge badge-secondary">{{ $gastosOperativo->categoria->nombre }}</span>
                            @else
                                <span class="text-muted">Sin categoría</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Proveedor:</th>
                        <td>{{ $gastosOperativo->proveedor->nombre ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%;">Tipo Comprobante:</th>
                        <td>{{ $gastosOperativo->tipo_comprobante ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Cuenta Contable:</th>
                        <td>
                            @if($gastosOperativo->cuenta)
                                {{ $gastosOperativo->cuenta->codigo }} - {{ $gastosOperativo->cuenta->nombre }}
                            @else
                                <span class="text-muted">Sin cuenta</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @if($gastosOperativo->asiento_id)
                                <span class="badge badge-success">Contabilizado</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @if($gastosOperativo->asiento_id)
                    <tr>
                        <th>Asiento N°:</th>
                        <td>
                            @if($gastosOperativo->asiento)
                                {{ $gastosOperativo->asiento->numero_asiento }}
                            @else
                                {{ $gastosOperativo->asiento_id }}
                            @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5>Descripción:</h5>
                <div class="alert alert-info">
                    {{ $gastosOperativo->descripcion }}
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        @if(!$gastosOperativo->asiento_id)
        <a href="{{ route('gastos-operativos.edit', $gastosOperativo) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        @endif
        <a href="{{ route('gastos-operativos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@stop
