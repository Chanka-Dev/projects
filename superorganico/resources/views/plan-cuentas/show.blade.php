@extends('layouts.app')

@section('title', 'Detalle de Cuenta')

@section('content_header')
    <h1><i class="fas fa-list-alt"></i> Detalle de Cuenta Contable</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-info">
        <h3 class="card-title">{{ $planCuenta->codigo }} - {{ $planCuenta->nombre }}</h3>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Código:</th>
                        <td><strong>{{ $planCuenta->codigo }}</strong></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $planCuenta->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Tipo:</th>
                        <td>
                            @if($planCuenta->tipo_cuenta == 'activo')
                                <span class="badge badge-success">Activo</span>
                            @elseif($planCuenta->tipo_cuenta == 'pasivo')
                                <span class="badge badge-danger">Pasivo</span>
                            @elseif($planCuenta->tipo_cuenta == 'patrimonio')
                                <span class="badge badge-primary">Patrimonio</span>
                            @elseif($planCuenta->tipo_cuenta == 'ingreso')
                                <span class="badge badge-info">Ingreso</span>
                            @else
                                <span class="badge badge-warning">Egreso</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Naturaleza:</th>
                        <td>{{ ucfirst($planCuenta->naturaleza) }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Nivel:</th>
                        <td>{{ $planCuenta->nivel }}</td>
                    </tr>
                    <tr>
                        <th>Acepta Movimientos:</th>
                        <td>
                            @if($planCuenta->acepta_movimientos)
                                <span class="badge badge-success">Sí</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @if($planCuenta->activa)
                                <span class="badge badge-success">Activa</span>
                            @else
                                <span class="badge badge-secondary">Inactiva</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Saldo Actual:</th>
                        <td><strong>{{ formatearBs($saldo) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        @if($planCuenta->cuentasHijas->count() > 0)
        <hr>
        <h5>Subcuentas ({{ $planCuenta->cuentasHijas->count() }})</h5>
        <ul>
            @foreach($planCuenta->cuentasHijas as $hija)
                <li>{{ $hija->codigo }} - {{ $hija->nombre }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <div class="card-footer">
        <a href="{{ route('plan-cuentas.edit', $planCuenta) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('plan-cuentas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@stop
