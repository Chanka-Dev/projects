@extends('layouts.app')

@section('title', 'Detalle de Compra')

@section('page_header')
    <h1><i class="fas fa-shopping-bag"></i> Detalle de Compra #{{ $compra->numero_compra }}</h1>
@stop

@section('page_content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información de la Compra</h3>
                    <div class="card-tools">
                        @if($compra->estado == 'pendiente')
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalRecibir">
                                <i class="fas fa-check"></i> Marcar como Recibida
                            </button>
                        @endif
                        <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Número de Compra:</th>
                                    <td><strong>#{{ $compra->numero_compra }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Proveedor:</th>
                                    <td>{{ $compra->proveedor->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>
                                    <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Nº Factura:</th>
                                    <td>{{ $compra->numero_factura ?? 'Sin factura' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Total:</th>
                                    <td class="text-right"><h4>{{ formatearBs($compra->total) }}</h4></td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($compra->estado == 'recibida')
                                            <span class="badge badge-success">Recibida</span>
                                        @elseif($compra->estado == 'pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($compra->estado) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado Contable:</th>
                                    <td>
                                        @if($compra->estado_contable == 'contabilizado')
                                            <span class="badge badge-success">Contabilizado</span>
                                        @else
                                            <span class="badge badge-warning">No Contabilizado</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Usuario:</th>
                                    <td>{{ $compra->usuario ? $compra->usuario->name : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($compra->observaciones)
                        <hr>
                        <strong>Observaciones:</strong>
                        <p>{{ $compra->observaciones }}</p>
                    @endif
                </div>
            </div>

            {{-- Detalles de productos --}}
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-boxes"></i> Productos Comprados</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Producto</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre }}</td>
                                    <td class="text-right">{{ $detalle->cantidad }}</td>
                                    <td class="text-right">{{ formatearBs($detalle->precio_unitario) }}</td>
                                    <td class="text-right">{{ formatearBs($detalle->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="3" class="text-right">TOTAL:</td>
                                <td class="text-right"><h5>{{ formatearBs($compra->total) }}</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Lotes generados --}}
            @if($compra->lotes->count() > 0)
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-warehouse"></i> Lotes de Inventario</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Producto</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Costo Unit.</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Caducidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compra->lotes as $lote)
                                    <tr>
                                        <td>{{ $lote->numero_lote }}</td>
                                        <td>{{ $lote->producto->nombre }}</td>
                                        <td class="text-right">{{ $lote->cantidad_disponible }} / {{ $lote->cantidad_inicial }}</td>
                                        <td class="text-right">{{ formatearBs($lote->costo_unitario) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->fecha_ingreso)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($lote->fecha_caducidad)
                                                {{ \Carbon\Carbon::parse($lote->fecha_caducidad)->format('d/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Asiento contable --}}
        <div class="col-md-4">
            @if($compra->asientoContable)
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title"><i class="fas fa-book"></i> Asiento Contable</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Número:</th>
                                <td>{{ $compra->asientoContable->numero_asiento }}</td>
                            </tr>
                            <tr>
                                <th>Fecha:</th>
                                <td>{{ \Carbon\Carbon::parse($compra->asientoContable->fecha)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge badge-success">
                                        {{ ucfirst($compra->asientoContable->estado) }}
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <h6 class="mt-3">Movimientos:</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cuenta</th>
                                    <th class="text-right">Debe</th>
                                    <th class="text-right">Haber</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compra->asientoContable->detalles as $detalle)
                                    <tr>
                                        <td>
                                            <small>
                                                {{ $detalle->cuenta->codigo }}<br>
                                                <em>{{ $detalle->cuenta->nombre }}</em>
                                            </small>
                                        </td>
                                        <td class="text-right">
                                            @if($detalle->debe > 0)
                                                {{ formatearBs($detalle->debe) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($detalle->haber > 0)
                                                {{ formatearBs($detalle->haber) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Esta compra será contabilizada al marcarla como recibida.
                </div>
            @endif
        </div>
    </div>

    {{-- Modal para recibir compra --}}
    <div class="modal fade" id="modalRecibir" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('compras.recibir', $compra->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success">
                        <h5 class="modal-title">Recibir Compra</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Fecha de Recepción</label>
                            <input type="date" name="fecha_recepcion" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <h6>Fechas de Caducidad (Productos Perecederos)</h6>
                        @foreach($compra->detalles as $detalle)
                            @if($detalle->producto->es_perecedero)
                                <div class="form-group">
                                    <label>{{ $detalle->producto->nombre }}</label>
                                    <input type="date" 
                                           name="fecha_caducidad_{{ $detalle->id }}" 
                                           class="form-control" 
                                           min="{{ date('Y-m-d') }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Confirmar Recepción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
