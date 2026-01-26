@extends('layouts.app')

@section('title', 'Balance General')

@section('page_header')
    <h1><i class="fas fa-balance-scale"></i> Balance General</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-calendar"></i> Fecha de Corte</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.balance') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Corte:</label>
                            <input type="date" name="fecha" class="form-control" value="{{ $fecha_corte }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>
                </div>
            </form>

            <div class="row">
                {{-- ACTIVO --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h4 class="card-title mb-0">ACTIVO</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cuenta</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activo as $item)
                                        <tr>
                                            <td><small>{{ $item['codigo'] }}</small></td>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td class="text-right">{{ formatearBs($item['saldo']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Sin movimientos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="font-weight-bold bg-light">
                                    <tr>
                                        <td colspan="2">TOTAL ACTIVO</td>
                                        <td class="text-right">{{ formatearBs($total_activo) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- PASIVO Y PATRIMONIO --}}
                <div class="col-md-6">
                    {{-- PASIVO --}}
                    <div class="card">
                        <div class="card-header bg-danger">
                            <h4 class="card-title mb-0">PASIVO</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cuenta</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pasivo as $item)
                                        <tr>
                                            <td><small>{{ $item['codigo'] }}</small></td>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td class="text-right">{{ formatearBs($item['saldo']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Sin movimientos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="font-weight-bold bg-light">
                                    <tr>
                                        <td colspan="2">TOTAL PASIVO</td>
                                        <td class="text-right">{{ formatearBs($total_pasivo) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- PATRIMONIO --}}
                    <div class="card mt-3">
                        <div class="card-header bg-info">
                            <h4 class="card-title mb-0">PATRIMONIO</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cuenta</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patrimonio as $item)
                                        <tr>
                                            <td><small>{{ $item['codigo'] }}</small></td>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td class="text-right">{{ formatearBs($item['saldo']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Sin movimientos</td>
                                        </tr>
                                    @endforelse
                                    
                                    {{-- Utilidad/Pérdida del Ejercicio --}}
                                    <tr class="font-weight-bold {{ $resultado_ejercicio >= 0 ? 'text-success' : 'text-danger' }}">
                                        <td colspan="2">
                                            <i class="fas fa-calculator"></i> {{ $resultado_ejercicio >= 0 ? 'Utilidad' : 'Pérdida' }}
                                            <br><small class="text-muted">(Ingresos: {{ formatearBs($total_ingresos) }} - Gastos: {{ formatearBs($total_gastos) }})</small>
                                        </td>
                                        <td class="text-right">{{ formatearBs($resultado_ejercicio) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="font-weight-bold bg-light">
                                    <tr>
                                        <td colspan="2">TOTAL PATRIMONIO + UTILIDAD</td>
                                        <td class="text-right">{{ formatearBs($total_patrimonio + $resultado_ejercicio) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- ECUACIÓN PATRIMONIAL --}}
                    <div class="card mt-3 {{ $balance_verificacion ? 'bg-success' : 'bg-danger' }}">
                        <div class="card-body text-white">
                            <h5 class="mb-2">Ecuación Patrimonial</h5>
                            <div class="row">
                                <div class="col-12">
                                    <strong>ACTIVO = PASIVO + PATRIMONIO + UTILIDAD</strong>
                                </div>
                                <div class="col-12 mt-2">
                                    {{ formatearBs($total_activo) }} = {{ formatearBs($total_pasivo) }} + {{ formatearBs($total_patrimonio) }} + {{ formatearBs($resultado_ejercicio) }}
                                </div>
                                <div class="col-12 mt-2">
                                    {{ formatearBs($total_activo) }} = {{ formatearBs($total_pasivo + $total_patrimonio + $resultado_ejercicio) }}
                                </div>
                                <div class="col-12 mt-2">
                                    @if($balance_verificacion)
                                        <i class="fas fa-check-circle"></i> Balance Cuadrado ✓
                                    @else
                                        <i class="fas fa-exclamation-triangle"></i> Balance Descuadrado
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir Balance
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
