@extends('layouts.app')

@section('title', 'Nuevo Gasto')

@section('content_header')
    <h1><i class="fas fa-plus"></i> Registrar Nuevo Gasto</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Datos del Gasto Operativo</h3>
    </div>
    
    <form action="{{ route('gastos-operativos.store') }}" method="POST">
        @csrf
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_gasto" class="form-control @error('fecha_gasto') is-invalid @enderror" value="{{ old('fecha_gasto', date('Y-m-d')) }}" required>
                        @error('fecha_gasto')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Monto <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="monto" class="form-control @error('monto') is-invalid @enderror" value="{{ old('monto') }}" required placeholder="0.00">
                        @error('monto')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Categoría <span class="text-danger">*</span></label>
                        <select name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" required>
                            <option value="">Seleccione una categoría...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cuenta Contable <span class="text-danger">*</span></label>
                        <select name="cuenta_id" class="form-control @error('cuenta_id') is-invalid @enderror" required>
                            <option value="">Seleccione una cuenta...</option>
                            @foreach($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}" {{ old('cuenta_id') == $cuenta->id ? 'selected' : '' }}>
                                    {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cuenta_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Proveedor (Opcional)</label>
                        <select name="proveedor_id" class="form-control @error('proveedor_id') is-invalid @enderror">
                            <option value="">Seleccione un proveedor...</option>
                            @foreach(\App\Models\Proveedores::where('activo', true)->orderBy('nombre')->get() as $proveedor)
                                <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('proveedor_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo de Comprobante</label>
                        <input type="text" name="tipo_comprobante" class="form-control @error('tipo_comprobante') is-invalid @enderror" value="{{ old('tipo_comprobante') }}" placeholder="Factura, recibo, etc.">
                        @error('tipo_comprobante')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Al guardar el gasto, se generará automáticamente un asiento contable.
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Registrar Gasto
            </button>
            <a href="{{ route('gastos-operativos.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@stop
