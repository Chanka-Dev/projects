@extends('layouts.app')
@section('title', 'Recetas')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6"><h3>Recetas</h3></div>
    <div class="col-sm-6 text-right">
        @can('recipe-create')
        <a href="{{ route('recetas.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Nueva receta</a>
        @endcan
    </div>
</div>

{{-- Filtros --}}
<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <select name="etiqueta" class="form-control form-control-sm">
                <option value="">-- Todas las etiquetas --</option>
                @foreach($etiquetas as $e)
                <option value="{{ $e->id }}" {{ $etiquetaFiltro == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="ingrediente" class="form-control form-control-sm">
                <option value="">-- Todos los ingredientes --</option>
                @foreach($ingredientes as $i)
                <option value="{{ $i->id }}" {{ $ingredienteFiltro == $i->id ? 'selected' : '' }}>{{ $i->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary btn-sm btn-block"><i class="fas fa-filter"></i> Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('recetas.index') }}" class="btn btn-outline-secondary btn-sm btn-block">Limpiar</a>
        </div>
    </div>
</form>

@session('success')
<div class="alert alert-success">{{ $value }}</div>
@endsession

<div class="row">
    @forelse($recetas as $receta)
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            @if($receta->imagen)
            <img src="{{ asset($receta->imagen) }}" class="card-img-top" style="height:180px;object-fit:cover" alt="{{ $receta->titulo }}">
            @endif
            <div class="card-body">
                <span class="badge mb-1" style="background-color:{{ $receta->etiqueta->color ?? '#17a2b8' }};color:#fff">{{ $receta->etiqueta->nombre }}</span>
                <h5 class="card-title">{{ $receta->titulo }}</h5>
                <small class="text-muted">Por {{ $receta->user->name }}</small>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('recetas.show', $receta) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                <div>
                    @can('recipe-edit')
                    <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                    @endcan
                    @can('recipe-delete')
                    <form method="POST" action="{{ route('recetas.destroy', $receta) }}" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar receta?')"><i class="fas fa-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="alert alert-light text-center">No hay recetas que coincidan.</div></div>
    @endforelse
</div>

{{ $recetas->links('pagination::bootstrap-5') }}
@endsection
