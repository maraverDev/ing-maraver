<form method="GET" action="{{ route('installations.index') }}">

    {{-- Buscador por lugar --}}
    <div class="form-group">
        <label>Buscar lugar</label>
        <input type="text" name="search" class="form-control" placeholder="Nombre del lugar"
            value="{{ request('search') }}">
    </div>

    {{-- Filtro por lugar --}}
    <div class="form-group">
        <label>Lugar</label>
        <select name="place_id" class="form-control">
            <option value="">Todos</option>
            @foreach($places as $place)
                <option value="{{ $place->id }}" {{ request('place_id') == $place->id ? 'selected' : '' }}>
                    {{ $place->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Rango de fechas --}}
    <div class="form-row">
        <div class="form-group col-6">
            <label>Desde</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>

        <div class="form-group col-6">
            <label>Hasta</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('installations.index') }}" class="btn btn-light btn-sm">
            Limpiar
        </a>

        <button type="submit" class="btn btn-primary btn-sm">
            Aplicar filtros
        </button>
    </div>

</form>