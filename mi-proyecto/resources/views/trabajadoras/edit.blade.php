@extends('adminlte::page')

@section('title', 'Editar Trabajadora')

@section('content_header')
    <h1>Editar Trabajadora</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('trabajadoras.update', $trabajadora) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $trabajadora->nombre) }}" required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tipo_contrato">Tipo de Contrato</label>
                    <select name="tipo_contrato" id="tipo_contrato" class="form-control @error('tipo_contrato') is-invalid @enderror" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="planta" {{ old('tipo_contrato', $trabajadora->tipo_contrato) == 'planta' ? 'selected' : '' }}>Planta</option>
                        <option value="independiente" {{ old('tipo_contrato', $trabajadora->tipo_contrato) == 'independiente' ? 'selected' : '' }}>Independiente</option>
                    </select>
                    @error('tipo_contrato')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Las comisiones ahora se configuran por servicio
                    </small>
                </div>
                <div class="form-group">
                    <label for="activo">Activo</label>
                    <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $trabajadora->activo) ? 'checked' : '' }}>
                    @error('activo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('trabajadoras.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection