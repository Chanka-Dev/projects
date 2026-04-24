@extends('layouts.app')
@section('title', 'Etiquetas')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6"><h3>Etiquetas</h3></div>
    <div class="col-sm-6 text-right">
        @can('tag-create')
        <a href="{{ route('etiquetas.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Nueva etiqueta
        </a>
        @endcan
    </div>
</div>

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
                    <th width="80">Color</th>
                    <th width="180">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($etiquetas as $etiqueta)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <span class="badge" style="background-color:{{ $etiqueta->color ?? '#6c757d' }};color:#fff;font-size:.9em">
                            {{ $etiqueta->nombre }}
                        </span>
                    </td>
                    <td>
                        @if($etiqueta->color)
                        <span style="display:inline-block;width:24px;height:24px;border-radius:4px;background:{{ $etiqueta->color }};border:1px solid #ccc;vertical-align:middle"></span>
                        <small class="text-muted ml-1">{{ $etiqueta->color }}</small>
                        @else
                        <small class="text-muted">—</small>
                        @endif
                    </td>
                    <td>
                        @can('tag-edit')
                        <a href="{{ route('etiquetas.edit', $etiqueta) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-pen"></i> Editar
                        </a>
                        @endcan
                        @can('tag-delete')
                        <form method="POST" action="{{ route('etiquetas.destroy', $etiqueta) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar etiqueta?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted">Sin etiquetas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $etiquetas->links('pagination::bootstrap-5') }}
@endsection
