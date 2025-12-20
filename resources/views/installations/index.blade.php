@extends('layouts.metronic.app')

@section('title', 'Instalaciones')

@section('content')

    <div class="card">
        <!--begin::Card header-->

        <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
            <div class="card-title d-flex flex-column">
                <h3 class="fw-bold m-0 text-dark">
                    Control de instalaciones
                </h3>
                <span class="text-muted fs-7">
                    Registro y seguimiento de instalaciones realizadas
                </span>
            </div>
            {{-- BOTÓN FILTROS --}}
            <div class="dropdown">
                @php
                    $filtersActive = collect(['search', 'place_id', 'from', 'to'])
                        ->filter(fn($f) => request()->filled($f))
                        ->count();
                @endphp
                <button class="btn btn-light-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="fas fa-filter mr-1"></i>
                    Filtros
                    @if ($filtersActive)
                        <span class="badge badge-primary ml-1">{{ $filtersActive }}</span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-right p-4" style="min-width: 320px;">
                    @include('installations.partials.filters')
                </div>
            </div>

            {{-- NUEVA INSTALACIÓN --}}
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateInstallation">
                Nueva instalación
            </button>

        </div>


        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">

            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'installation_date', 'direction' => request('sort') == 'installation_date' && request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                class="text-muted text-hover-primary">
                                Fecha
                                @if (request('sort', 'installation_date') == 'installation_date')
                                    <i
                                        class="fas fa-sort-{{ request('direction', 'desc') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="min-w-150px">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'place', 'direction' => request('sort') == 'place' && request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                class="text-muted text-hover-primary">
                                Lugar
                                @if (request('sort') == 'place')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th>Sub-sitios</th>
                        <th class="min-w-125px">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'limiters_installed', 'direction' => request('sort') == 'limiters_installed' && request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                class="text-muted text-hover-primary">
                                Limitadores
                                @if (request('sort') == 'limiters_installed')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th>Estado</th>
                        <th>Archivos</th>
                        <th>Técnico</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($installations as $installation)
                        <tr class="installation-row" data-href="{{ route('installations.show', $installation) }}"
                            style="cursor:pointer">
                            <td>{{ $installation->installation_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $installation->place->name }}</td>
                            <td>{{ $installation->subSites->count() }}</td>
                            <td>{{ $installation->limiters_installed }}</td>
                            <td>
                                @if ($installation->status === \App\Models\Installation::STATUS_COMPLETED)
                                    <span class="badge badge-light-success text-success fw-bold">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Realizada
                                    </span>
                                @else
                                    <span class="badge badge-light-warning text-warning fw-bold">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pendiente
                                    </span>
                                @endif
                            </td>


                            {{-- ARCHIVOS --}}
<td>
    @switch($installation->installation_status)

        @case('complete')
            <span class="badge badge-light-success text-success fw-bold">
                <i class="fas fa-folder-open mr-1"></i>
                {{ $installation->uploaded_files_count }}/{{ $installation->expected_files_count }}
            </span>
        @break

        @case('partial')
            <span class="badge badge-light-warning text-warning fw-bold">
                <i class="fas fa-folder-minus mr-1"></i>
                {{ $installation->uploaded_files_count }}/{{ $installation->expected_files_count }}
            </span>
        @break

        @case('empty')
            <span class="badge badge-light-danger text-danger fw-bold">
                <i class="fas fa-folder mr-1"></i>
                0/{{ $installation->expected_files_count }}
            </span>
        @break

    @endswitch
</td>





                            <td>{{ $installation->creationLog->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay instalaciones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando {{ $installations->firstItem() }}–{{ $installations->lastItem() }}
                        de {{ $installations->total() }} instalaciones
                    </div>

                    {{ $installations->links('pagination::bootstrap-4') }}
                </div>

            </div>
            <!--end::Card body-->
        </div>

        @include('installations.modals.create')

    @endsection
    @push('scripts')

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Aceptar',
                    timer: 3000
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hay errores en el formulario',
                        text: 'Revisa los campos marcados en rojo',
                        confirmButtonText: 'Revisar'
                    }).then(() => {
                        $('#modalCreateInstallation').modal('show');
                    });
                });
            </script>
        @endif

        <script>
            /* ================================
                                                                                           VARIABLES DE ESTADO (PERSISTENCIA)
                                                                                        ================================ */
            const oldData = {
                placeId: "{{ old('place_id') }}",
                limitersInstalled: "{{ old('limiters_installed') }}",
                subSites: @json(old('sub_sites', [])),
                errors: @json($errors->toArray())
            };

            /* ================================
               LÓGICA DE CARGA DE LUGAR
            ================================ */
            function loadPlaceData(placeId, callback = null) {
                if (!placeId) {
                    $('#subSitesContainer').empty();
                    $('#subSiteFilesContainer').empty();
                    $('#limitersSelect').prop('disabled', true).html('<option value="">Selecciona un lugar primero</option>');
                    return;
                }

                const limitersSelect = $('#limitersSelect');
                limitersSelect.prop('disabled', true).html('<option value="">Cargando...</option>');

                $.get(`/places/${placeId}/sub-sites`, function(response) {
                    // LIMITADORES
                    limitersSelect.html('<option value="">Selecciona</option>');
                    for (let i = 1; i <= response.max_limiters; i++) {
                        const selected = (oldData.limitersInstalled == i) ? 'selected' : '';
                        limitersSelect.append(`<option value="${i}" ${selected}>${i}</option>`);
                    }
                    limitersSelect.prop('disabled', false);

                    // SUB-SITIOS
                    $('#subSitesContainer').empty();
                    response.sub_sites.forEach(subSite => {
                        const submittedIndex = oldData.subSites.indexOf(subSite.id.toString());
                        const isChecked = submittedIndex !== -1;
                        const hasError = (submittedIndex !== -1 && oldData.errors[
                            `sub_sites.${submittedIndex}`]) || oldData.errors['sub_sites'];

                        const html = `
                        <div class="sub-site-item mb-3">
                            <label class="sub-site-card ${hasError ? 'border-danger' : ''}">
                                <input type="checkbox"
                                    class="sub-site-checkbox"
                                    name="sub_sites[]"
                                    value="${subSite.id}"
                                    data-name="${subSite.name}"
                                    ${isChecked ? 'checked' : ''}>
                                <div class="sub-site-content">
                                    <strong class="${hasError ? 'text-danger' : ''}">${subSite.name}</strong>
                                </div>
                            </label>
                        </div>
                    `;
                        $('#subSitesContainer').append(html);

                        if (isChecked) {
                            renderFilesSection(subSite.id, subSite.name);
                        }
                    });

                    if (callback) callback();
                });
            }

            /* ================================
               RENDERIZAR SECCIÓN DE ARCHIVOS
            ================================ */
            function renderFilesSection(subSiteId, subSiteName) {
                if ($(`#files-${subSiteId}`).length) return;

                const filesHtml = `
                <div class="card mb-4 sub-site-files" id="files-${subSiteId}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">${subSiteName}</h6>
                            <span class="badge badge-light-info">Esperando archivos</span>
                        </div>
                        <div class="form-group">
                            <label>Archivos del sub-sitio <span class="text-danger">*</span></label>
                            <div class="custom-file-upload">
                                <button type="button" class="btn btn-light-primary btn-sm select-files-btn" data-subsite="${subSiteId}">
                                    <i class="fas fa-paperclip mr-1"></i> Subir archivos
                                </button>
                                <span class="selected-files text-muted ml-2">Ningún archivo seleccionado</span>
                                <input type="file" name="files[${subSiteId}][]" class="d-none sub-site-file-input" data-subsite="${subSiteId}" multiple accept=".csv,.pdf">
                            </div>
                        </div>
                        <div class="file-status mt-3">
                            <span class="status-item datos">DATOS ❌</span>
                            <span class="status-item prog">PROG ❌</span>
                            <span class="status-item inst">INST ❌</span>
                        </div>
                    </div>
                </div>
            `;
                $('#subSiteFilesContainer').append(filesHtml);
            }

            // Eventos
            $('select[name="place_id"]').on('change', function() {
                loadPlaceData($(this).val());
            });

            $(document).on('change', '.sub-site-checkbox', function() {
                const subSiteId = $(this).val();
                const subSiteName = $(this).data('name');
                if (this.checked) {
                    renderFilesSection(subSiteId, subSiteName);
                } else {
                    $(`#files-${subSiteId}`).remove();
                }
            });

            /* ================================
               INICIO (RESTAURACIÓN)
            ================================ */
            $(document).ready(function() {
                if (oldData.placeId) {
                    loadPlaceData(oldData.placeId);
                }
            });

            /* ... el resto de scripts existentes (submit, clicks, etc.) ... */
            $(document).on('click', '.select-files-btn', function() {
                const subSiteId = $(this).data('subsite');
                $(`input.sub-site-file-input[data-subsite="${subSiteId}"]`).click();
            });

            $(document).on('change', '.sub-site-file-input', function() {
                const subSiteId = $(this).data('subsite');
                const files = this.files;
                const container = $(`#files-${subSiteId}`);
                container.find('.selected-files').text(files.length ?
                    `${files.length} archivo${files.length > 1 ? 's' : ''} seleccionado${files.length > 1 ? 's' : ''}` :
                    'Ningún archivo seleccionado');
                let found = {
                    datos: false,
                    prog: false,
                    inst: false
                };
                Array.from(files).forEach(file => {
                    const name = file.name.toUpperCase();
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (ext === 'csv') found.datos = true;
                    if (ext === 'pdf' && (name.includes('PROG') || name.includes('SET'))) found.prog = true;
                    if (ext === 'pdf' && (name.includes('INST') || name.includes('INS'))) found.inst = true;
                });
                container.find('.status-item.datos').toggleClass('ok', found.datos).text(found.datos ? 'DATOS ✔' :
                    'DATOS ❌');
                container.find('.status-item.prog').toggleClass('ok', found.prog).text(found.prog ? 'PROG ✔' :
                    'PROG ❌');
                container.find('.status-item.inst').toggleClass('ok', found.inst).text(found.inst ? 'INST ✔' :
                    'INST ❌');
                const badge = container.find('.badge');
                if (found.datos && found.prog && found.inst) {
                    badge.removeClass('badge-light-info').addClass('badge-light-success').text('Archivos completos');
                } else {
                    badge.removeClass('badge-light-success').addClass('badge-light-info').text('Faltan archivos');
                }
            });

            $('form#formCreateInstallation').on('submit', function(e) {
                // Si ya fue confirmado por Swal, dejamos que prosiga
                if ($(this).data('confirmed')) return true;

                let incompleteSubSites = [];
                $('.sub-site-files').each(function() {
                    const badgeText = $(this).find('.badge').text();
                    if (badgeText !== 'Archivos completos') {
                        incompleteSubSites.push($(this).find('h6').text().trim());
                    }
                });

                if (incompleteSubSites.length > 0) {
                    e.preventDefault();
                    const form = this;

                    Swal.fire({
                        icon: 'warning',
                        title: 'Instalación incompleta',
                        html: `<p>Los siguientes sub-sitios no tienen todos los archivos:</p>
                               <ul style="text-align:left">${incompleteSubSites.map(s => `<li>${s}</li>`).join('')}</ul>
                               <p>¿Deseas guardar la instalación igualmente?</p>`,
                        showCancelButton: true,
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#3699ff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(form).data('confirmed', true).submit();
                        }
                    });
                }
            });

            $(document).on('click', '.installation-row', function(e) {
                if ($(e.target).closest('a, button, input, label').length) return;
                window.location = $(this).data('href');
            });

            /* ================================
               INICIALIZACIÓN DE FILTROS (SELECT2)
            ================================ */
            $(document).ready(function() {
                // Inicializar Select2 en el filtro de lugar
                $('#select2_filters_place').select2({
                    placeholder: "Selecciona un lugar",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#modalCreateInstallation').length ? $(
                        '.dropdown-menu:has(#select2_filters_place)') : null
                });

                // Prevenir que el dropdown de Bootstrap se cierre al interactuar con Select2
                $(document).on('click', '.select2-container', function(e) {
                    e.stopPropagation();
                });
            });
        </script>

        </script>

    @endpush
