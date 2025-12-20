<form method="GET" action="{{ route('installations.index') }}" class="m-form m-form--fit">

    {{-- Filtro por lugar (Select2) --}}
    <div class="form-group mb-4">
        <label class="form-label fw-bold mb-2">Lugar</label>
        <select name="place_id" class="form-control m-select2" id="select2_filters_place">
            <option value="">Cualquier lugar</option>
            @foreach ($places as $place)
                <option value="{{ $place->id }}" {{ request('place_id') == $place->id ? 'selected' : '' }}>
                    {{ $place->name }}
                </option>
            @endforeach
        </select>
        <span class="m-form__help text-muted fs-8">Busca por el nombre del local de forma rápida</span>
    </div>

    <div class="separator separator-dashed my-4"></div>

    {{-- Rango de fechas --}}
    <div class="form-group mb-0">
        <label class="form-label fw-bold mb-3">Rango de fechas</label>

        {{-- Desde --}}
        <div class="row align-items-center mb-2">
            <div class="col-5">
                <label class="form-label mb-0 fs-8 text-muted fw-bold">Desde:</label>
            </div>
            <div class="col-7">
                <input type="date" name="from" class="form-control form-control-sm border-0 bg-light"
                    value="{{ request('from') }}">
            </div>
        </div>

        {{-- Hasta --}}
        <div class="row align-items-center">
            <div class="col-5">
                <label class="form-label mb-0 fs-8 text-muted fw-bold">Hasta:</label>
            </div>
            <div class="col-7">
                <input type="date" name="to" class="form-control form-control-sm border-0 bg-light"
                    value="{{ request('to') }}">
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex justify-content-between align-items-center mt-5">
        <a href="{{ route('installations.index') }}" class="btn btn-link btn-color-muted border-0 fw-bold px-0">
            Limpiar filtros
        </a>

        <button type="submit" class="btn btn-primary btn-sm px-5 fw-bold">
            Aplicar
        </button>
    </div>

</form>
