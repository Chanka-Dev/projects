@extends('layouts.app')

@section('title', 'Nueva Cuenta')

@section('content_header')
    <h1><i class="fas fa-plus"></i> Nueva Cuenta Contable</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrar Nueva Cuenta</h3>
    </div>
    
    <form action="{{ route('plan-cuentas.store') }}" method="POST">
        @csrf
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Código <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}" required placeholder="Ej: 1.1.1.01">
                        @error('codigo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Use puntos para separar niveles (Ej: 1.1.1.01)</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipo de Cuenta <span class="text-danger">*</span></label>
                        <select name="tipo_cuenta" class="form-control @error('tipo_cuenta') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            <option value="activo" {{ old('tipo_cuenta') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="pasivo" {{ old('tipo_cuenta') == 'pasivo' ? 'selected' : '' }}>Pasivo</option>
                            <option value="patrimonio" {{ old('tipo_cuenta') == 'patrimonio' ? 'selected' : '' }}>Patrimonio</option>
                            <option value="ingreso" {{ old('tipo_cuenta') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                            <option value="egreso" {{ old('tipo_cuenta') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                        </select>
                        @error('tipo_cuenta')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Naturaleza <span class="text-danger">*</span></label>
                        <select name="naturaleza" class="form-control @error('naturaleza') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            <option value="deudora" {{ old('naturaleza') == 'deudora' ? 'selected' : '' }}>Deudora</option>
                            <option value="acreedora" {{ old('naturaleza') == 'acreedora' ? 'selected' : '' }}>Acreedora</option>
                        </select>
                        @error('naturaleza')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Activo/Egreso: Deudora | Pasivo/Patrimonio/Ingreso: Acreedora</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nivel <span class="text-danger">*</span></label>
                        <select name="nivel" class="form-control @error('nivel') is-invalid @enderror" required>
                            <option value="1" {{ old('nivel') == 1 ? 'selected' : '' }}>1 - Mayor</option>
                            <option value="2" {{ old('nivel') == 2 ? 'selected' : '' }}>2 - Subcuenta</option>
                            <option value="3" {{ old('nivel') == 3 ? 'selected' : '' }}>3 - Auxiliar</option>
                            <option value="4" {{ old('nivel') == 4 ? 'selected' : '' }}>4 - Detalle</option>
                            <option value="5" {{ old('nivel') == 5 ? 'selected' : '' }}>5 - Sub-detalle</option>
                        </select>
                        @error('nivel')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cuenta Padre</label>
                        <select name="cuenta_padre_id" class="form-control @error('cuenta_padre_id') is-invalid @enderror">
                            <option value="">Sin cuenta padre (nivel 1)</option>
                            @foreach($cuentasPadre as $cuentaPadre)
                                <option value="{{ $cuentaPadre->id }}" {{ old('cuenta_padre_id') == $cuentaPadre->id ? 'selected' : '' }}>
                                    {{ $cuentaPadre->codigo }} - {{ $cuentaPadre->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cuenta_padre_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Acepta Movimientos</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="acepta_movimientos" name="acepta_movimientos" value="1" {{ old('acepta_movimientos', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="acepta_movimientos">Sí</label>
                        </div>
                        <small class="text-muted">Solo cuentas de último nivel aceptan movimientos</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Estado</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="activa" name="activa" value="1" {{ old('activa', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="activa">Activa</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar Cuenta
            </button>
            <a href="{{ route('plan-cuentas.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@stop
