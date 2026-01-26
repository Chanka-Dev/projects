@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content_header')
    <h1>Editar Cliente</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Datos del Cliente</h3>
    </div>
    
    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre', $cliente->nombre) }}"
                               required>
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tipo">Tipo <span class="text-danger">*</span></label>
                        <select name="tipo" id="tipo" class="form-control" required>
                            <option value="persona" {{ old('tipo', $cliente->tipo) == 'persona' ? 'selected' : '' }}>Persona</option>
                            <option value="empresa" {{ old('tipo', $cliente->tipo) == 'empresa' ? 'selected' : '' }}>Empresa</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nit">NIT</label>
                        <input type="text" 
                               name="nit" 
                               id="nit" 
                               class="form-control @error('nit') is-invalid @enderror" 
                               value="{{ old('nit', $cliente->nit) }}">
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
                               value="{{ old('ci', $cliente->ci) }}">
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
                               value="{{ old('telefono', $cliente->telefono) }}"
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
                               value="{{ old('email', $cliente->email) }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="activo">Estado</label>
                        <select name="activo" id="activo" class="form-control">
                            <option value="1" {{ old('activo', $cliente->activo) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('activo', $cliente->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
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
                                  rows="2">{{ old('direccion', $cliente->direccion) }}</textarea>
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
                               value="{{ old('ciudad', $cliente->ciudad) }}">
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
                               value="{{ old('pais', $cliente->pais) }}">
                        @error('pais')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@stop
