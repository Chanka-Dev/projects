@extends('layouts.app')
@section('title', 'Nuevo Ingrediente')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6"><h3>Nuevo Ingrediente</h3></div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('ingredientes.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('ingredientes.store') }}">
            @csrf
            <div class="form-group">
                <label>Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="ej: Pollo, Harina, Limón...">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Unidad de medida <small class="text-muted">(opcional)</small></label>
                        <input type="text" name="unidad_default" class="form-control" value="{{ old('unidad_default') }}" placeholder="ej: taza, gramo, cdita, unidad...">
                        <small class="text-muted">Referencia, no es obligatoria al usar en recetas.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Categoría <small class="text-muted">(opcional)</small></label>
                        <select name="categoria" class="form-control">
                            <option value="">-- Sin categoría --</option>
                            @foreach(['Lácteo','Vegetal','Fruta','Proteína','Cereal','Condimento','Aceite','Endulzante','Líquido','Otro'] as $cat)
                            <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción <small class="text-muted">(opcional)</small></label>
                <textarea name="descripcion" class="form-control" rows="2" placeholder="Notas sobre el ingrediente...">{{ old('descripcion') }}</textarea>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="hidden" name="es_alergeno" value="0">
                    <input type="checkbox" class="custom-control-input" id="esAlergeno" name="es_alergeno" value="1" {{ old('es_alergeno') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="esAlergeno">Es alergéno común <small class="text-muted">(gluten, lácteos, frutos secos...)</small></label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
        </form>
    </div>
</div>
@endsection
