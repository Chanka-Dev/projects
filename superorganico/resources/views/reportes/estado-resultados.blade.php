@extends('layouts.app')

@section('title', 'Estado de Resultados')

@section('page_header')
    <h1><i class="fas fa-chart-line"></i> Estado de Resultados</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Período</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.estado-resultados') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha Desde:</label>
                            <input type="date" name="fecha_desde" class="form-control" value="{{ $fecha_desde }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha Hasta:</label>
                            <input type="date" name="fecha_hasta" class="form-control" value="{{ $fecha_hasta }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>
                </div>
            </form>

            <div class="row">
                {{-- INGRESOS --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h4 class="card-title mb-0">INGRESOS (Cuenta 5.x)</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cuenta</th>
                                        <th class="text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ingresos as $item)
                                        <tr>
                                            <td><small>{{ $item['codigo'] }}</small></td>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td class="text-right">{{ formatearBs($item['monto']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Sin ingresos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="font-weight-bold bg-light">
                                    <tr>
                                        <td colspan="2">TOTAL INGRESOS</td>
                                        <td class="text-right">{{ formatearBs($total_ingresos) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- EGRESOS --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger">
                            <h4 class="card-title mb-0">EGRESOS (Cuenta 6.x)</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cuenta</th>
                                        <th class="text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($egresos as $item)
                                        <tr>
                                            <td><small>{{ $item['codigo'] }}</small></td>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td class="text-right">{{ formatearBs($item['monto']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Sin egresos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="font-weight-bold bg-light">
                                    <tr>
                                        <td colspan="2">TOTAL EGRESOS</td>
                                        <td class="text-right">{{ formatearBs($total_egresos) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RESULTADO --}}
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card border-dark">
                        <div class="card-body">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr class="bg-light">
                                        <td class="font-weight-bold">TOTAL INGRESOS</td>
                                        <td class="text-right font-weight-bold">{{ formatearBs($total_ingresos) }}</td>
                                    </tr>
                                    <tr>
                                        <td>(-) TOTAL EGRESOS</td>
                                        <td class="text-right">{{ formatearBs($total_egresos) }}</td>
                                    </tr>
                                    <tr class="{{ $utilidad_antes_impuestos >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                        <td class="font-weight-bold">
                                            @if($utilidad_antes_impuestos >= 0)
                                                <i class="fas fa-equals"></i> UTILIDAD ANTES DE IMPUESTOS
                                            @else
                                                <i class="fas fa-equals"></i> PÉRDIDA DEL PERÍODO
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold">{{ formatearBs(abs($utilidad_antes_impuestos)) }}</td>
                                    </tr>
                                    @if($utilidad_antes_impuestos > 0)
                                    <tr class="bg-warning">
                                        <td>(-) IUE 25% (Impuesto sobre Utilidades)</td>
                                        <td class="text-right">{{ formatearBs($iue) }}</td>
                                    </tr>
                                    <tr class="{{ $utilidad_neta >= 0 ? 'bg-primary' : 'bg-danger' }} text-white">
                                        <td class="font-weight-bold" style="font-size: 1.1em;">
                                            <i class="fas fa-equals"></i> UTILIDAD NETA DESPUÉS DE IUE
                                        </td>
                                        <td class="text-right font-weight-bold" style="font-size: 1.2em;">
                                            {{ formatearBs($utilidad_neta) }}
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Período: {{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} 
                                    al {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir Estado de Resultados
                </button>
            </div>
        </div>
    </div>
@stop

@section('extra_css')
<style>
@media print {
    .card-header, .btn, form { display: none !important; }
    .card { page-break-inside: avoid; }
}
</style>
@stop
