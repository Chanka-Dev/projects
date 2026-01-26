@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('page_header')
    <h1><i class="fas fa-user-plus"></i> Nuevo Cliente</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-plus"></i> Registrar Cliente</h3>
        </div>
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo de Cliente <span class="text-danger">*</span></label>
                            <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                <option value="persona" {{ old('tipo') == 'persona' ? 'selected' : '' }}>Persona Natural</option>
                                <option value="empresa" {{ old('tipo') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                            </select>
                            @error('tipo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre/Razón Social <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nit">NIT</label>
                            <input type="text" name="nit" id="nit" class="form-control @error('nit') is-invalid @enderror" 
                                   value="{{ old('nit') }}">
                            @error('nit')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ci">CI/DNI</label>
                            <input type="text" name="ci" id="ci" class="form-control @error('ci') is-invalid @enderror" 
                                   value="{{ old('ci') }}">
                            @error('ci')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                                   value="{{ old('telefono') }}">
                            @error('telefono')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <textarea name="direccion" id="direccion" rows="2" class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input type="text" name="ciudad" id="ciudad" class="form-control @error('ciudad') is-invalid @enderror" 
                                   value="{{ old('ciudad') }}">
                            @error('ciudad')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pais">País</label>
                            <input type="text" name="pais" id="pais" class="form-control @error('pais') is-invalid @enderror" 
                                   value="{{ old('pais', 'Bolivia') }}">
                            @error('pais')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="activo">
                                <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                                Cliente Activo
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop
