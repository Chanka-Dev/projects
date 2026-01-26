@extends('adminlte::page')

@section('title', 'Detalle de Recordatorio')

@section('content_header')
    <h1>Detalle de Recordatorio #{{ $recordatorio->id }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $recordatorio->id }}</p>
            <p><strong>Cita:</strong> {{ $recordatorio->cita->id }} ({{ $recordatorio->cita->fecha->format('d/m/Y') }} {{ $recordatorio->cita->hora_inicio->format('H:i') }})</p>
            <p><strong>Cliente:</strong> {{ $recordatorio->cita->cliente->nombre }}</p>
            <p><strong>Mensaje:</strong> {{ $recordatorio->mensaje }}</p>
            <p><strong>Fecha de Envío:</strong> {{ $recordatorio->fecha_envio->format('d/m/Y H:i') }}</p>
            <p><strong>Método de Envío:</strong> {{ $recordatorio->tipo }}</p>
            <p><strong>Estado:</strong> {{ $recordatorio->estado }}</p>
            <a href="{{ route('recordatorios.edit', $recordatorio) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('recordatorios.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection