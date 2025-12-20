<div class="row">

    {{-- LUGAR --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">
                Lugar <span class="text-danger">*</span>
            </label>
            <select name="place_id"
                class="form-control @error('place_id') is-invalid @enderror"
                required>
                <option value="">Selecciona</option>
                @foreach($places as $place)
                    <option value="{{ $place->id }}"
                        {{ old('place_id') == $place->id ? 'selected' : '' }}>
                        {{ $place->name }}
                    </option>
                @endforeach
            </select>
            @error('place_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- FECHA --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">
                Fecha <span class="text-danger">*</span>
            </label>
            <input type="datetime-local"
                name="installation_date"
                class="form-control @error('installation_date') is-invalid @enderror"
                value="{{ old('installation_date') }}"
                required>
            @error('installation_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- ESTADO --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">
                Estado <span class="text-danger">*</span>
            </label>
            <select name="status"
                class="form-control @error('status') is-invalid @enderror"
                required>
                <option value="completed"
                    {{ old('status', 'completed') === 'completed' ? 'selected' : '' }}>
                    Realizada
                </option>
                <option value="pending"
                    {{ old('status') === 'pending' ? 'selected' : '' }}>
                    Pendiente
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>

<div class="row">

    {{-- LIMITADORES --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">
                Limitadores <span class="text-danger">*</span>
            </label>
            <select name="limiters_installed"
                id="limitersSelect"
                class="form-control @error('limiters_installed') is-invalid @enderror"
                {{ old('place_id') ? '' : 'disabled' }}
                required>
                <option value="">Selecciona un lugar primero</option>
            </select>
            @error('limiters_installed')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>
