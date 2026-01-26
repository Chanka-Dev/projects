@extends('layouts.app')

@section('title', 'Productos')

@section('page_header')
    <h1><i class="fas fa-leaf"></i> Gestión de Productos Orgánicos</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Productos</h3>
            <div class="card-tools">
                <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Filtros --}}
            <form method="GET" action="{{ route('productos.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o código..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="tipo" class="form-control">
                            <option value="">Todos los tipos</option>
                            <option value="verdura" {{ request('tipo') == 'verdura' ? 'selected' : '' }}>Verduras</option>
                            <option value="fruta" {{ request('tipo') == 'fruta' ? 'selected' : '' }}>Frutas</option>
                            <option value="grano" {{ request('tipo') == 'grano' ? 'selected' : '' }}>Granos</option>
                            <option value="lacteo" {{ request('tipo') == 'lacteo' ? 'selected' : '' }}>Lácteos</option>
                            <option value="otro" {{ request('tipo') == 'otro' ? 'selected' : '' }}>Otros</option>
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

            {{-- Tabla de productos --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Unidad</th>
                            <th class="text-right">Costo Prom.</th>
                            <th class="text-right">Precio Venta</th>
                            <th class="text-right">Stock</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td><strong>{{ $producto->codigo }}</strong></td>
                                <td>{{ $producto->nombre }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($producto->tipo) }}</span>
                                </td>
                                <td>{{ $producto->unidad_medida }}</td>
                                <td class="text-right">{{ formatearBs($producto->costoPromedioPEPS()) }}</td>
                                <td class="text-right">{{ formatearBs($producto->precio_venta) }}</td>
                                <td class="text-right">
                                    @if($producto->stock_actual > $producto->stock_minimo)
                                        <span class="badge badge-success">{{ $producto->stock_actual }}</span>
                                    @elseif($producto->stock_actual > 0)
                                        <span class="badge badge-warning">{{ $producto->stock_actual }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $producto->stock_actual }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmarEliminacion('¿Eliminar producto {{ $producto->nombre }}?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No se encontraron productos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $productos->links() }}
        </div>
    </div>
@stop

@section('js')
<script>
    function formatearBs(monto) {
        return 'Bs. ' + parseFloat(monto).toFixed(2);
    }
</script>
@stop
