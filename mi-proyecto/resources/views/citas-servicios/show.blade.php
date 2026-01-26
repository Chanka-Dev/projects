@extends('adminlte::page')

@section('title', 'Detalle de Cita-Servicio')

@section('content_header')
    <h1>Detalle de Cita-Servicio #{{ $citaServicio->id }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $citaServicio->id }}</p>
            <p><strong>Cita:</strong> {{ $citaServicio->cita->id }} ({{ $citaServicio->cita->fecha->format('d/m/Y') }} {{ $citaServicio->cita->hora_inicio->format('H:i') }})</p>
            <p><strong>Cliente:</strong> {{ $citaServicio->cita->cliente->nombre }}</p>
            <p><strong>Servicio:</strong> {{ $citaServicio->servicio->nombre }}</p>
            <p><strong>Precio Aplicado:</strong> {{ number_format($citaServicio->precio_aplicado, 2) }}</p>
            <a href="{{ route('citas-servicios.edit', $citaServicio) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('citas-servicios.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection