@extends('layouts.app')
@section('title', 'Editar Etiqueta')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6"><h3>Editar Etiqueta</h3></div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('etiquetas.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        <form method="POST" action="{{ route('etiquetas.update', $etiqueta) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $etiqueta->nombre) }}">
            </div>
            <div class="form-group">
                <label>Color <small class="text-muted">(opcional)</small></label>
                <div class="input-group" style="max-width:220px">
                    <div class="input-group-prepend">
                        <span class="input-group-text p-1">
                            <input type="color" id="colorPicker" value="{{ old('color', $etiqueta->color ?? '#3498db') }}" style="width:32px;height:32px;border:none;cursor:pointer;padding:0">
                        </span>
                    </div>
                    <input type="text" name="color" id="colorHex" class="form-control" value="{{ old('color', $etiqueta->color) }}" placeholder="#3498db" maxlength="7">
                </div>
                <small class="text-muted">Se usará para resaltar la etiqueta visualmente.</small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const picker = document.getElementById('colorPicker');
    const hex    = document.getElementById('colorHex');
    picker.addEventListener('input', () => hex.value = picker.value);
    hex.addEventListener('input', () => { if (/^#[0-9A-Fa-f]{6}$/.test(hex.value)) picker.value = hex.value; });
</script>
@endpush
@endsection
