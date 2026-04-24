@extends('layouts.app')
@section('title', 'Editar Receta')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6"><h3>Editar: {{ $receta->titulo }}</h3></div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('recetas.show', $receta) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ route('recetas.update', $receta) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('recetas._form', ['receta' => $receta])
    <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save"></i> Actualizar receta</button>
</form>
@endsection
