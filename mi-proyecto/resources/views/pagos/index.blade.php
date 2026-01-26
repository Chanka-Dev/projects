@extends('adminlte::page')

@section('title', 'Lista de Pagos')

@section('content_header')
    <h1>Lista de Pagos</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <!-- Citas pendientes de pago -->
    @if($citasSinPago->count() > 0)
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Citas Completadas Pendientes de Pago</h3>
            <div class="card-tools">
                <span class="badge badge-warning">{{ $citasSinPago->count() }} pendiente(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cita #</th>
                        <th>Cliente</th>
                        <th>Trabajadora</th>
                        <th>Fecha</th>
                        <th>Servicios</th>
                        <th>Monto Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citasSinPago as $cita)
                        <tr>
                            <td>{{ $cita->id }}</td>
                            <td>{{ $cita->cliente->nombre }}</td>
                            <td>{{ $cita->trabajadora->nombre }}</td>
                            <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                            <td>
                                @foreach($cita->servicios as $servicio)
                                    <small class="badge badge-info">{{ $servicio->nombre }}</small>
                                @endforeach
                            </td>
                            <td class="font-weight-bold">Bs {{ number_format($cita->servicios->sum('pivot.precio_aplicado'), 2) }}</td>
                            <td>
                                <a href="{{ route('pagos.create', ['cita_id' => $cita->id]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Registrar Pago
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Pagos registrados -->
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title">Pagos Registrados</h3>
            <div class="card-tools">
                <a href="{{ route('pagos.create') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-plus"></i> Nuevo Pago
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form action="{{ route('pagos.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por cliente..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="fallido" {{ request('estado') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Cita</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $index => $pago)
                            <tr>
                                <td>{{ $pagos->firstItem() + $index }}</td>
                                <td>{{ $pago->cita->cliente->nombre }}</td>
                                <td>#{{ $pago->cita->id }} - {{ $pago->cita->fecha->format('d/m/Y') }}</td>
                                <td class="font-weight-bold">Bs {{ number_format($pago->monto_total, 2) }}</td>
                                <td>{{ ucfirst($pago->metodo_pago) }}</td>
                                <td>
                                    @if($pago->estado == 'completado')
                                        <span class="badge badge-success">Completado</span>
                                    @elseif($pago->estado == 'pendiente')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @else
                                        <span class="badge badge-danger">Fallido</span>
                                    @endif
                                </td>
                                <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                <td>
                                    @if($pago->estado == 'pendiente')
                                        <form action="{{ route('pagos.completar', $pago) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Marcar como completado" onclick="return confirm('¿Marcar este pago como completado?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pagos.destroy', $pago) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay pagos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $pagos->links() }}
            </div>
        </div>
    </div>
@endsection