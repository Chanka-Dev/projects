@extends('layouts.app')
@section('title', $receta->titulo)

@section('content')
<div class="row mb-2">
    <div class="col-sm-8">
        <h2>{{ $receta->titulo }}
            <span class="badge ml-2" style="font-size:.6em;background-color:{{ $receta->etiqueta->color ?? '#17a2b8' }};color:#fff">{{ $receta->etiqueta->nombre }}</span>
        </h2>
        <small class="text-muted">Por {{ $receta->user->name }} &mdash; {{ $receta->created_at->format('d/m/Y') }}</small>
    </div>
    <div class="col-sm-4 text-right">
        @can('recipe-edit')
        <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i> Editar</a>
        @endcan
        <a href="{{ route('recetas.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>
</div>

<div class="row">
    {{-- Columna imagen + meta --}}
    <div class="col-md-4">
        @if($receta->imagen)
        <img src="{{ asset($receta->imagen) }}" class="img-fluid rounded mb-3" alt="{{ $receta->titulo }}">
        @endif
        <div class="card mb-3">
            <div class="card-body">
                @if($receta->fuente)
                <p><strong><i class="fas fa-book mr-1"></i> Fuente:</strong> {{ $receta->fuente }}</p>
                @endif
                @if($receta->link)
                <p><strong><i class="fas fa-link mr-1"></i> Link:</strong>
                    <a href="{{ $receta->link }}" target="_blank" rel="noopener noreferrer">{{ $receta->link }}</a>
                </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Columna ingredientes + instrucciones --}}
    <div class="col-md-8">

        {{-- Ingredientes generales (sin parte) --}}
        @php $ingsGenerales = $receta->ingredientes->whereNull('receta_parte_id'); @endphp
        @if($ingsGenerales->isNotEmpty() || $receta->partes->isEmpty())
        <div class="card mb-3">
            <div class="card-header"><strong><i class="fas fa-list mr-1"></i> Ingredientes</strong></div>
            <ul class="list-group list-group-flush">
                @foreach($ingsGenerales as $ri)
                <li class="list-group-item py-1">
                    {{ $ri->cantidad }} {{ $ri->ingrediente->nombre }}
                    @if($ri->notas) <small class="text-muted">({{ $ri->notas }})</small> @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Partes --}}
        @foreach($receta->partes as $parte)
        <div class="card mb-3">
            <div class="card-header bg-light"><strong>{{ $parte->titulo }}</strong></div>
            <div class="card-body">
                @if($parte->ingredientes->isNotEmpty())
                <h6>Ingredientes</h6>
                <ul class="list-unstyled">
                    @foreach($parte->ingredientes as $ri)
                    <li><i class="fas fa-circle mr-1" style="font-size:.5rem;vertical-align:middle"></i>
                        {{ $ri->cantidad }} {{ $ri->ingrediente->nombre }}
                        @if($ri->notas) <small class="text-muted">({{ $ri->notas }})</small> @endif
                    </li>
                    @endforeach
                </ul>
                @endif
                @if($parte->instrucciones)
                <h6 class="mt-2">Preparación</h6>
                <p style="white-space:pre-line">{{ $parte->instrucciones }}</p>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Instrucciones generales --}}
        @if($receta->instrucciones)
        <div class="card mb-3">
            <div class="card-header"><strong><i class="fas fa-clipboard-list mr-1"></i> Preparación</strong></div>
            <div class="card-body">
                <p style="white-space:pre-line">{{ $receta->instrucciones }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
