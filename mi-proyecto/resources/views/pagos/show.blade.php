@extends('adminlte::page')

@section('title', 'Detalle de Pago')

@section('content_header')
    <h1>Detalle de Pago #{{ $pago->id }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $pago->id }}</p>
            <p><strong>Cliente:</strong> {{ $pago->cita->cliente->nombre }}</p>
            <p><strong>Cita:</strong> {{ $pago->cita->id }} ({{ $pago->cita->fecha->format('d/m/Y') }} {{ $pago->cita->hora_inicio->format('H:i') }})</p>
            <p><strong>Monto Total:</strong> {{ number_format($pago->monto_total, 2) }}</p>
            <p><strong>Método de Pago:</strong> {{ $pago->metodo_pago }}</p>
            <p><strong>Estado:</strong> {{ $pago->estado }}</p>
            <p><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago->format('d/m/Y') }}</p>
            <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection