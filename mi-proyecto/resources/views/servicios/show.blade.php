@extends('adminlte::page')

@section('title', 'Detalle de Servicio')

@section('content_header')
    <h1>Detalle de {{ $servicio->nombre }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $servicio->id }}</p>
            <p><strong>Nombre:</strong> {{ $servicio->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $servicio->descripcion ?? 'N/A' }}</p>
            <p><strong>Precio Base:</strong> Bs {{ number_format($servicio->precio_base, 2) }}</p>
            <p><strong>Comisión:</strong> Bs {{ number_format($servicio->monto_comision, 2) }}</p>
            <p><strong>Activo:</strong> 
                @if($servicio->activo)
                    <span class="badge badge-success">Sí</span>
                @else
                    <span class="badge badge-danger">No</span>
                @endif
            </p>
            <hr>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Información de comisión:</strong> Por cada servicio de Bs {{ number_format($servicio->precio_base, 2) }}, 
                la trabajadora recibirá Bs {{ number_format($servicio->monto_comision, 2) }} de comisión fija.
            </div>
            <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection