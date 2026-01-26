@extends('layouts.app')

@section('title', 'Proveedores')

@section('content_header')
    <h1>Gestión de Proveedores</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Lista de Proveedores</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Proveedor
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="buscar" class="form-control" placeholder="Buscar proveedor...">
            </div>
            <div class="col-md-3">
                <select id="filtroActivo" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="tablaProveedores">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>NIT</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $proveedor)
                    <tr>
                        <td>{{ $proveedor->id }}</td>
                        <td>{{ $proveedor->nombre }}</td>
                        <td>{{ $proveedor->nit ?? 'N/A' }}</td>
                        <td>{{ $proveedor->telefono }}</td>
                        <td>{{ $proveedor->email ?? 'N/A' }}</td>
                        <td>{{ $proveedor->ciudad ?? 'N/A' }}</td>
                        <td>
                            @if($proveedor->activo)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('proveedores.show', $proveedor->id) }}" 
                                   class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay proveedores registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $proveedores->links() }}
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $('#buscar').on('keyup', function() {
        let valor = $(this).val().toLowerCase();
        $('#tablaProveedores tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Filtro por estado
    $('#filtroActivo').on('change', function() {
        let valor = $(this).val();
        if (valor === '') {
            $('#tablaProveedores tbody tr').show();
        } else {
            $('#tablaProveedores tbody tr').each(function() {
                let estado = $(this).find('td:eq(6) .badge').hasClass('badge-success') ? '1' : '0';
                $(this).toggle(estado === valor);
            });
        }
    });
});
</script>
@stop
