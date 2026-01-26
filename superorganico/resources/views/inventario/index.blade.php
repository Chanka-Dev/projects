@extends('layouts.app')

@section('title', 'Inventario General')

@section('content_header')
    <h1>Control de Inventario</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Stock General de Productos</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('inventario.alertas') }}" class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle"></i> Ver Alertas
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="buscar" class="form-control" placeholder="Buscar producto...">
                    </div>
                    <div class="col-md-3">
                        <select id="filtroCategoria" class="form-control">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filtroStock" class="form-control">
                            <option value="">Todos los niveles</option>
                            <option value="bajo">Stock Bajo</option>
                            <option value="normal">Stock Normal</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-sm" id="tablaInventario">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Unidad</th>
                                <th>Precio Compra</th>
                                <th>Precio Venta</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $producto)
                            <tr class="{{ $producto->stock <= $producto->stock_minimo ? 'table-warning' : '' }}">
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->categoria }}</td>
                                <td>
                                    <strong>{{ number_format($producto->stock, 2) }}</strong>
                                    @if($producto->stock <= $producto->stock_minimo)
                                        <i class="fas fa-exclamation-triangle text-warning ml-1"></i>
                                    @endif
                                </td>
                                <td>{{ number_format($producto->stock_minimo, 2) }}</td>
                                <td>{{ $producto->unidad_medida }}</td>
                                <td>{{ formatearBs($producto->precio_compra) }}</td>
                                <td>{{ formatearBs($producto->precio_venta) }}</td>
                                <td>
                                    @if($producto->activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('inventario.kardex.producto', $producto->id) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Ver Kardex">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No hay productos en el inventario</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="3"><strong>Totales:</strong></td>
                                <td><strong>{{ number_format($productos->sum('stock'), 2) }}</strong></td>
                                <td colspan="6"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Búsqueda
    $('#buscar').on('keyup', function() {
        let valor = $(this).val().toLowerCase();
        $('#tablaInventario tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Filtro por categoría
    $('#filtroCategoria').on('change', function() {
        let valor = $(this).val().toLowerCase();
        $('#tablaInventario tbody tr').filter(function() {
            if (!valor) {
                $(this).show();
            } else {
                let categoria = $(this).find('td:eq(2)').text().toLowerCase();
                $(this).toggle(categoria.indexOf(valor) > -1);
            }
        });
    });

    // Filtro por nivel de stock
    $('#filtroStock').on('change', function() {
        let valor = $(this).val();
        $('#tablaInventario tbody tr').each(function() {
            if (!valor) {
                $(this).show();
            } else {
                let tienealerta = $(this).find('.fa-exclamation-triangle').length > 0;
                if (valor === 'bajo') {
                    $(this).toggle(tienealerta);
                } else {
                    $(this).toggle(!tienealerta);
                }
            }
        });
    });
});
</script>
@stop
