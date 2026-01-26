@extends('adminlte::page')

@section('title', 'Registrar Pago')

@section('content_header')
    <h1>Registrar Pago a Trabajadora</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-info">
                <div class="card-header bg-info">
                    <h3 class="card-title">Resumen del Pago</h3>
                </div>
                <div class="card-body">
                    <p><strong>Trabajadora:</strong> {{ $pagoTrabajadora->trabajadora->nombre }}</p>
                    <p><strong>Periodo:</strong><br>
                        {{ $pagoTrabajadora->fecha_inicio_periodo->format('d/m/Y') }} - 
                        {{ $pagoTrabajadora->fecha_fin_periodo->format('d/m/Y') }}
                    </p>
                    <hr>
                    <p><strong>Total por Servicios:</strong><br>
                        ${{ number_format($pagoTrabajadora->total_servicios, 2) }}
                    </p>
                    <p><strong>Total Comisiones a Pagar:</strong><br>
                        <span class="h3 text-success">${{ number_format($pagoTrabajadora->total_comisiones, 2) }}</span>
                    </p>
                    <p class="text-muted"><small>{{ $pagoTrabajadora->historialServicios->count() }} servicios realizados</small></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Datos del Pago</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('pago-trabajadoras.update', $pagoTrabajadora) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="monto_pagado">Monto Pagado <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="monto_pagado" id="monto_pagado" 
                                       class="form-control @error('monto_pagado') is-invalid @enderror" 
                                       value="{{ old('monto_pagado', $pagoTrabajadora->total_comisiones) }}" 
                                       step="0.01" min="0" required>
                                @error('monto_pagado')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Monto sugerido: ${{ number_format($pagoTrabajadora->total_comisiones, 2) }}</small>
                        </div>

                        <div class="form-group">
                            <label for="metodo_pago">Método de Pago <span class="text-danger">*</span></label>
                            <select name="metodo_pago" id="metodo_pago" class="form-control @error('metodo_pago') is-invalid @enderror" required>
                                <option value="">Seleccione un método</option>
                                <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="deposito" {{ old('metodo_pago') == 'deposito' ? 'selected' : '' }}>Depósito Bancario</option>
                                <option value="cheque" {{ old('metodo_pago') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                            @error('metodo_pago')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fecha_pago">Fecha de Pago <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_pago" id="fecha_pago" 
                                   class="form-control @error('fecha_pago') is-invalid @enderror" 
                                   value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                            @error('fecha_pago')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      placeholder="Notas adicionales sobre el pago...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Atención:</strong> Una vez registrado el pago, no se podrá modificar ni eliminar.
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check"></i> Confirmar Pago
                            </button>
                            <a href="{{ route('pago-trabajadoras.show', $pagoTrabajadora) }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
