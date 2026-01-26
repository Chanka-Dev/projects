@extends('adminlte::page')

@section('title', 'Detalle de Cita')

@section('content_header')
    <h1>Detalle de Cita #{{ $cita->id }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Cita</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Cliente:</strong> {{ $cita->cliente->nombre }}</p>
                    <p><strong>Teléfono:</strong> {{ $cita->cliente->telefono }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trabajadora:</strong> {{ $cita->trabajadora->nombre }}</p>
                    <p><strong>Especialidad:</strong> {{ $cita->trabajadora->especialidad }}</p>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> {{ $cita->fecha->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong> 
                        @if($cita->estado === 'pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                        @elseif($cita->estado === 'confirmada')
                            <span class="badge badge-info">Confirmada</span>
                        @elseif($cita->estado === 'completada')
                            <span class="badge badge-success">Completada</span>
                        @elseif($cita->estado === 'cancelada')
                            <span class="badge badge-danger">Cancelada</span>
                        @endif
                    </p>
                </div>
            </div>
            
            <hr>
            
            <p><strong>Servicios Solicitados:</strong></p>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalServicios = 0; @endphp
                    @foreach($cita->servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->nombre }}</td>
                            <td>Bs {{ number_format($servicio->pivot->precio_aplicado, 2) }}</td>
                        </tr>
                        @php $totalServicios += $servicio->pivot->precio_aplicado; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>Bs {{ number_format($totalServicios, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
            
            @if($cita->observaciones)
                <hr>
                <p><strong>Observaciones:</strong></p>
                <div class="alert alert-info">
                    {{ $cita->observaciones }}
                </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@endsection