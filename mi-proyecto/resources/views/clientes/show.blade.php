@extends('adminlte::page')

@section('title', 'Perfil de Cliente')

@section('content_header')
    <h1>Perfil de {{ $cliente->nombre }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $cliente->id }}</p>
            <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
            <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
            <p><strong>Servicios Totales:</strong> {{ $cliente->servicios_totales }}</p>
            <p><strong>Total Gastado:</strong> {{ number_format($cliente->total_gastado, 2) }}</p>
            <p><strong>Fecha de Registro:</strong> {{ $cliente->fecha_registro ? $cliente->fecha_registro->format('d/m/Y') : 'N/A' }}</p>
            <p><strong>Última Visita:</strong> {{ $cliente->ultima_visita ? $cliente->ultima_visita->format('d/m/Y') : 'N/A' }}</p>
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection