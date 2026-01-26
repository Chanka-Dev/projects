@extends('layouts.app')

@section('title', 'Nueva Venta')

@section('page_header')
    <h1><i class="fas fa-shopping-cart"></i> Nueva Venta</h1>
@stop

@section('page_content')
    <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
        @csrf
        <div class="row">
            {{-- Información de la venta --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Información de Venta</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="cliente_id" id="cliente_id" class="form-control select2 @error('cliente_id') is-invalid @enderror" required style="width: 100%;">
                                            <option value="">Buscar cliente...</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                                    {{ $cliente->nombre }} - {{ $cliente->nit ?? $cliente->ci }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoCliente">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('cliente_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" 
                                           value="{{ old('fecha', date('Y-m-d')) }}" required>
                                    @error('fecha')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_comprobante">Tipo Comprobante</label>
                                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
                                        <option value="factura" {{ old('tipo_comprobante', 'factura') == 'factura' ? 'selected' : '' }}>Factura</option>
                                        <option value="nota_venta" {{ old('tipo_comprobante') == 'nota_venta' ? 'selected' : '' }}>Nota de Venta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_pago">Tipo Pago <span class="text-danger">*</span></label>
                                    <select name="tipo_pago" id="tipo_pago" class="form-control" required>
                                        <option value="efectivo" {{ old('tipo_pago', 'efectivo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                        <option value="tarjeta" {{ old('tipo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                        <option value="transferencia" {{ old('tipo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control">{{ old('observaciones') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Productos --}}
                        <hr>
                        <h5><i class="fas fa-shopping-basket"></i> Productos</h5>
                        
                        <div class="form-group">
                            <label>Agregar Producto</label>
                            <div class="input-group">
                                <select id="producto_id" class="form-control">
                                    <option value="">Buscar producto...</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" 
                                                data-nombre="{{ $producto->nombre }}"
                                                data-precio="{{ $producto->precio_venta }}"
                                                data-precio-factura="{{ $producto->precioFactura() }}"
                                                data-stock="{{ $producto->stock_actual }}">
                                            {{ $producto->nombre }} - Stock: {{ $producto->stock_actual }} - Bs. {{ number_format($producto->precio_venta, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" onclick="agregarProducto()">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tablaDetalles">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th width="100">Cantidad</th>
                                        <th width="120" class="text-right">Precio</th>
                                        <th width="120" class="text-right">Subtotal</th>
                                        <th width="50" class="text-center">-</th>
                                    </tr>
                                </thead>
                                <tbody id="detallesBody">
                                    <tr id="emptyRow">
                                        <td colspan="5" class="text-center text-muted">No hay productos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen de totales --}}
            <div class="col-md-4">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calculator"></i> Resumen</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Subtotal (sin impuestos):</th>
                                <td class="text-right" id="subtotalDisplay">Bs. 0.00</td>
                            </tr>
                            <tr>
                                <th>IVA (13%):</th>
                                <td class="text-right" id="ivaDisplay">Bs. 0.00</td>
                            </tr>
                            <tr>
                                <th>Precio Factura:</th>
                                <td class="text-right" id="precioFacturaDisplay">Bs. 0.00</td>
                            </tr>
                            <tr>
                                <th>IT (3%):</th>
                                <td class="text-right" id="itDisplay">Bs. 0.00</td>
                            </tr>
                            <tr class="table-success">
                                <th><h4>TOTAL A COBRAR:</h4></th>
                                <td class="text-right"><h4 id="totalDisplay">Bs. 0.00</h4></td>
                            </tr>
                        </table>

                        <input type="hidden" name="subtotal" id="subtotal" value="0">
                        <input type="hidden" name="iva" id="iva" value="0">
                        <input type="hidden" name="it" id="it" value="0">
                        <input type="hidden" name="total" id="total" value="0">

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Sistema PEPS:</strong> Los costos se descontarán automáticamente siguiendo el método PEPS (Primero en Entrar, Primero en Salir).
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-lg btn-block" id="btnGuardar" disabled>
                            <i class="fas fa-save"></i> Registrar Venta
                        </button>
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
<script>
let detallesVenta = [];
let contadorDetalles = 0;

function agregarProducto() {
    const select = document.getElementById('producto_id');
    const option = select.options[select.selectedIndex];
    
    if (!option.value) {
        alert('Seleccione un producto');
        return;
    }

    const productoId = option.value;
    const nombre = option.dataset.nombre;
    const precio = parseFloat(option.dataset.precio);
    const precioFactura = parseFloat(option.dataset.precioFactura);
    const stockDisponible = parseFloat(option.dataset.stock);

    if (stockDisponible <= 0) {
        alert('No hay stock disponible para este producto');
        return;
    }

    // Verificar si ya existe
    const existe = detallesVenta.find(d => d.producto_id == productoId);
    if (existe) {
        alert('El producto ya está en la lista');
        return;
    }

    const detalle = {
        id: ++contadorDetalles,
        producto_id: productoId,
        nombre: nombre,
        cantidad: 1,
        precio_unitario: precio,
        precio_factura: precioFactura,
        subtotal: precio,
        stockDisponible: stockDisponible
    };

    detallesVenta.push(detalle);
    renderizarDetalles();
    calcularTotales();
    select.value = '';
}

function eliminarDetalle(id) {
    detallesVenta = detallesVenta.filter(d => d.id !== id);
    renderizarDetalles();
    calcularTotales();
}

function actualizarCantidad(id, cantidad) {
    const detalle = detallesVenta.find(d => d.id === id);
    if (detalle) {
        cantidad = parseFloat(cantidad);
        if (cantidad > detalle.stockDisponible) {
            alert(`Stock disponible: ${detalle.stockDisponible}`);
            cantidad = detalle.stockDisponible;
        }
        if (cantidad <= 0) cantidad = 1;
        
        detalle.cantidad = cantidad;
        detalle.subtotal = detalle.precio_unitario * cantidad;
        renderizarDetalles();
        calcularTotales();
    }
}

function renderizarDetalles() {
    const tbody = document.getElementById('detallesBody');
    const emptyRow = document.getElementById('emptyRow');
    
    if (detallesVenta.length === 0) {
        emptyRow.style.display = '';
        document.getElementById('btnGuardar').disabled = true;
        return;
    }
    
    emptyRow.style.display = 'none';
    document.getElementById('btnGuardar').disabled = false;
    
    let html = '';
    detallesVenta.forEach((detalle, index) => {
        html += `
            <tr>
                <td>
                    ${detalle.nombre}
                    <input type="hidden" name="detalles[${index}][producto_id]" value="${detalle.producto_id}">
                </td>
                <td>
                    <input type="number" 
                           name="detalles[${index}][cantidad]" 
                           class="form-control form-control-sm" 
                           value="${detalle.cantidad}" 
                           min="0.01" 
                           step="0.01"
                           max="${detalle.stockDisponible}"
                           onchange="actualizarCantidad(${detalle.id}, this.value)">
                </td>
                <td class="text-right">
                    Bs. ${detalle.precio_unitario.toFixed(2)}
                    <input type="hidden" name="detalles[${index}][precio_unitario]" value="${detalle.precio_unitario}">
                </td>
                <td class="text-right">
                    Bs. ${detalle.subtotal.toFixed(2)}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${detalle.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html + emptyRow.outerHTML;
}

function calcularTotales() {
    const subtotal = detallesVenta.reduce((sum, d) => sum + d.subtotal, 0);
    
    // Aplicar tasa efectiva 14.91% para precio factura
    const precioFactura = subtotal * 1.1491;
    
    // IVA 13% sobre el subtotal
    const iva = subtotal * 0.13;
    
    // IT 3% sobre precio factura (NO se cobra al cliente, lo asume la empresa)
    const it = precioFactura * 0.03;
    
    // Total a cobrar AL CLIENTE (sin IT)
    const total = precioFactura;
    
    // Actualizar displays
    document.getElementById('subtotalDisplay').textContent = 'Bs. ' + subtotal.toFixed(2);
    document.getElementById('ivaDisplay').textContent = 'Bs. ' + iva.toFixed(2);
    document.getElementById('precioFacturaDisplay').textContent = 'Bs. ' + precioFactura.toFixed(2);
    document.getElementById('itDisplay').textContent = 'Bs. ' + it.toFixed(2);
    document.getElementById('totalDisplay').textContent = 'Bs. ' + total.toFixed(2);
    
    // Actualizar campos ocultos
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('iva').value = iva.toFixed(2);
    document.getElementById('it').value = it.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

// Inicializar Select2
$(document).ready(function() {
    $('#cliente_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Buscar cliente...',
        allowClear: true
    });
});

// Guardar nuevo cliente
$('#formNuevoCliente').on('submit', function(e) {
    e.preventDefault();
    
    const btnGuardar = $('#btnGuardarCliente');
    btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    
    $.ajax({
        url: '{{ route("clientes.ajax.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Agregar el nuevo cliente al select
                const nuevoCliente = response.cliente;
                const displayText = nuevoCliente.nombre + ' - ' + (nuevoCliente.nit || nuevoCliente.ci);
                const newOption = new Option(displayText, nuevoCliente.id, true, true);
                $('#cliente_id').append(newOption).trigger('change');
                
                // Cerrar modal y resetear formulario
                $('#modalNuevoCliente').modal('hide');
                $('#formNuevoCliente')[0].reset();
                
                // Mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente creado',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            let errorMsg = 'Error al guardar el cliente';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                errorMsg = errors.join('<br>');
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMsg
            });
        },
        complete: function() {
            btnGuardar.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Cliente');
        }
    });
});

// Limpiar formulario al cerrar modal
$('#modalNuevoCliente').on('hidden.bs.modal', function () {
    $('#formNuevoCliente')[0].reset();
});
</script>
@stop

{{-- Modal Nuevo Cliente --}}
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nuevo Cliente</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoCliente">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_nombre">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="modal_nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_tipo">Tipo <span class="text-danger">*</span></label>
                                <select name="tipo" id="modal_tipo" class="form-control" required>
                                    <option value="persona">Persona</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_nit">NIT</label>
                                <input type="text" name="nit" id="modal_nit" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_ci">CI</label>
                                <input type="text" name="ci" id="modal_ci" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_telefono">Teléfono <span class="text-danger">*</span></label>
                                <input type="text" name="telefono" id="modal_telefono" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_email">Email</label>
                                <input type="email" name="email" id="modal_email" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCliente">
                        <i class="fas fa-save"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
