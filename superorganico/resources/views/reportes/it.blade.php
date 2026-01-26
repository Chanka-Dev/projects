@extends('layouts.app')

@section('title', 'Reporte IT')

@section('page_header')
    <h1><i class="fas fa-file-invoice-dollar"></i> Reporte IT - Impuesto a las Transacciones (3%)</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-danger">
            <h3 class="card-title"><i class="fas fa-calculator"></i> Cálculo IT Boliviano</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.it') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label>Mes</label>
                        <select name="mes" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Año</label>
                        <select name="anio" class="form-control">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $anio == $i ? 'selected' : '' }}>
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

            <hr>

            {{-- Detalle de transacciones --}}
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Detalle de Transacciones</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th class="text-right">Base Imponible</th>
                                <th class="text-right">IT 3%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventasConIT as $venta)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $venta->tipo_comprobante == 'factura' ? 'success' : 'info' }}">
                                            {{ strtoupper($venta->tipo_comprobante) }}
                                        </span>
                                        #{{ $venta->id }}
                                    </td>
                                    <td>{{ $venta->cliente->nombre }}</td>
                                    <td class="text-right">Bs. {{ number_format($venta->total, 2) }}</td>
                                    <td class="text-right">Bs. {{ number_format($venta->it, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay transacciones en este período</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-danger">
                            <tr>
                                <th colspan="4" class="text-right">TOTAL IT A PAGAR:</th>
                                <th class="text-right">
                                    <h4 class="mb-0">Bs. {{ number_format($itAcumulado, 2) }}</h4>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">IT Acumulado del Período</span>
                            <span class="info-box-number">Bs. {{ number_format($itAcumulado, 2) }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                3% sobre {{ count($ventasConIT) }} transacciones de venta
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Información IT (Impuesto a las Transacciones)</h5>
                <p><strong>Base Legal:</strong> Ley N° 843 - Texto Ordenado Vigente</p>
                <p><strong>Alícuota:</strong> 3% sobre el total de transacciones</p>
                <p><strong>Aplicación:</strong> Se aplica sobre ventas brutas, sin deducciones</p>
                <p class="mb-0"><strong>Período:</strong> {{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}</p>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir Reporte
                </button>
                <a href="{{ route('reportes.iva') }}" class="btn btn-primary">
                    <i class="fas fa-file-alt"></i> Ver Reporte IVA
                </a>
            </div>
        </div>
    </div>
@stop

@section('extra_css')
<style>
@media print {
    .card-header, .btn, form { display: none !important; }
}
</style>
@stop
