@extends('adminlte::page')

@section('title', 'Editar Cita-Servicio')

@section('content_header')
    <h1>Editar Cita-Servicio #{{ $citaServicio->id }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('citas-servicios.update', $citaServicio) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="cita_id">Cita</label>
                    <select name="cita_id" id="cita_id" class="form-control @error('cita_id') is-invalid @enderror" required>
                        <option value="">Seleccionar cita</option>
                        @foreach($citas as $cita)
                            <option value="{{ $cita->id }}" {{ old('cita_id', $citaServicio->cita_id) == $cita->id ? 'selected' : '' }}>
                                {{ $cita->cliente->nombre }} - {{ $cita->fecha->format('d/m/Y') }} {{ $cita->hora_inicio->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('cita_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="servicio_id">Servicio</label>
                    <select name="servicio_id" id="servicio_id" class="form-control @error('servicio_id') is-invalid @enderror" required>
                        <option value="">Seleccionar servicio</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('servicio_id', $citaServicio->servicio_id) == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }} ({{ number_format($servicio->precio_base, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="precio_aplicado">Precio Aplicado</label>
                    <input type="number" name="precio_aplicado" id="precio_aplicado" class="form-control @error('precio_aplicado') is-invalid @enderror" value="{{ old('precio_aplicado', $citaServicio->precio_aplicado) }}" step="0.01" required>
                    @error('precio_aplicado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('citas-servicios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection