@extends('layouts.app')
@section('title', 'Ingredientes')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6"><h3>Ingredientes</h3></div>
    <div class="col-sm-6 text-right">
        @can('ingredient-create')
        <a href="{{ route('ingredientes.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Nuevo ingrediente
        </a>
        @endcan
    </div>
</div>

<form method="GET" class="mb-3">
    <div class="input-group" style="max-width:350px">
        <input type="text" name="q" class="form-control" placeholder="Buscar ingrediente..." value="{{ $query }}">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </div>
</form>

@session('success')
<div class="alert alert-success">{{ $value }}</div>
@endsession

<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="60">#</th>
                    <th>Nombre</th>
                    <th width="180">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredientes as $ingrediente)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $ingrediente->nombre }}</td>
                    <td>
                        @can('ingredient-edit')
                        <a href="{{ route('ingredientes.edit', $ingrediente) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-pen"></i> Editar
                        </a>
                        @endcan
                        @can('ingredient-delete')
                        <form method="POST" action="{{ route('ingredientes.destroy', $ingrediente) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar ingrediente?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted">Sin ingredientes registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $ingredientes->links('pagination::bootstrap-5') }}
@endsection
