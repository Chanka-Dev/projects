@extends('adminlte::page')

@section('title', 'Registrar Servicio')

@section('content_header')
    <h1>Registrar Nuevo Servicio</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('servicios.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="precio_base">Precio Base (Bs)</label>
                    <input type="number" name="precio_base" id="precio_base" class="form-control @error('precio_base') is-invalid @enderror" value="{{ old('precio_base') }}" step="0.01" required>
                    @error('precio_base')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="monto_comision">Comisión (Bs)</label>
                    <input type="number" name="monto_comision" id="monto_comision" class="form-control @error('monto_comision') is-invalid @enderror" value="{{ old('monto_comision', 0) }}" step="0.01" min="0" required>
                    <small class="form-text text-muted">Monto fijo que se pagará a la trabajadora por este servicio</small>
                    @error('monto_comision')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="activo">Activo</label>
                    <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', 1) ? 'checked' : '' }}>
                    @error('activo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection