<div class="modal fade" id="createInstallationModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('installations.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva instalación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lugar</label>
                            <select name="place_id" id="placeSelect" class="form-select" required
                                onchange="onPlaceChange()">
                                <option value="">Selecciona un lugar</option>
                                @foreach($places as $place)
                                    <option value="{{ $place->id }}" data-max-limiters="{{ $place->max_limiters }}">
                                        {{ $place->name }}
                                    </option>

                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="installation_date" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Limitadores</label>
                            <select name="limiters_installed" id="limiters" class="form-select" required>
                                <option value="">Selecciona un lugar primero</option>
                            </select>

                        </div>
                    </div>

                    <hr>
                    <h6>Sub-sitios y archivos</h6>

                    <div id="subSitesContainer"></div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const places = @json($places);

    function onPlaceChange() {
        const placeSelect = document.getElementById('placeSelect');
        const placeId = placeSelect.value;

        const container = document.getElementById('subSitesContainer');
        const limitersSelect = document.getElementById('limiters');

        container.innerHTML = '';
        limitersSelect.innerHTML = '';

        if (!placeId) {
            limitersSelect.innerHTML = '<option value="">Selecciona un lugar primero</option>';
            return;
        }

        const place = places.find(p => p.id == placeId);

        // 1️⃣ Rellenar selector de limitadores
        for (let i = 1; i <= place.max_limiters; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            limitersSelect.appendChild(option);
        }

        // 2️⃣ Renderizar sub-sitios
        place.sub_sites.forEach(subSite => {
            container.insertAdjacentHTML('beforeend', `
                <div class="border rounded p-3 mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox"
                               name="sub_sites[]" value="${subSite.id}" id="ss_${subSite.id}">
                        <label class="form-check-label" for="ss_${subSite.id}">
                            ${subSite.name}
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>CSV</label>
                            <input type="file" name="files[${subSite.id}][csv]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>PDF Programación</label>
                            <input type="file" name="files[${subSite.id}][pdf_programming]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>PDF Instalación</label>
                            <input type="file" name="files[${subSite.id}][pdf_installation]" class="form-control" required>
                        </div>
                    </div>
                </div>
            `);
        });
    }
</script>