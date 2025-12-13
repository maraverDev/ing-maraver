<div class="modal fade" id="createPlaceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="createInstallationForm" method="POST" action="{{ route('installations.store') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo lugar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Máx. limitadores</label>
                        <input type="number" name="max_limiters" class="form-control" min="1" max="5" value="1"
                            required>
                    </div>

                    <hr>
                    <h6>Sub-sitios</h6>

                    <div id="subSitesContainer"></div>

                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addSubSite()">
                        Añadir sub-sitio
                    </button>

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
    let subSiteIndex = 0;

    function addSubSite() {
        const container = document.getElementById('subSitesContainer');

        const html = `
        <div class="border rounded p-3 mb-2">
            <div class="mb-2">
                <label class="form-label">Nombre del sub-sitio</label>
                <input type="text" name="sub_sites[${subSiteIndex}][name]" class="form-control" required>
            </div>

            <div>
                <label class="form-label">Estudios acústicos</label>
                <input type="file" name="sub_sites[${subSiteIndex}][files][]" class="form-control" multiple>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', html);
        subSiteIndex++;
    }
    document.getElementById('createInstallationForm').addEventListener('submit', function (e) {

        const checkedSubSites = document.querySelectorAll('input[name="sub_sites[]"]:checked');

        if (checkedSubSites.length === 0) {
            e.preventDefault();
            alert('Debes seleccionar al menos un sub-sitio antes de guardar la instalación.');
            return false;
        }

    });
</script>