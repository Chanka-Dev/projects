@extends('adminlte::page')

@section('title', 'Citas-Servicios')

@section('content_header')
    <h1>Servicios por Cita</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('citas-servicios.index') }}" method="GET" class="form-inline float-right">
                <input type="text" name="search" class="form-control mr-2" placeholder="Buscar cliente o servicio" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <h3 class="card-title">Listado de Servicios Aplicados</h3>
        </div>
        <div class="card-body p-0">
            @if($citasServicios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha Cita</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Precio Aplicado</th>
                                <th>Trabajadora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citasServicios as $registro)
                                <tr>
                                    <td>{{ $registro->id }}</td>
                                    <td>{{ $registro->cita->fecha->format('d/m/Y') }}</td>
                                    <td>{{ $registro->cita->cliente->nombre }}</td>
                                    <td>{{ $registro->servicio->nombre }}</td>
                                    <td>
                                        <span class="badge badge-success">
                                            ${{ number_format($registro->precio_aplicado, 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $registro->cita->trabajadora->nombre }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay servicios registrados en citas</p>
                </div>
            @endif
        </div>
        <div class="card-footer">
            {{ $citasServicios->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
