@extends('layouts.app')

@section('title', 'Reporte IVA')

@section('page_header')
    <h1><i class="fas fa-file-invoice-dollar"></i> Reporte de IVA</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-danger">
            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Declaración de IVA - Bolivia</h3>
        </div>
        <div class="card-body">
            {{-- Filtro de período --}}
            <form method="GET" action="{{ route('reportes.iva') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label>Mes</label>
                        <select name="mes" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('mes', date('n')) == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Año</label>
                        <select name="anio" class="form-control">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ request('anio', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

            <div class="row">
                {{-- Débito Fiscal --}}
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-arrow-up"></i> Débito Fiscal IVA (Ventas)</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Comprobante</th>
                                        <th>Cliente</th>
                                        <th class="text-right">Base</th>
                                        <th class="text-right">IVA 13%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventasConIVA as $venta)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $venta->tipo_comprobante == 'factura' ? 'success' : 'info' }}">
                                                    {{ strtoupper($venta->tipo_comprobante) }}
                                                </span>
                                            </td>
                                            <td>{{ $venta->cliente->nombre }}</td>
                                            <td class="text-right">Bs. {{ number_format($venta->subtotal, 2) }}</td>
                                            <td class="text-right">Bs. {{ number_format($venta->iva, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-success">
                                        <td colspan="4" class="text-right"><strong>TOTAL DÉBITO FISCAL:</strong></td>
                                        <td class="text-right">
                                            <strong>Bs. {{ number_format($totalDebitoFiscal, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Crédito Fiscal --}}
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-arrow-down"></i> Crédito Fiscal IVA (Compras)</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Factura</th>
                                        <th>Proveedor</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">IVA 13%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comprasConIVA as $compra)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</td>
                                            <td><code>{{ $compra->numero_factura }}</code></td>
                                            <td>{{ $compra->proveedor->nombre }}</td>
                                            <td class="text-right">Bs. {{ number_format($compra->total, 2) }}</td>
                                            <td class="text-right">Bs. {{ number_format($compra->credito_fiscal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-primary">
                                        <td colspan="4" class="text-right"><strong>TOTAL CRÉDITO FISCAL:</strong></td>
                                        <td class="text-right">
                                            <strong>Bs. {{ number_format($totalCreditoFiscal, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen IVA --}}
            <div class="card {{ $saldoIVA >= 0 ? 'card-danger' : 'card-info' }}">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calculator"></i> Liquidación de IVA</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="50%">Débito Fiscal (IVA por Ventas)</th>
                            <td class="text-right"><h4>Bs. {{ number_format($totalDebitoFiscal, 2) }}</h4></td>
                        </tr>
                        <tr>
                            <th>Crédito Fiscal (IVA por Compras)</th>
                            <td class="text-right"><h4>Bs. {{ number_format($totalCreditoFiscal, 2) }}</h4></td>
                        </tr>
                        <tr class="{{ $saldoIVA >= 0 ? 'table-danger' : 'table-info' }}">
                            <th><h3>{{ $saldoIVA >= 0 ? 'IVA A PAGAR' : 'SALDO A FAVOR' }}</h3></th>
                            <td class="text-right">
                                <h3>Bs. {{ number_format(abs($saldoIVA), 2) }}</h3>
                            </td>
                        </tr>
                    </table>

                    @if($saldoIVA >= 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong> Debe pagar Bs. {{ number_format($saldoIVA, 2) }} a Impuestos Nacionales de Bolivia.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> Tiene un saldo a favor de Bs. {{ number_format(abs($saldoIVA), 2) }} que puede compensar en el siguiente período.
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('reportes.iva', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                    <a href="{{ route('reportes.iva', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
