{{-- Partial compartido entre create y edit --}}

{{-- Datos básicos --}}
<div class="card mb-3">
    <div class="card-header"><strong>Información general</strong></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $receta->titulo ?? '') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Etiqueta <span class="text-danger">*</span></label>
                    <select name="etiqueta_id" class="form-control" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($etiquetas as $et)
                        <option value="{{ $et->id }}" {{ old('etiqueta_id', $receta->etiqueta_id ?? '') == $et->id ? 'selected' : '' }}>
                            {{ $et->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Fuente <small class="text-muted">(opcional)</small></label>
                    <input type="text" name="fuente" class="form-control" value="{{ old('fuente', $receta->fuente ?? '') }}" placeholder="Libro, revista, abuela...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Link <small class="text-muted">(opcional)</small></label>
                    <input type="url" name="link" class="form-control" value="{{ old('link', $receta->link ?? '') }}" placeholder="https://...">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Imagen <small class="text-muted">(opcional)</small></label>
            @if(!empty($receta->imagen))
            <div class="mb-1"><img src="{{ Storage::url($receta->imagen) }}" style="height:80px;border-radius:4px" alt="imagen actual"></div>
            @endif
            <input type="file" name="imagen" class="form-control-file" accept="image/*">
        </div>
    </div>
</div>

{{-- Ingredientes generales --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Ingredientes generales</strong>
        <small class="text-muted">Para ingredientes que no pertenecen a ninguna parte específica</small>
    </div>
    <div class="card-body">
        <div id="ings-generales">
            @php $ingsGenerales = $receta ? $receta->ingredientes->whereNull('receta_parte_id') : collect(); @endphp
            @forelse($ingsGenerales as $ri)
            <div class="ing-row row mb-1">
                <div class="col-md-3">
                    <input type="text" name="ing_general[][cantidad]" class="form-control form-control-sm" value="{{ $ri->cantidad }}" placeholder="Cantidad">
                </div>
                <div class="col-md-5">
                    <select name="ing_general[][ingrediente_id]" class="form-control form-control-sm">
                        <option value="">-- Ingrediente --</option>
                        @foreach($ingredientes as $ing)
                        <option value="{{ $ing->id }}" {{ $ri->ingrediente_id == $ing->id ? 'selected' : '' }}>{{ $ing->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="ing_general[][notas]" class="form-control form-control-sm" value="{{ $ri->notas }}" placeholder="Notas (remojadas...)">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                </div>
            </div>
            @empty
            <div class="ing-row row mb-1">
                <div class="col-md-3"><input type="text" name="ing_general[][cantidad]" class="form-control form-control-sm" placeholder="Cantidad"></div>
                <div class="col-md-5">
                    <select name="ing_general[][ingrediente_id]" class="form-control form-control-sm">
                        <option value="">-- Ingrediente --</option>
                        @foreach($ingredientes as $ing)<option value="{{ $ing->id }}">{{ $ing->nombre }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3"><input type="text" name="ing_general[][notas]" class="form-control form-control-sm" placeholder="Notas"></div>
                <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button></div>
            </div>
            @endforelse
        </div>
        <button type="button" class="btn btn-outline-success btn-sm mt-2" id="add-ing-general">
            <i class="fas fa-plus"></i> Agregar ingrediente
        </button>
    </div>
</div>

{{-- Instrucciones generales --}}
<div class="card mb-3">
    <div class="card-header"><strong>Instrucciones generales</strong> <small class="text-muted">(si no usas partes)</small></div>
    <div class="card-body">
        <textarea name="instrucciones" class="form-control" rows="4" placeholder="Paso 1: ...\nPaso 2: ...">{{ old('instrucciones', $receta->instrucciones ?? '') }}</textarea>
    </div>
</div>

{{-- Partes de la receta --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Partes de la receta</strong>
        <button type="button" class="btn btn-success btn-sm" id="add-parte"><i class="fas fa-plus"></i> Agregar parte</button>
    </div>
    <div class="card-body" id="partes-container">
        @if($receta)
        @foreach($receta->partes as $pi => $parte)
        <div class="parte-block border rounded p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <input type="text" name="partes[{{ $pi }}][titulo]" class="form-control col-md-8" value="{{ $parte->titulo }}" placeholder="Nombre de la parte (ej: Para la mayonesa)">
                <button type="button" class="btn btn-outline-danger btn-sm remove-parte"><i class="fas fa-trash"></i> Quitar parte</button>
            </div>
            <label class="text-muted">Ingredientes de esta parte</label>
            <div class="ings-parte">
                @foreach($parte->ingredientes as $ri)
                <div class="ing-row row mb-1">
                    <div class="col-md-3"><input type="text" name="partes[{{ $pi }}][ingredientes][][cantidad]" class="form-control form-control-sm" value="{{ $ri->cantidad }}" placeholder="Cantidad"></div>
                    <div class="col-md-5">
                        <select name="partes[{{ $pi }}][ingredientes][][ingrediente_id]" class="form-control form-control-sm">
                            <option value="">-- Ingrediente --</option>
                            @foreach($ingredientes as $ing)<option value="{{ $ing->id }}" {{ $ri->ingrediente_id == $ing->id ? 'selected' : '' }}>{{ $ing->nombre }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><input type="text" name="partes[{{ $pi }}][ingredientes][][notas]" class="form-control form-control-sm" value="{{ $ri->notas }}" placeholder="Notas"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button></div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-success btn-sm add-ing-parte mt-1"><i class="fas fa-plus"></i> Ingrediente</button>
            <div class="form-group mt-2">
                <label class="text-muted">Instrucciones de esta parte</label>
                <textarea name="partes[{{ $pi }}][instrucciones]" class="form-control" rows="3" placeholder="Preparación específica para esta parte...">{{ $parte->instrucciones }}</textarea>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>

{{-- Templates JS --}}
<template id="tpl-ing">
    <div class="ing-row row mb-1">
        <div class="col-md-3"><input type="text" name="__name__[][cantidad]" class="form-control form-control-sm" placeholder="Cantidad"></div>
        <div class="col-md-5">
            <select name="__name__[][ingrediente_id]" class="form-control form-control-sm">
                <option value="">-- Ingrediente --</option>
                @foreach($ingredientes as $ing)<option value="{{ $ing->id }}">{{ $ing->nombre }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3"><input type="text" name="__name__[][notas]" class="form-control form-control-sm" placeholder="Notas"></div>
        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button></div>
    </div>
</template>

<template id="tpl-parte">
    <div class="parte-block border rounded p-3 mb-3">
        <div class="d-flex justify-content-between mb-2">
            <input type="text" name="partes[__idx__][titulo]" class="form-control col-md-8" placeholder="Nombre de la parte (ej: Para la mayonesa)">
            <button type="button" class="btn btn-outline-danger btn-sm remove-parte"><i class="fas fa-trash"></i> Quitar parte</button>
        </div>
        <label class="text-muted">Ingredientes de esta parte</label>
        <div class="ings-parte"></div>
        <button type="button" class="btn btn-outline-success btn-sm add-ing-parte mt-1"><i class="fas fa-plus"></i> Ingrediente</button>
        <div class="form-group mt-2">
            <label class="text-muted">Instrucciones de esta parte</label>
            <textarea name="partes[__idx__][instrucciones]" class="form-control" rows="3" placeholder="Preparación específica para esta parte..."></textarea>
        </div>
    </div>
</template>

<script>
(function() {
    let parteIdx = {{ $receta ? $receta->partes->count() : 0 }};

    function cloneIng(namePrefix) {
        const tpl = document.getElementById('tpl-ing').content.cloneNode(true);
        tpl.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace('__name__', namePrefix);
        });
        return tpl;
    }

    document.getElementById('add-ing-general').addEventListener('click', () => {
        const cont = document.getElementById('ings-generales');
        cont.appendChild(cloneIng('ing_general'));
    });

    document.getElementById('add-parte').addEventListener('click', () => {
        const tpl = document.getElementById('tpl-parte').content.cloneNode(true);
        const idx = parteIdx++;
        tpl.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace('__idx__', idx);
        });
        document.getElementById('partes-container').appendChild(tpl);
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.ing-row').remove();
        }
        if (e.target.closest('.remove-parte')) {
            e.target.closest('.parte-block').remove();
        }
        if (e.target.closest('.add-ing-parte')) {
            const btn = e.target.closest('.add-ing-parte');
            const parte = btn.closest('.parte-block');
            const idxMatch = parte.querySelector('[name*="partes["]')?.name.match(/partes\[(\d+)\]/);
            if (!idxMatch) return;
            const prefix = `partes[${idxMatch[1]}][ingredientes]`;
            const cont = parte.querySelector('.ings-parte');
            cont.appendChild(cloneIng(prefix));
        }
    });
})();
</script>
