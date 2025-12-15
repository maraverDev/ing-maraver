<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label> Nombre del lugar <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror" required>

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>
                Número de limitadores <span class="text-danger">*</span>
            </label>

            <select name="max_limiters" class="form-control @error('max_limiters') is-invalid @enderror" required>
                <option value="">Selecciona</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('max_limiters') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>

            @error('max_limiters')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

    </div>
</div>