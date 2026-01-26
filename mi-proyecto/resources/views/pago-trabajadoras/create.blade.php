@extends('adminlte::page')

@section('title', 'Crear Pago a Trabajadora')

@section('content_header')
    <h1>Generar Pago a Trabajadora</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pago-trabajadoras.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="trabajadora_id">Trabajadora <span class="text-danger">*</span></label>
                            <select name="trabajadora_id" id="trabajadora_id" class="form-control @error('trabajadora_id') is-invalid @enderror" required>
                                <option value="">Seleccione una trabajadora</option>
                                @foreach($trabajadoras as $trabajadora)
                                    <option value="{{ $trabajadora->id }}" {{ old('trabajadora_id', request('trabajadora_id')) == $trabajadora->id ? 'selected' : '' }}>
                                        {{ $trabajadora->nombre }}
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
                            <label for="fecha_inicio_periodo">Fecha Inicio Periodo <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_inicio_periodo" id="fecha_inicio_periodo" 
                                   class="form-control @error('fecha_inicio_periodo') is-invalid @enderror" 
                                   value="{{ old('fecha_inicio_periodo', request('fecha_inicio')) }}" required>
                            @error('fecha_inicio_periodo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_fin_periodo">Fecha Fin Periodo <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_fin_periodo" id="fecha_fin_periodo" 
                                   class="form-control @error('fecha_fin_periodo') is-invalid @enderror" 
                                   value="{{ old('fecha_fin_periodo', request('fecha_fin')) }}" required>
                            @error('fecha_fin_periodo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Nota:</strong> Se generará un pago con todos los servicios realizados en el periodo seleccionado 
                    que aún no han sido pagados. El sistema calculará automáticamente las comisiones.
                </div>

                <div id="preview-servicios"></div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calculator"></i> Calcular y Generar Pago
                    </button>
                    <a href="{{ route('pago-trabajadoras.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Calcular automáticamente el domingo de la semana cuando se selecciona el lunes
    document.getElementById('fecha_inicio_periodo').addEventListener('change', function() {
        const fechaInicio = new Date(this.value);
        const fechaFin = new Date(fechaInicio);
        fechaFin.setDate(fechaInicio.getDate() + 6);
        
        const year = fechaFin.getFullYear();
        const month = String(fechaFin.getMonth() + 1).padStart(2, '0');
        const day = String(fechaFin.getDate()).padStart(2, '0');
        
        document.getElementById('fecha_fin_periodo').value = `${year}-${month}-${day}`;
    });

    // Cargar preview de servicios al cambiar trabajadora o fechas
    async function cargarPreview() {
        const trabajadoraId = document.getElementById('trabajadora_id').value;
        const fechaInicio = document.getElementById('fecha_inicio_periodo').value;
        const fechaFin = document.getElementById('fecha_fin_periodo').value;
        
        if (!trabajadoraId || !fechaInicio || !fechaFin) {
            document.getElementById('preview-servicios').innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`{{ route('pago-trabajadoras.index') }}?preview=1&trabajadora_id=${trabajadoraId}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`);
            // Por ahora solo mostramos un mensaje
            const previewDiv = document.getElementById('preview-servicios');
            previewDiv.innerHTML = '<div class="alert alert-secondary"><i class="fas fa-spinner fa-spin"></i> Calculando servicios del periodo...</div>';
        } catch (error) {
            console.error('Error cargando preview:', error);
        }
    }

    document.getElementById('trabajadora_id').addEventListener('change', cargarPreview);
    document.getElementById('fecha_fin_periodo').addEventListener('change', cargarPreview);
</script>
@endsection
