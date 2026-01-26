@extends('layouts.app')

@section('title', 'Clientes')

@section('page_header')
    <h1><i class="fas fa-users"></i> Gestión de Clientes</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Clientes</h3>
            <div class="card-tools">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Cliente
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clientes.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre, NIT o CI..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="tipo" class="form-control">
                            <option value="">Todos los tipos</option>
                            <option value="persona" {{ request('tipo') == 'persona' ? 'selected' : '' }}>Persona Natural</option>
                            <option value="empresa" {{ request('tipo') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>NIT/CI</th>
                            <th>Nombre/Razón Social</th>
                            <th>Tipo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Ciudad</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr>
                                <td><strong>{{ $cliente->nit ?? $cliente->ci }}</strong></td>
                                <td>{{ $cliente->nombre }}</td>
                                <td>
                                    <span class="badge badge-{{ $cliente->tipo == 'empresa' ? 'primary' : 'info' }}">
                                        {{ $cliente->tipo == 'empresa' ? 'Empresa' : 'Persona' }}
                                    </span>
                                </td>
                                <td>{{ $cliente->email }}</td>
                                <td>{{ $cliente->telefono }}</td>
                                <td>{{ $cliente->ciudad }}</td>
                                <td>
                                    @if($cliente->activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmarEliminacion('¿Eliminar cliente {{ $cliente->nombre }}?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No se encontraron clientes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $clientes->links() }}
        </div>
    </div>
@stop
