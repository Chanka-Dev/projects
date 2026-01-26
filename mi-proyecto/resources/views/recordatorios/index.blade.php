@extends('adminlte::page')

@section('title', 'Lista de Recordatorios')

@section('content_header')
    <h1>Lista de Recordatorios</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('recordatorios.create') }}" class="btn btn-primary">Nuevo Recordatorio</a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('recordatorios.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por cliente, mensaje o método" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cita</th>
                        <th>Cliente</th>
                        <th>Mensaje</th>
                        <th>Fecha de Envío</th>
                        <th>Método de Envío</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recordatorios as $index => $recordatorio)
                        <tr>
                            <td>{{ $recordatorios->firstItem() + $index }}</td>
                            <td>{{ $recordatorio->cita->id }} ({{ $recordatorio->cita->fecha->format('d/m/Y') }})</td>
                            <td>{{ $recordatorio->cita->cliente->nombre }}</td>
                            <td>{{ Str::limit($recordatorio->mensaje, 30) }}</td>
                            <td>{{ $recordatorio->fecha_envio->format('d/m/Y H:i') }}</td>
                            <td>{{ $recordatorio->tipo }}</td>
                            <td>{{ $recordatorio->estado }}</td>
                            <td>
                                <a href="{{ route('recordatorios.show', $recordatorio) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('recordatorios.edit', $recordatorio) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('recordatorios.destroy', $recordatorio) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $recordatorios->links() }}
        </div>
    </div>
@endsection