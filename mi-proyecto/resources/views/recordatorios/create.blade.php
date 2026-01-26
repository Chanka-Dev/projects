@extends('adminlte::page')

@section('title', 'Registrar Recordatorio')

@section('content_header')
    <h1>Registrar Nuevo Recordatorio</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('recordatorios.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cita_id">Cita</label>
                    <select name="cita_id" id="cita_id" class="form-control @error('cita_id') is-invalid @enderror" required>
                        <option value="">Seleccionar cita</option>
                        @foreach($citas as $cita)
                            <option value="{{ $cita->id }}" {{ old('cita_id') == $cita->id ? 'selected' : '' }}>
                                {{ $cita->cliente->nombre }} - {{ $cita->fecha->format('d/m/Y') }} {{ $cita->hora_inicio->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('cita_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea name="mensaje" id="mensaje" class="form-control @error('mensaje') is-invalid @enderror" required>{{ old('mensaje') }}</textarea>
                    @error('mensaje')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fecha_envio">Fecha de Envío</label>
                    <input type="datetime-local" name="fecha_envio" id="fecha_envio" class="form-control @error('fecha_envio') is-invalid @enderror" value="{{ old('fecha_envio', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_envio')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tipo">Método de Envío</label>
                    <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                        <option value="">Seleccionar método</option>
                        <option value="email" {{ old('tipo') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('tipo') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="whatsapp" {{ old('tipo') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    </select>
                    @error('tipo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="enviado" {{ old('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                        <option value="falido" {{ old('estado') == 'falido' ? 'selected' : '' }}>Fallido</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('recordatorios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection