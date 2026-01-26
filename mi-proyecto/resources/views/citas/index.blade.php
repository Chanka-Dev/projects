@extends('adminlte::page')

@section('title', 'Lista de Citas')

@section('content_header')
    <h1>Lista de Citas</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('citas.create') }}" class="btn btn-primary">Nueva Cita</a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('citas.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por cliente o fecha" value="{{ request('search') }}">
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
                        <th>Cliente</th>
                        <th>Trabajadora</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Servicios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $index => $cita)
                        <tr>
                            <td>{{ $citas->firstItem() + $index }}</td>
                            <td>{{ $cita->cliente->nombre }}</td>
                            <td>{{ $cita->trabajadora->nombre }}</td>
                            <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                            <td>
                                @if($cita->estado === 'pendiente')
                                    <span class="badge badge-warning">Pendiente</span>
                                @elseif($cita->estado === 'confirmada')
                                    <span class="badge badge-info">Confirmada</span>
                                @elseif($cita->estado === 'completada')
                                    <span class="badge badge-success">Completada</span>
                                @elseif($cita->estado === 'cancelada')
                                    <span class="badge badge-danger">Cancelada</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $cita->servicios->pluck('nombre')->join(', ') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('citas.show', $cita) }}" class="btn btn-info btn-sm" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('citas.destroy', $cita) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar esta cita?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $citas->links() }}
        </div>
    </div>
@endsection