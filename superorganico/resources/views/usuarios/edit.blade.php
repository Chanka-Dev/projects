@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-warning">
        <h3 class="card-title">Modificar Datos del Usuario: {{ $usuario->name }}</h3>
    </div>
    
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nueva Contraseña <small class="text-muted">(dejar en blanco para no cambiar)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rol <span class="text-danger">*</span></label>
                        <select name="rol" class="form-control @error('rol') is-invalid @enderror" required>
                            <option value="administrador" {{ old('rol', $usuario->rol) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="empleado" {{ old('rol', $usuario->rol) == 'empleado' ? 'selected' : '' }}>Empleado</option>
                        </select>
                        @error('rol')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <strong>Permisos por Rol:</strong>
                <ul class="mb-0">
                    <li><strong>Administrador:</strong> Acceso total al sistema</li>
                    <li><strong>Empleado:</strong> Registro de ventas, visualización de inventario, gestión de clientes y acceso al dashboard</li>
                </ul>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@stop
