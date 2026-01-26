@extends('adminlte::page')

@section('title', 'Lista de Clientes')

@section('content_header')
    <h1>Lista de Clientes</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Nuevo Cliente</a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('clientes.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o teléfono" value="{{ request('search') }}">
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
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Servicios Totales</th>
                        <th>Total Gastado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $index => $cliente)
                        <tr>
                            <td>{{ $clientes->firstItem() + $index }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>{{ $cliente->servicios_totales }}</td>
                            <td>{{ number_format($cliente->total_gastado, 2) }}</td>
                            <td>
                                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $clientes->links() }}
        </div>
    </div>
@endsection