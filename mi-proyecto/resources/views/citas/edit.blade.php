@extends('adminlte::page')

@section('title', 'Editar Cita')

@section('content_header')
    <h1>Editar Cita #{{ $cita->id }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('citas.update', $cita) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cliente_id">Cliente <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="cliente_id" id="cliente_id" class="form-control select2 @error('cliente_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccione un cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $cita->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} - {{ $cliente->telefono }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalNuevoCliente" title="Agregar nuevo cliente">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('cliente_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="trabajadora_id">Trabajadora <span class="text-danger">*</span></label>
                            <select name="trabajadora_id" id="trabajadora_id" class="form-control select2 @error('trabajadora_id') is-invalid @enderror" required>
                                <option value="">-- Seleccione una trabajadora --</option>
                                @foreach($trabajadoras as $trabajadora)
                                    <option value="{{ $trabajadora->id }}" {{ old('trabajadora_id', $cita->trabajadora_id) == $trabajadora->id ? 'selected' : '' }}>
                                        {{ $trabajadora->nombre }} - {{ $trabajadora->especialidad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trabajadora_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha">Fecha de la Cita <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $cita->fecha) }}" required>
                            <small class="form-text text-muted">Puede registrar citas para cualquier fecha (pasadas, presentes o futuras)</small>
                            @error('fecha')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="servicios">Servicios <span class="text-danger">*</span></label>
                    <select name="servicios[]" id="servicios" class="form-control select2 @error('servicios') is-invalid @enderror" multiple required>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ in_array($servicio->id, old('servicios', $cita->servicios->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $servicio->nombre }} - Bs {{ number_format($servicio->precio_base, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Puede seleccionar múltiples servicios manteniendo presionado Ctrl (Windows) o Cmd (Mac)</small>
                    @error('servicios')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control @error('observaciones') is-invalid @enderror" placeholder="Ej: Cliente prefiere tinte sin amoniaco, alergia a ciertos productos, etc.">{{ old('observaciones', $cita->observaciones) }}</textarea>
                    @error('observaciones')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <div class="text-right">
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Nuevo Cliente -->
    <div class="modal fade" id="modalNuevoCliente" tabindex="-1" role="dialog" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalNuevoClienteLabel">
                        <i class="fas fa-user-plus"></i> Agregar Nuevo Cliente
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoCliente">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nuevo_nombre">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nuevo_nombre" name="nombre" required>
                                    <div class="invalid-feedback" id="error_nombre"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nuevo_telefono">Teléfono <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nuevo_telefono" name="telefono" required>
                                    <div class="invalid-feedback" id="error_telefono"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nuevo_email">Email</label>
                            <input type="email" class="form-control" id="nuevo_email" name="email">
                            <div class="invalid-feedback" id="error_email"></div>
                        </div>
                        <div class="form-group">
                            <label for="nuevo_direccion">Dirección</label>
                            <textarea class="form-control" id="nuevo_direccion" name="direccion" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="nuevo_observaciones">Observaciones</label>
                            <textarea class="form-control" id="nuevo_observaciones" name="observaciones" rows="2" placeholder="Ej: Alergias, preferencias, etc."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-success" id="btnGuardarCliente">
                        <i class="fas fa-save"></i> Guardar Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Seleccione una opción'
            });

            // Guardar nuevo cliente vía AJAX
            $('#btnGuardarCliente').click(function() {
                const btn = $(this);
                const form = $('#formNuevoCliente');
                
                // Limpiar errores previos
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');
                
                // Deshabilitar botón
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                
                $.ajax({
                    url: '{{ route("clientes.store") }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Agregar nuevo cliente al select
                        const nuevoCliente = response.cliente;
                        const newOption = new Option(
                            nuevoCliente.nombre + ' - ' + nuevoCliente.telefono,
                            nuevoCliente.id,
                            true,
                            true
                        );
                        $('#cliente_id').append(newOption).trigger('change');
                        
                        // Cerrar modal y limpiar formulario
                        $('#modalNuevoCliente').modal('hide');
                        form[0].reset();
                        
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Cliente agregado!',
                            text: 'El cliente ha sido registrado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Errores de validación
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const input = $('#nuevo_' + field);
                                input.addClass('is-invalid');
                                $('#error_' + field).text(messages[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al guardar el cliente'
                            });
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Cliente');
                    }
                });
            });

            // Limpiar errores al cerrar el modal
            $('#modalNuevoCliente').on('hidden.bs.modal', function () {
                $('#formNuevoCliente')[0].reset();
                $('#formNuevoCliente').find('.is-invalid').removeClass('is-invalid');
                $('#formNuevoCliente').find('.invalid-feedback').text('');
            });
        });
    </script>
@endsection