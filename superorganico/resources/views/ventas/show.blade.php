@extends('layouts.app')

@section('title', 'Detalle de Venta')

@section('page_header')
    <h1><i class="fas fa-receipt"></i> Detalle de Venta #{{ $venta->numero_venta }}</h1>
@stop

@section('page_content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información de la Venta</h3>
                    <div class="card-tools">
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Número de Venta:</th>
                                    <td><strong>#{{ $venta->numero_venta }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Cliente:</th>
                                    <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>
                                    <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo de Pago:</th>
                                    <td><span class="badge badge-primary">{{ ucfirst($venta->tipo_pago) }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Total:</th>
                                    <td class="text-right"><h4>{{ formatearBs($venta->total) }}</h4></td>
                                </tr>
                                <tr>
                                    <th>Estado Contable:</th>
                                    <td>
                                        @if($venta->estado_contable == 'contabilizado')
                                            <span class="badge badge-success">Contabilizado</span>
                                        @elseif($venta->estado_contable == 'anulado')
                                            <span class="badge badge-danger">Anulado</span>
                                        @else
                                            <span class="badge badge-warning">No Contabilizado</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Usuario:</th>
                                    <td>{{ $venta->usuario ? $venta->usuario->name : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($venta->observaciones)
                        <hr>
                        <strong>Observaciones:</strong>
                        <p>{{ $venta->observaciones }}</p>
                    @endif
                </div>
            </div>

            {{-- Detalles de productos --}}
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-shopping-basket"></i> Productos Vendidos</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $detalle->lote ? $detalle->lote->numero_lote : 'N/A' }}
                                        </small>
                                    </td>
                                    <td class="text-right">{{ $detalle->cantidad }}</td>
                                    <td class="text-right">{{ formatearBs($detalle->precio_unitario) }}</td>
                                    <td class="text-right">{{ formatearBs($detalle->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="4" class="text-right">TOTAL:</td>
                                <td class="text-right"><h5>{{ formatearBs($venta->total) }}</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Asiento contable --}}
        <div class="col-md-4">
            @if($venta->asientoContable)
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title"><i class="fas fa-book"></i> Asiento Contable</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Número:</th>
                                <td>{{ $venta->asientoContable->numero_asiento }}</td>
                            </tr>
                            <tr>
                                <th>Fecha:</th>
                                <td>{{ \Carbon\Carbon::parse($venta->asientoContable->fecha)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge badge-success">
                                        {{ ucfirst($venta->asientoContable->estado) }}
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
                                @foreach($venta->asientoContable->detalles as $detalle)
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
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Esta venta aún no ha sido contabilizada.
                </div>
            @endif
        </div>
    </div>
@stop
