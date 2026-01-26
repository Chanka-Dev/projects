@extends('adminlte::page')

@section('title', 'Perfil de Trabajadora')

@section('content_header')
    <h1>Perfil de {{ $trabajadora->nombre }}</h1>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-purple-theme.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $trabajadora->id }}</p>
            <p><strong>Nombre:</strong> {{ $trabajadora->nombre }}</p>
            <p><strong>Tipo de Contrato:</strong> {{ ucfirst($trabajadora->tipo_contrato) }}</p>
            <p><strong>Activo:</strong> 
                @if($trabajadora->activo)
                    <span class="badge badge-success">Sí</span>
                @else
                    <span class="badge badge-danger">No</span>
                @endif
            </p>
            
            <hr>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Nota:</strong> Las comisiones ahora se configuran individualmente en cada servicio.
                Puedes ver los pagos de esta trabajadora en el módulo "Pagos a Trabajadoras".
            </div>
            
            <a href="{{ route('trabajadoras.edit', $trabajadora) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('pago-trabajadoras.index', ['trabajadora_id' => $trabajadora->id]) }}" class="btn btn-primary">
                <i class="fas fa-money-bill-wave"></i> Ver Pagos
            </a>
            <a href="{{ route('trabajadoras.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection