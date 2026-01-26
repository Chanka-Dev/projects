@extends('adminlte::page')

@section('title', 'Editar Venta')

@section('content_header')
    <h1>Editar Venta #{{ $venta->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('ventas.update', $venta->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cliente_id">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" class="form-control select2" required>
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ $venta->cliente_id == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_hora">Fecha y Hora *</label>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control" 
                                   value="{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_comprobante">Tipo Comprobante *</label>
                            <select name="tipo_comprobante" id="tipo_comprobante" class="form-control" required>
                                <option value="factura" {{ $venta->tipo_comprobante == 'factura' ? 'selected' : '' }}>Factura</option>
                                <option value="nota_venta" {{ $venta->tipo_comprobante == 'nota_venta' ? 'selected' : '' }}>Nota de Venta</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero_comprobante">Número Comprobante *</label>
                            <input type="text" name="numero_comprobante" id="numero_comprobante" 
                                   class="form-control" value="{{ $venta->numero_comprobante }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" 
                                      rows="2">{{ $venta->observaciones }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Productos de la Venta</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td>Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                    <td><strong>Bs. {{ number_format($venta->subtotal, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>IVA (13%):</strong></td>
                                    <td><strong>Bs. {{ number_format($venta->iva, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>IT (3%):</strong></td>
                                    <td><strong>Bs. {{ number_format($venta->it, 2) }}</strong></td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                    <td><strong>Bs. {{ number_format($venta->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle"></i> Los productos y cantidades no se pueden modificar desde aquí. 
                            Si necesita cambiar los productos, elimine la venta y créela nuevamente.
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Venta
                    </button>
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
