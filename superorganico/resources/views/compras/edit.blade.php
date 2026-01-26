@extends('adminlte::page')

@section('title', 'Editar Compra')

@section('content_header')
    <h1>Editar Compra #{{ $compra->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('compras.update', $compra->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="proveedor_id">Proveedor *</label>
                            <select name="proveedor_id" id="proveedor_id" class="form-control select2" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{ $compra->proveedor_id == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha">Fecha *</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" 
                                   value="{{ $compra->fecha }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="numero_factura">Número Factura *</label>
                            <input type="text" name="numero_factura" id="numero_factura" 
                                   class="form-control" value="{{ $compra->numero_factura }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" 
                              rows="3">{{ $compra->observaciones }}</textarea>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Productos de la Compra</h3>
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
                                @foreach($compra->detalles as $detalle)
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
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>Bs. {{ number_format($compra->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle"></i> Los productos y cantidades no se pueden modificar desde aquí. 
                            Si necesita cambiar los productos, elimine la compra y créela nuevamente.
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Compra
                    </button>
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">
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
