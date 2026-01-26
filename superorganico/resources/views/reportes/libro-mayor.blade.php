@extends('layouts.app')

@section('title', 'Libro Mayor')

@section('page_header')
    <h1><i class="fas fa-book"></i> Libro Mayor</h1>
    <p class="text-muted">Movimientos detallados de una cuenta contable</p>
@stop

@section('page_content')
    {{-- Explicación --}}
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5><i class="fas fa-info-circle"></i> ¿Qué es el Libro Mayor?</h5>
        <p class="mb-0">
            Es como un "extracto bancario" de cada cuenta contable. Muestra todos los movimientos (entradas y salidas) 
            de una cuenta específica en un período, con su saldo acumulado después de cada operación.
        </p>
    </div>

    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-filter"></i> Seleccione la Cuenta a Consultar</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.libro-mayor') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cuenta Contable:</label>
                            <select name="cuenta_id" class="form-control" required>
                                <option value="">Seleccione una cuenta...</option>
                                @foreach($cuentas as $c)
                                    <option value="{{ $c->id }}" {{ $cuenta && $cuenta->id == $c->id ? 'selected' : '' }}>
                                        {{ $c->codigo }} - {{ $c->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Desde:</label>
                            <input type="date" name="fecha_desde" class="form-control" value="{{ $fecha_desde }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha Hasta:</label>
                            <input type="date" name="fecha_hasta" class="form-control" value="{{ $fecha_hasta }}">
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
        </div>
    </div>

    @if($cuenta)
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                    <i class="fas fa-file-alt"></i> 
                    Movimientos: {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                </h3>
                <div class="card-tools">
                    <span class="badge badge-light">Tipo: {{ ucfirst($cuenta->tipo_cuenta ?? 'N/A') }}</span>
                    <span class="badge badge-light">Naturaleza: {{ ucfirst($cuenta->naturaleza ?? 'deudora') }}</span>
                </div>
            </div>
            
            {{-- Resumen --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box bg-light">
                            <span class="info-box-icon"><i class="fas fa-arrow-up text-success"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Debe (Cargos)</span>
                                <span class="info-box-number">{{ formatearBs($total_debe) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-light">
                            <span class="info-box-icon"><i class="fas fa-arrow-down text-danger"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Haber (Abonos)</span>
                                <span class="info-box-number">{{ formatearBs($total_haber) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-{{ $saldo_final >= 0 ? 'success' : 'warning' }}">
                            <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Saldo Final</span>
                                <span class="info-box-number">{{ formatearBs($saldo_final) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if($movimientos->count() > 0)
                    <table class="table table-striped table-sm table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="80">Fecha</th>
                                <th width="120">Nº Asiento</th>
                                <th>Descripción / Concepto</th>
                                <th class="text-right" width="120">Debe<br><small class="text-muted">(Cargos)</small></th>
                                <th class="text-right" width="120">Haber<br><small class="text-muted">(Abonos)</small></th>
                                <th class="text-right bg-light" width="120">Saldo<br><small class="text-muted">(Acumulado)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movimientos as $index => $mov)
                                <tr>
                                    <td><small>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</small></td>
                                    <td><code>{{ $mov->numero_asiento }}</code></td>
                                    <td><small>{{ $mov->glosa }}</small></td>
                                    <td class="text-right {{ $mov->debe > 0 ? 'text-success font-weight-bold' : 'text-muted' }}">
                                        {{ $mov->debe > 0 ? formatearBs($mov->debe) : '-' }}
                                    </td>
                                    <td class="text-right {{ $mov->haber > 0 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                        {{ $mov->haber > 0 ? formatearBs($mov->haber) : '-' }}
                                    </td>
                                    <td class="text-right bg-light">
                                        <strong class="{{ $mov->saldo >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ formatearBs($mov->saldo) }}
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="font-weight-bold bg-secondary text-white">
                            <tr>
                                <td colspan="3" class="text-right">TOTALES DEL PERÍODO:</td>
                                <td class="text-right text-success">{{ formatearBs($total_debe) }}</td>
                                <td class="text-right text-danger">{{ formatearBs($total_haber) }}</td>
                                <td class="text-right">
                                    <span class="{{ $saldo_final >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ formatearBs($saldo_final) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h5>No hay movimientos para esta cuenta</h5>
                        <p>Período: {{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}</p>
                        <small>Intenta ampliar el rango de fechas o selecciona otra cuenta.</small>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <button class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <a href="{{ route('reportes.libro-diario') }}" class="btn btn-info">
                    <i class="fas fa-book-open"></i> Ver Libro Diario
                </a>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <h5><i class="fas fa-arrow-up"></i> Selecciona una cuenta contable</h5>
            <p>Usa el formulario de arriba para seleccionar una cuenta y ver todos sus movimientos detallados.</p>
            <hr>
            <small class="text-muted">
                <strong>¿Qué verás?</strong> El Libro Mayor muestra el "historial" completo de una cuenta, 
                como un extracto bancario. Cada operación (cargo o abono) se lista con su saldo acumulado.
            </small>
        </div>
    @endif
@stop

@section('extra_css')
<style>
@media print {
    .card-header, .card-footer, .btn, form { display: none !important; }
}
</style>
@stop
