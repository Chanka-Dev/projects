@extends('adminlte::page')

@section('title', 'Editar Pago')

@section('content_header')
    <h1>Editar Pago #{{ $pago->id }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pagos.update', $pago) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="cita_id">Cita</label>
                    <select name="cita_id" id="cita_id" class="form-control @error('cita_id') is-invalid @enderror" required>
                        <option value="">Seleccionar cita</option>
                        @foreach($citas as $cita)
                            <option value="{{ $cita->id }}" {{ old('cita_id', $pago->cita_id) == $cita->id ? 'selected' : '' }}>
                                {{ $cita->cliente->nombre }} - {{ $cita->fecha->format('d/m/Y') }} {{ $cita->hora_inicio->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('cita_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="monto_total">Monto Total</label>
                    <input type="number" name="monto_total" id="monto_total" class="form-control @error('monto_total') is-invalid @enderror" value="{{ old('monto_total', $pago->monto_total) }}" step="0.01" required>
                    @error('monto_total')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-control @error('metodo_pago') is-invalid @enderror" required>
                        <option value="">Seleccionar método</option>
                        <option value="efectivo" {{ old('metodo_pago', $pago->metodo_pago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ old('metodo_pago', $pago->metodo_pago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transferencia" {{ old('metodo_pago', $pago->metodo_pago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    </select>
                    @error('metodo_pago')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                        <option value="pendiente" {{ old('estado', $pago->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="completado" {{ old('estado', $pago->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="fallido" {{ old('estado', $pago->estado) == 'fallido' ? 'selected' : '' }}>Fallido</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fecha_pago">Fecha de Pago</label>
                    <input type="date" name="fecha_pago" id="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror" value="{{ old('fecha_pago', $pago->fecha_pago->format('Y-m-d')) }}" required>
                    @error('fecha_pago')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection