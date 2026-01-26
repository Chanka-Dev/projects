@extends('adminlte::page')

@section('title', 'Lista de Trabajadoras')

@section('content_header')
    <h1>Lista de Trabajadoras</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('trabajadoras.create') }}" class="btn btn-primary">Nueva Trabajadora</a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('trabajadoras.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o tipo de contrato" value="{{ request('search') }}">
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
                        <th>Tipo de Contrato</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trabajadoras as $index => $trabajadora)
                        <tr>
                            <td>{{ $trabajadoras->firstItem() + $index }}</td>
                            <td>{{ $trabajadora->nombre }}</td>
                            <td>{{ ucfirst($trabajadora->tipo_contrato) }}</td>
                            <td>
                                @if($trabajadora->activo)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('trabajadoras.show', $trabajadora) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('trabajadoras.edit', $trabajadora) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('trabajadoras.destroy', $trabajadora) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $trabajadoras->links() }}
        </div>
    </div>
@endsection