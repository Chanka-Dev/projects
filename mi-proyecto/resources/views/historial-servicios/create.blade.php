@extends('adminlte::page')

@section('title', 'Registrar Historial de Servicio')

@section('content_header')
    <h1>Registrar Nuevo Historial de Servicio</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('historial-servicios.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Seleccionar cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="trabajadora_id">Trabajadora</label>
                    <select name="trabajadora_id" id="trabajadora_id" class="form-control @error('trabajadora_id') is-invalid @enderror" required>
                        <option value="">Seleccionar trabajadora</option>
                        @foreach($trabajadoras as $trabajadora)
                            <option value="{{ $trabajadora->id }}" {{ old('trabajadora_id') == $trabajadora->id ? 'selected' : '' }}>{{ $trabajadora->nombre }}</option>
                        @endforeach
                    </select>
                    @error('trabajadora_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="servicio_id">Servicio</label>
                    <select name="servicio_id" id="servicio_id" class="form-control @error('servicio_id') is-invalid @enderror" required>
                        <option value="">Seleccionar servicio</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }} ({{ number_format($servicio->precio_base, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fecha_servicio">Fecha de Servicio</label>
                    <input type="date" name="fecha_servicio" id="fecha_servicio" class="form-control @error('fecha_servicio') is-invalid @enderror" value="{{ old('fecha_servicio', now()->format('Y-m-d')) }}" required>
                    @error('fecha_servicio')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="precio_cobrado">Precio Cobrado</label>
                    <input type="number" name="precio_cobrado" id="precio_cobrado" class="form-control @error('precio_cobrado') is-invalid @enderror" value="{{ old('precio_cobrado') }}" step="0.01" required>
                    @error('precio_cobrado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-control @error('metodo_pago') is-invalid @enderror" required>
                        <option value="">Seleccionar método</option>
                        <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    </select>
                    @error('metodo_pago')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('historial-servicios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection