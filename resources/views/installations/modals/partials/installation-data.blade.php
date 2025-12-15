<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label>Lugar <span class="text-danger">*</span></label>
            <select name="place_id" class="form-control" required>
                <option value="">Selecciona</option>
                @foreach($places as $place)
                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha <span class="text-danger">*</span></label>
            <input type="date" name="installation_date" class="form-control" required>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>
                Limitadores <span class="text-danger">*</span>
            </label>
            <select name="limiters_installed" id="limitersSelect" class="form-control" disabled required>
                <option value="">Selecciona un lugar primero</option>
            </select>
        </div>
    </div>

</div>