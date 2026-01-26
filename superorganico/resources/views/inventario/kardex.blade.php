@extends('layouts.app')

@section('title', 'Kardex de Producto')

@section('content_header')
    <h1>Kardex de Inventario</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">
                    @if($producto)
                        {{ $producto->nombre }}
                    @else
                        Todos los Productos
                    @endif
                </h3>
                @if($producto)
                    <p class="mb-0"><small>Stock Actual: {{ $producto->stock }}</small></p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Producto</label>
                <select name="producto_id" id="producto_id" class="form-control">
                    <option value="">-- Todos los productos --</option>
                    @foreach($productos as $p)
                        <option value="{{ $p->id }}" {{ $producto && $producto->id == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" name="fecha_desde" class="form-control" value="{{ $fechaDesde }}">
            </div>
            <div class="col-md-3">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin" name="fecha_hasta" class="form-control" value="{{ $fechaHasta }}">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block" onclick="filtrarKardex()">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-success btn-block" onclick="exportarKardex()">
                    <i class="fas fa-file-excel"></i> Exportar
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2">Fecha</th>
                        <th rowspan="2">Tipo</th>
                        <th rowspan="2">Detalle</th>
                        <th colspan="3" class="text-center bg-info">Entradas</th>
                        <th colspan="3" class="text-center bg-warning">Salidas</th>
                        <th colspan="3" class="text-center bg-success">Saldos</th>
                    </tr>
                    <tr>
                        <th class="bg-info">Cant.</th>
                        <th class="bg-info">P.U.</th>
                        <th class="bg-info">Total</th>
                        <th class="bg-warning">Cant.</th>
                        <th class="bg-warning">P.U.</th>
                        <th class="bg-warning">Total</th>
                        <th class="bg-success">Cant.</th>
                        <th class="bg-success">P.U.</th>
                        <th class="bg-success">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo_cantidad = 0;
                        $saldo_total = 0;
                    @endphp
                    @forelse($movimientos as $mov)
                    @php
                        if ($mov['tipo'] == 'entrada') {
                            $saldo_cantidad += $mov['cantidad'];
                            $saldo_total += $mov['total'];
                        } else {
                            $saldo_cantidad -= $mov['cantidad'];
                            $saldo_total -= $mov['total'];
                        }
                        $precio_promedio = $saldo_cantidad > 0 ? $saldo_total / $saldo_cantidad : 0;
                    @endphp
                    <tr>
                        <td>{{ formatearFecha($mov['fecha']) }}</td>
                        <td>
                            @if($mov['tipo'] == 'entrada')
                                <span class="badge badge-info">{{ ucfirst($mov['tipo']) }}</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($mov['tipo']) }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $mov['detalle'] }}
                            @if(!$producto)
                                <br><small class="text-muted">{{ $mov['producto'] }} - Lote: {{ $mov['lote'] }}</small>
                            @endif
                        </td>
                        
                        <!-- Entradas -->
                        @if($mov['tipo'] == 'entrada')
                            <td class="text-right">{{ number_format($mov['cantidad'], 2) }}</td>
                            <td class="text-right">{{ formatearBs($mov['precio_unitario']) }}</td>
                            <td class="text-right">{{ formatearBs($mov['total']) }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        
                        <!-- Salidas -->
                        @if($mov['tipo'] == 'salida')
                            <td class="text-right">{{ number_format($mov['cantidad'], 2) }}</td>
                            <td class="text-right">{{ formatearBs($mov['precio_unitario']) }}</td>
                            <td class="text-right">{{ formatearBs($mov['total']) }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        
                        <!-- Saldos -->
                        <td class="text-right"><strong>{{ number_format($saldo_cantidad, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ formatearBs($precio_promedio) }}</strong></td>
                        <td class="text-right"><strong>{{ formatearBs($saldo_total) }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No hay movimientos en el período seleccionado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer">
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Inventario
        </a>
    </div>
</div>
@stop

@section('js')
<script>
function filtrarKardex() {
    let productoId = $('#producto_id').val();
    let fechaInicio = $('#fechaInicio').val();
    let fechaFin = $('#fechaFin').val();
    
    // Alertas de debug
    alert('Fecha Inicio leída: ' + fechaInicio + '\nFecha Fin leída: ' + fechaFin);
    
    console.log('Filtrando kardex:', {
        productoId: productoId,
        fechaInicio: fechaInicio,
        fechaFin: fechaFin
    });
    
    // Verificar que las fechas no estén vacías
    if (!fechaInicio || !fechaFin) {
        alert('Por favor seleccione ambas fechas');
        return;
    }
    
    // Construir URL con parámetros
    let url = `{{ route('inventario.kardex') }}?fecha_desde=${fechaInicio}&fecha_hasta=${fechaFin}`;
    
    // Agregar producto_id solo si está seleccionado
    if (productoId) {
        url += `&producto_id=${productoId}`;
    }
    
    console.log('URL final:', url);
    window.location.href = url;
}

function exportarKardex() {
    alert('Función de exportación en desarrollo');
}

// Mostrar info de la página actual
$(document).ready(function() {
    console.log('Kardex cargado con:', {
        fechaDesde: '{{ $fechaDesde }}',
        fechaHasta: '{{ $fechaHasta }}',
        totalMovimientos: {{ $movimientos->count() }}
    });
    
    // Mostrar valores actuales de los inputs
    console.log('Valores en inputs:', {
        fechaInicio: $('#fechaInicio').val(),
        fechaFin: $('#fechaFin').val()
    });
});
</script>
@stop
