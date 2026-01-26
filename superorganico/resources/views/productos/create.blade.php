@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('page_header')
    <h1><i class="fas fa-leaf"></i> Nuevo Producto Orgánico</h1>
@stop

@section('page_content')
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-plus"></i> Registrar Producto</h3>
        </div>
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- Información básica --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" id="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                   value="{{ old('codigo') }}" required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Clasificación --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="text-danger">*</span></label>
                            <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                <option value="verdura" {{ old('tipo') == 'verdura' ? 'selected' : '' }}>Verdura</option>
                                <option value="fruta" {{ old('tipo') == 'fruta' ? 'selected' : '' }}>Fruta</option>
                                <option value="grano" {{ old('tipo') == 'grano' ? 'selected' : '' }}>Grano</option>
                                <option value="lacteo" {{ old('tipo') == 'lacteo' ? 'selected' : '' }}>Lácteo</option>
                                <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
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
                                <option value="">Seleccione...</option>
                                <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                                <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                <option value="litro" {{ old('unidad_medida') == 'litro' ? 'selected' : '' }}>Litro</option>
                                <option value="bolsa" {{ old('unidad_medida') == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                                <option value="caja" {{ old('unidad_medida') == 'caja' ? 'selected' : '' }}>Caja</option>
                            </select>
                            @error('unidad_medida')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="perecedero">
                                <input type="checkbox" name="perecedero" id="perecedero" value="1" {{ old('perecedero') ? 'checked' : '' }}>
                                Producto Perecedero (requiere PEPS)
                            </label>
                        </div>
                    </div>

                    {{-- Precios --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="precio_venta">Precio de Venta (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="precio_venta" id="precio_venta" 
                                   class="form-control @error('precio_venta') is-invalid @enderror" 
                                   value="{{ old('precio_venta') }}" required oninput="validarDecimal(this)">
                            @error('precio_venta')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Precio sin IVA ni IT (se aplicará tasa efectiva 14.91%)
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock_minimo">Stock Mínimo <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="stock_minimo" id="stock_minimo" 
                                   class="form-control @error('stock_minimo') is-invalid @enderror" 
                                   value="{{ old('stock_minimo', 10) }}" required>
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
                                   value="{{ old('dias_alerta_vencimiento', 7) }}">
                            @error('dias_alerta_vencimiento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Para productos perecederos</small>
                        </div>
                    </div>

                    {{-- Cuentas contables --}}
                    <div class="col-md-12">
                        <hr>
                        <h5><i class="fas fa-book"></i> Configuración Contable</h5>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plan_cuenta_inventario_id">Cuenta Inventario <span class="text-danger">*</span></label>
                            <select name="plan_cuenta_inventario_id" id="plan_cuenta_inventario_id" 
                                    class="form-control @error('plan_cuenta_inventario_id') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach($cuentasInventario as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('plan_cuenta_inventario_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_cuenta_inventario_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plan_cuenta_costo_venta_id">Cuenta Costo Venta <span class="text-danger">*</span></label>
                            <select name="plan_cuenta_costo_venta_id" id="plan_cuenta_costo_venta_id" 
                                    class="form-control @error('plan_cuenta_costo_venta_id') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach($cuentasCostoVenta as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('plan_cuenta_costo_venta_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_cuenta_costo_venta_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plan_cuenta_ingreso_id">Cuenta Ingreso <span class="text-danger">*</span></label>
                            <select name="plan_cuenta_ingreso_id" id="plan_cuenta_ingreso_id" 
                                    class="form-control @error('plan_cuenta_ingreso_id') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach($cuentasIngreso as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('plan_cuenta_ingreso_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_cuenta_ingreso_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="activo">
                                <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                                Producto Activo
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Producto
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop
