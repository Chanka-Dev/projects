@extends('layouts.app')

@section('title', 'Nueva Compra')

@section('page_header')
    <h1><i class="fas fa-truck"></i> Nueva Compra</h1>
@stop

@section('page_content')
    <form action="{{ route('compras.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Información de Compra</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="proveedor_id">Proveedor <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="proveedor_id" id="proveedor_id" class="form-control select2 @error('proveedor_id') is-invalid @enderror" required style="width: 100%;">
                                            <option value="">Buscar proveedor...</option>
                                            @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->nombre }} - {{ $proveedor->nit }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoProveedor">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('proveedor_id')
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
                                    <label for="numero_factura">Nº Factura <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_factura" id="numero_factura" class="form-control @error('numero_factura') is-invalid @enderror" 
                                           value="{{ old('numero_factura') }}" required>
                                    @error('numero_factura')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control">{{ old('observaciones') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5><i class="fas fa-boxes"></i> Productos</h5>

                        <div class="form-group">
                            <label>Agregar Producto</label>
                            <div class="input-group">
                                <select id="producto_id" class="form-control">
                                    <option value="">Buscar producto...</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" 
                                                data-nombre="{{ $producto->nombre }}">
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="agregarProducto()">
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
                                        <th width="120">Cantidad</th>
                                        <th width="150">Precio Unitario (con IVA)</th>
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
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Importante:</strong> El precio que ingresa ya incluye IVA (precio facturado). 
                            El sistema calculará automáticamente:
                            <ul class="mb-0 mt-2">
                                <li>Crédito Fiscal IVA: 13% directo sobre el precio factura</li>
                                <li>Costo para Inventario: Precio factura - Crédito fiscal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calculator"></i> Resumen</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Total Factura (con IVA):</th>
                                <td class="text-right" id="totalFacturaDisplay">Bs. 0.00</td>
                            </tr>
                            <tr class="table-info">
                                <th>Crédito Fiscal IVA (13%):</th>
                                <td class="text-right" id="creditoFiscalDisplay">Bs. 0.00</td>
                            </tr>
                            <tr class="table-success">
                                <th><h4>Costo Inventario:</h4></th>
                                <td class="text-right"><h4 id="costoInventarioDisplay">Bs. 0.00</h4></td>
                            </tr>
                        </table>

                        <input type="hidden" name="total" id="total" value="0">
                        <input type="hidden" name="credito_fiscal" id="credito_fiscal" value="0">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="btnGuardar" disabled>
                            <i class="fas fa-save"></i> Registrar Compra
                        </button>
                        <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-block">
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
let detallesCompra = [];
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

    // Verificar si ya existe
    const existe = detallesCompra.find(d => d.producto_id == productoId);
    if (existe) {
        alert('El producto ya está en la lista');
        return;
    }

    const detalle = {
        id: ++contadorDetalles,
        producto_id: productoId,
        nombre: nombre,
        cantidad: 1,
        precio_factura: 0,
        subtotal: 0
    };

    detallesCompra.push(detalle);
    renderizarDetalles();
    calcularTotales();
    select.value = '';
}

function eliminarDetalle(id) {
    detallesCompra = detallesCompra.filter(d => d.id !== id);
    renderizarDetalles();
    calcularTotales();
}

function actualizarCantidad(id, cantidad) {
    const detalle = detallesCompra.find(d => d.id === id);
    if (detalle) {
        cantidad = parseFloat(cantidad);
        if (cantidad <= 0) cantidad = 1;
        
        detalle.cantidad = cantidad;
        detalle.subtotal = detalle.precio_factura * cantidad;
        renderizarDetalles();
        calcularTotales();
    }
}

function actualizarPrecio(id, precio) {
    const detalle = detallesCompra.find(d => d.id === id);
    if (detalle) {
        precio = parseFloat(precio);
        if (isNaN(precio) || precio < 0) precio = 0;
        
        detalle.precio_factura = precio;
        detalle.subtotal = detalle.cantidad * precio;
        renderizarDetalles();
        calcularTotales();
    }
}

function renderizarDetalles() {
    const tbody = document.getElementById('detallesBody');
    const emptyRow = document.getElementById('emptyRow');
    
    if (detallesCompra.length === 0) {
        emptyRow.style.display = '';
        document.getElementById('btnGuardar').disabled = true;
        return;
    }
    
    emptyRow.style.display = 'none';
    document.getElementById('btnGuardar').disabled = false;
    
    let html = '';
    detallesCompra.forEach(detalle => {
        html += `
            <tr>
                <td>
                    ${detalle.nombre}
                    <input type="hidden" name="detalles[${detalle.id}][producto_id]" value="${detalle.producto_id}">
                </td>
                <td>
                    <input type="number" 
                           name="detalles[${detalle.id}][cantidad]" 
                           class="form-control form-control-sm" 
                           value="${detalle.cantidad}" 
                           min="0.01" 
                           step="0.01"
                           onchange="actualizarCantidad(${detalle.id}, this.value)">
                </td>
                <td>
                    <input type="number" 
                           name="detalles[${detalle.id}][precio_unitario]" 
                           class="form-control form-control-sm" 
                           value="${detalle.precio_factura}" 
                           min="0" 
                           step="0.01"
                           placeholder="Precio con IVA"
                           onchange="actualizarPrecio(${detalle.id}, this.value)">
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
    const totalFactura = detallesCompra.reduce((sum, d) => sum + d.subtotal, 0);
    
    // Crédito fiscal: 13% directo sobre total factura
    const creditoFiscal = totalFactura * 0.13;
    
    // Costo inventario: total - crédito fiscal
    const costoInventario = totalFactura - creditoFiscal;
    
    // Actualizar displays
    document.getElementById('totalFacturaDisplay').textContent = 'Bs. ' + totalFactura.toFixed(2);
    document.getElementById('creditoFiscalDisplay').textContent = 'Bs. ' + creditoFiscal.toFixed(2);
    document.getElementById('costoInventarioDisplay').textContent = 'Bs. ' + costoInventario.toFixed(2);
    
    // Actualizar campos ocultos
    document.getElementById('total').value = totalFactura.toFixed(2);
    document.getElementById('credito_fiscal').value = creditoFiscal.toFixed(2);
}

// Inicializar Select2
$(document).ready(function() {
    $('#proveedor_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Buscar proveedor...',
        allowClear: true
    });
});

// Guardar nuevo proveedor
$('#formNuevoProveedor').on('submit', function(e) {
    e.preventDefault();
    
    const btnGuardar = $('#btnGuardarProveedor');
    btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    
    $.ajax({
        url: '{{ route("proveedores.ajax.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Agregar el nuevo proveedor al select
                const nuevoProveedor = response.proveedor;
                const displayText = nuevoProveedor.nombre + ' - ' + (nuevoProveedor.nit || nuevoProveedor.ci);
                const newOption = new Option(displayText, nuevoProveedor.id, true, true);
                $('#proveedor_id').append(newOption).trigger('change');
                
                // Cerrar modal y resetear formulario
                $('#modalNuevoProveedor').modal('hide');
                $('#formNuevoProveedor')[0].reset();
                
                // Mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Proveedor creado',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            let errorMsg = 'Error al guardar el proveedor';
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
            btnGuardar.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Proveedor');
        }
    });
});

// Limpiar formulario al cerrar modal
$('#modalNuevoProveedor').on('hidden.bs.modal', function () {
    $('#formNuevoProveedor')[0].reset();
});
</script>
@stop

{{-- Modal Nuevo Proveedor --}}
<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-truck-loading"></i> Nuevo Proveedor</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoProveedor">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_nombre">Nombre/Razón Social <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="modal_nombre" class="form-control" required>
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
                        <div class="col-md-12">
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
                    <button type="submit" class="btn btn-primary" id="btnGuardarProveedor">
                        <i class="fas fa-save"></i> Guardar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
