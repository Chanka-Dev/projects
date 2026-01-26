@extends('layouts.app')

@section('title', 'Plan de Cuentas')

@section('content_header')
    <h1><i class="fas fa-list-alt"></i> Plan de Cuentas Contable</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Gestión de Cuentas Contables</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('plan-cuentas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Cuenta
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="tipo" class="form-control">
                        <option value="">Todos los tipos</option>
                        <option value="activo" {{ request('tipo') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="pasivo" {{ request('tipo') == 'pasivo' ? 'selected' : '' }}>Pasivo</option>
                        <option value="patrimonio" {{ request('tipo') == 'patrimonio' ? 'selected' : '' }}>Patrimonio</option>
                        <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                        <option value="egreso" {{ request('tipo') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por código o nombre" value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Nivel</th>
                        <th>Naturaleza</th>
                        <th>Movimientos</th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuentas as $cuenta)
                    <tr>
                        <td><strong>{{ $cuenta->codigo }}</strong></td>
                        <td>
                            <span style="padding-left: {{ ($cuenta->nivel - 1) * 20 }}px;">
                                @if($cuenta->nivel > 1)
                                    <i class="fas fa-level-up-alt fa-rotate-90 text-muted"></i>
                                @endif
                                {{ $cuenta->nombre }}
                            </span>
                        </td>
                        <td>
                            @if($cuenta->tipo_cuenta == 'activo')
                                <span class="badge badge-success">Activo</span>
                            @elseif($cuenta->tipo_cuenta == 'pasivo')
                                <span class="badge badge-danger">Pasivo</span>
                            @elseif($cuenta->tipo_cuenta == 'patrimonio')
                                <span class="badge badge-primary">Patrimonio</span>
                            @elseif($cuenta->tipo_cuenta == 'ingreso')
                                <span class="badge badge-info">Ingreso</span>
                            @else
                                <span class="badge badge-warning">Egreso</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $cuenta->nivel }}</td>
                        <td>
                            @if($cuenta->naturaleza == 'deudora')
                                <span class="text-success">Deudora</span>
                            @else
                                <span class="text-danger">Acreedora</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($cuenta->acepta_movimientos)
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($cuenta->activa)
                                <span class="badge badge-success">Activa</span>
                            @else
                                <span class="badge badge-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plan-cuentas.show', $cuenta) }}" class="btn btn-info btn-sm" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('plan-cuentas.edit', $cuenta) }}" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('plan-cuentas.destroy', $cuenta) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar esta cuenta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay cuentas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $cuentas->links() }}
        </div>
    </div>
</div>
@stop
