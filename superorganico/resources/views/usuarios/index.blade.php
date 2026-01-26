@extends('layouts.app')

@section('title', 'Usuarios')

@section('content_header')
    <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Lista de Usuarios</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Usuario
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha Creación</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            @if($usuario->esAdministrador())
                                <span class="badge badge-danger">Administrador</span>
                            @else
                                <span class="badge badge-info">Empleado</span>
                            @endif
                        </td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($usuario->id !== auth()->id())
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay usuarios registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@stop
