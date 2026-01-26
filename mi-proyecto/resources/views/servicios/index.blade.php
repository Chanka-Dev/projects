@extends('adminlte::page')

@section('title', 'Catálogo de Servicios')

@section('content_header')
    <h1>Catálogo de Servicios</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('servicios.create') }}" class="btn btn-primary">Nuevo Servicio</a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('servicios.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o descripción" value="{{ request('search') }}">
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
                        <th>Precio (Bs)</th>
                        <th>Comisión (Bs)</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $index => $servicio)
                        <tr>
                            <td>{{ $servicios->firstItem() + $index }}</td>
                            <td>{{ $servicio->nombre }}</td>
                            <td>Bs {{ number_format($servicio->precio_base, 2) }}</td>
                            <td>Bs {{ number_format($servicio->monto_comision, 2) }}</td>
                            <td>
                                @if($servicio->activo)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('servicios.show', $servicio) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $servicios->links() }}
        </div>
    </div>
@endsection