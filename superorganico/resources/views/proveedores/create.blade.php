@extends('layouts.app')

@section('title', 'Nuevo Proveedor')

@section('content_header')
    <h1>Registrar Nuevo Proveedor</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Datos del Proveedor</h3>
    </div>
    
    <form action="{{ route('proveedores.store') }}" method="POST">
        @csrf
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre/Razón Social <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}"
                               required>
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nit">NIT</label>
                        <input type="text" 
                               name="nit" 
                               id="nit" 
                               class="form-control @error('nit') is-invalid @enderror" 
                               value="{{ old('nit') }}">
                        @error('nit')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ci">CI</label>
                        <input type="text" 
                               name="ci" 
                               id="ci" 
                               class="form-control @error('ci') is-invalid @enderror" 
                               value="{{ old('ci') }}">
                        @error('ci')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="telefono" 
                               id="telefono" 
                               class="form-control @error('telefono') is-invalid @enderror" 
                               value="{{ old('telefono') }}"
                               required>
                        @error('telefono')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="activo">Estado</label>
                        <select name="activo" id="activo" class="form-control">
                            <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <textarea name="direccion" 
                                  id="direccion" 
                                  class="form-control @error('direccion') is-invalid @enderror" 
                                  rows="2">{{ old('direccion') }}</textarea>
                        @error('direccion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" 
                               name="ciudad" 
                               id="ciudad" 
                               class="form-control @error('ciudad') is-invalid @enderror" 
                               value="{{ old('ciudad', 'Santa Cruz') }}">
                        @error('ciudad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pais">País</label>
                        <input type="text" 
                               name="pais" 
                               id="pais" 
                               class="form-control @error('pais') is-invalid @enderror" 
                               value="{{ old('pais', 'Bolivia') }}">
                        @error('pais')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@stop
