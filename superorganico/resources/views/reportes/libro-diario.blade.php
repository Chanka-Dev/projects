@extends('layouts.app')

@section('title', 'Libro Diario')

@section('page_header')
    <h1><i class="fas fa-book"></i> Libro Diario</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-calendar"></i> Asientos Contables</h3>
        </div>
        <div class="card-body">
            {{-- Filtros --}}
            <form method="GET" action="{{ route('reportes.libro-diario') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>Fecha Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde', date('Y-m-01')) }}">
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control">
                            <option value="">Todos</option>
                            <option value="venta" {{ request('tipo') == 'venta' ? 'selected' : '' }}>Ventas</option>
                            <option value="compra" {{ request('tipo') == 'compra' ? 'selected' : '' }}>Compras</option>
                            <option value="gasto" {{ request('tipo') == 'gasto' ? 'selected' : '' }}>Gastos</option>
                            <option value="ajuste" {{ request('tipo') == 'ajuste' ? 'selected' : '' }}>Ajustes</option>
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

            {{-- Tabla de asientos --}}
            @forelse($asientos as $asiento)
                <div class="card mb-3 {{ $asiento->tipo == 'venta' ? 'border-success' : ($asiento->tipo == 'compra' ? 'border-primary' : 'border-secondary') }}">
                    <div class="card-header {{ $asiento->tipo == 'venta' ? 'bg-success' : ($asiento->tipo == 'compra' ? 'bg-primary' : 'bg-secondary') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Asiento Nº {{ $asiento->numero }}</strong> - 
                                <span class="badge badge-light">{{ ucfirst($asiento->tipo) }}</span>
                            </div>
                            <div class="col-md-6 text-right">
                                <strong>Fecha:</strong> {{ $asiento->fecha->format('d/m/Y') }}
                            </div>
                        </div>
                        @if($asiento->glosa)
                            <div class="mt-1"><small>{{ $asiento->glosa }}</small></div>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="120">Código</th>
                                    <th>Cuenta</th>
                                    <th width="150" class="text-right">DEBE</th>
                                    <th width="150" class="text-right">HABER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asiento->detalles as $detalle)
                                    <tr>
                                        <td><code>{{ $detalle->cuenta->codigo ?? 'N/A' }}</code></td>
                                        <td>{{ $detalle->cuenta->nombre ?? 'Sin cuenta' }}</td>
                                        <td class="text-right">
                                            @if($detalle->debe > 0)
                                                <strong>Bs. {{ number_format($detalle->debe, 2) }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($detalle->haber > 0)
                                                <strong>Bs. {{ number_format($detalle->haber, 2) }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td colspan="2" class="text-right"><strong>TOTALES:</strong></td>
                                    <td class="text-right">
                                        <strong>Bs. {{ number_format($asiento->detalles->sum('debe'), 2) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>Bs. {{ number_format($asiento->detalles->sum('haber'), 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay asientos contables en el período seleccionado.
                </div>
            @endforelse
        </div>
        <div class="card-footer">
            {{ $asientos->links() }}
            
            <div class="float-right">
                <a href="{{ route('reportes.libro-diario', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="{{ route('reportes.libro-diario', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>
        </div>
    </div>
@stop
