@extends('adminlte::page')

@section('title', 'Generar Pago Semanal')

@section('content_header')
    <h1>Generar Pago Semanal</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title">Seleccionar Trabajadora para Pago Semanal</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Generación Automática de Pago Semanal</strong><br>
                Esta función generará automáticamente un pago para la semana actual (de lunes a domingo).
                El sistema calculará todos los servicios realizados en este periodo que aún no han sido pagados.
            </div>

            <form action="{{ route('pago-trabajadoras.generar-semanal') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="form-group">
                            <label for="trabajadora_id">Seleccione la Trabajadora <span class="text-danger">*</span></label>
                            <select name="trabajadora_id" id="trabajadora_id" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($trabajadoras as $trabajadora)
                                    <option value="{{ $trabajadora->id }}">{{ $trabajadora->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <h5><i class="fas fa-calendar-week"></i> Periodo de la Semana Actual</h5>
                                <p class="mb-0">
                                    <strong>Inicio:</strong> {{ \Carbon\Carbon::now()->startOfWeek()->format('d/m/Y') }} (Lunes)<br>
                                    <strong>Fin:</strong> {{ \Carbon\Carbon::now()->endOfWeek()->format('d/m/Y') }} (Domingo)
                                </p>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-calculator"></i> Generar Pago Semanal
                            </button>
                            <a href="{{ route('pago-trabajadoras.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
