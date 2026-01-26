@extends('layouts.app')

@section('title', 'Editar Producto')

@section('page_header')
    <h1><i class="fas fa-edit"></i> Editar Producto</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-edit"></i> Modificar Producto: {{ $producto->nombre }}</h3>
        </div>
        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" id="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                   value="{{ old('codigo', $producto->codigo) }}" required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $producto->nombre) }}" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="text-danger">*</span></label>
                            <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                <option value="verdura" {{ old('tipo', $producto->tipo) == 'verdura' ? 'selected' : '' }}>Verdura</option>
                                <option value="fruta" {{ old('tipo', $producto->tipo) == 'fruta' ? 'selected' : '' }}>Fruta</option>
                                <option value="grano" {{ old('tipo', $producto->tipo) == 'grano' ? 'selected' : '' }}>Grano</option>
                                <option value="lacteo" {{ old('tipo', $producto->tipo) == 'lacteo' ? 'selected' : '' }}>Lácteo</option>
                                <option value="otro" {{ old('tipo', $producto->tipo) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('tipo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                            <select name="unidad_medida" id="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" required>
                                <option value="kg" {{ old('unidad_medida', $producto->unidad_medida) == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                                <option value="unidad" {{ old('unidad_medida', $producto->unidad_medida) == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                <option value="litro" {{ old('unidad_medida', $producto->unidad_medida) == 'litro' ? 'selected' : '' }}>Litro</option>
                                <option value="bolsa" {{ old('unidad_medida', $producto->unidad_medida) == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                                <option value="caja" {{ old('unidad_medida', $producto->unidad_medida) == 'caja' ? 'selected' : '' }}>Caja</option>
                            </select>
                            @error('unidad_medida')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="perecedero">
                                <input type="checkbox" name="perecedero" id="perecedero" value="1" {{ old('perecedero', $producto->perecedero) ? 'checked' : '' }}>
                                Producto Perecedero
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="precio_venta">Precio de Venta (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="precio_venta" id="precio_venta" 
                                   class="form-control @error('precio_venta') is-invalid @enderror" 
                                   value="{{ old('precio_venta', $producto->precio_venta) }}" required>
                            @error('precio_venta')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock_minimo">Stock Mínimo <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="stock_minimo" id="stock_minimo" 
                                   class="form-control @error('stock_minimo') is-invalid @enderror" 
                                   value="{{ old('stock_minimo', $producto->stock_minimo) }}" required>
                            @error('stock_minimo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dias_alerta_vencimiento">Días Alerta Vencimiento</label>
                            <input type="number" name="dias_alerta_vencimiento" id="dias_alerta_vencimiento" 
                                   class="form-control @error('dias_alerta_vencimiento') is-invalid @enderror" 
                                   value="{{ old('dias_alerta_vencimiento', $producto->dias_alerta_vencimiento) }}">
                            @error('dias_alerta_vencimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plan_cuenta_inventario_id">Cuenta Inventario</label>
                            <select name="plan_cuenta_inventario_id" id="plan_cuenta_inventario_id" class="form-control">
                                @foreach($cuentasInventario as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('plan_cuenta_inventario_id', $producto->plan_cuenta_inventario_id) == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plan_cuenta_costo_venta_id">Cuenta Costo Venta</label>
                            <select name="plan_cuenta_costo_venta_id" id="plan_cuenta_costo_venta_id" class="form-control">
                                @foreach($cuentasCostoVenta as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('plan_cuenta_costo_venta_id', $producto->plan_cuenta_costo_venta_id) == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plan_cuenta_ingreso_id">Cuenta Ingreso</label>
                            <select name="plan_cuenta_ingreso_id" id="plan_cuenta_ingreso_id" class="form-control">
                                @foreach($cuentasIngreso as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('plan_cuenta_ingreso_id', $producto->plan_cuenta_ingreso_id) == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="activo">
                                <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                Producto Activo
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop
