@extends('layouts.app')

@section('title', 'Detalle del Producto')

@section('page_header')
    <h1><i class="fas fa-leaf"></i> Detalle del Producto</h1>
@stop

@section('page_content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Producto</h3>
                    <div class="card-tools">
                        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Código:</th>
                                    <td><strong>{{ $producto->codigo }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nombre:</th>
                                    <td><strong>{{ $producto->nombre }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Categoría:</th>
                                    <td><span class="badge badge-info">{{ ucfirst($producto->categoria) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Unidad de Medida:</th>
                                    <td>{{ strtoupper($producto->unidad_medida) }}</td>
                                </tr>
                                <tr>
                                    <th>Perecedero:</th>
                                    <td>
                                        @if($producto->es_perecedero)
                                            <span class="badge badge-warning">Sí</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($producto->activo)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Stock Disponible:</th>
                                    <td class="text-right">
                                        @if($stock_disponible > $producto->stock_minimo)
                                            <span class="badge badge-success">{{ $stock_disponible }}</span>
                                        @elseif($stock_disponible > 0)
                                            <span class="badge badge-warning">{{ $stock_disponible }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $stock_disponible }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stock Mínimo:</th>
                                    <td class="text-right">{{ $producto->stock_minimo }}</td>
                                </tr>
                                <tr>
                                    <th>Costo Promedio PEPS:</th>
                                    <td class="text-right"><strong>{{ formatearBs($costo_promedio_peps) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Precio de Venta:</th>
                                    <td class="text-right"><strong>{{ formatearBs($producto->precio_venta) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Margen de Utilidad:</th>
                                    <td class="text-right">
                                        <span class="badge badge-info">{{ number_format($margen_utilidad, 2) }}%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Valor en Inventario:</th>
                                    <td class="text-right">
                                        <strong>{{ formatearBs($stock_disponible * $costo_promedio_peps) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($producto->descripcion)
                        <hr>
                        <h5>Descripción:</h5>
                        <p>{{ $producto->descripcion }}</p>
                    @endif

                    <hr>
                    <h5>Cuentas Contables:</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Inventario:</strong><br>
                            {{ $producto->cuentaInventario->codigo }} - {{ $producto->cuentaInventario->nombre }}
                        </div>
                        <div class="col-md-4">
                            <strong>Costo de Venta:</strong><br>
                            {{ $producto->cuentaCostoVenta->codigo }} - {{ $producto->cuentaCostoVenta->nombre }}
                        </div>
                        <div class="col-md-4">
                            <strong>Ingreso:</strong><br>
                            {{ $producto->cuentaIngreso->codigo }} - {{ $producto->cuentaIngreso->nombre }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-boxes"></i> Lotes Disponibles (PEPS)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Lote</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">Costo</th>
                                @if($producto->es_perecedero)
                                    <th>Caducidad</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($producto->lotes as $lote)
                                <tr>
                                    <td>{{ $lote->numero_lote }}</td>
                                    <td class="text-right">{{ $lote->cantidad_disponible }}</td>
                                    <td class="text-right">{{ formatearBs($lote->costo_unitario) }}</td>
                                    @if($producto->es_perecedero)
                                        <td>
                                            @if($lote->fecha_caducidad)
                                                @php
                                                    $dias = \Carbon\Carbon::parse($lote->fecha_caducidad)->diffInDays(now(), false);
                                                @endphp
                                                @if($dias > 0)
                                                    <span class="badge badge-danger">Vencido</span>
                                                @elseif($dias > -7)
                                                    <span class="badge badge-warning">{{ abs($dias) }}d</span>
                                                @else
                                                    <span class="badge badge-success">{{ abs($dias) }}d</span>
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $producto->es_perecedero ? 4 : 3 }}" class="text-center text-muted">
                                        Sin lotes disponibles
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
