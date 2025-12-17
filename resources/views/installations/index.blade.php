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
                <button class="btn btn-light-primary dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter mr-1"></i>
                    Filtros
                    @if($filtersActive)
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
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Sub-sitios</th>
                        <th>Limitadores</th>
                        <th>Estado</th>
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
                            {{-- ESTADO --}}
                            <td class="text-dark">
                                @switch($installation->installation_status)

                                    @case('complete')
                                        <span class="badge badge-light-success text-success fw-bold">
                                            Completa ({{ $installation->uploaded_files_count }})
                                        </span>
                                        @break

                                    @case('partial')
                                        <span class="badge badge-light-warning text-warning fw-bold">
                                            Incompleta ({{ $installation->uploaded_files_count }}/{{ $installation->expected_files_count }})
                                        </span>
                                        @break

                                    @case('empty')
                                        <span class="badge badge-light-danger text-danger fw-bold">
                                            Sin archivos
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
                title: 'Correcto',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            $(document).ready(function () {
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
           CAMBIO DE LUGAR
        ================================ */
        $('select[name="place_id"]').on('change', function () {

            const placeId = $(this).val();

            $('#subSitesContainer').empty();
            $('#subSiteFilesContainer').empty();

            const limitersSelect = $('#limitersSelect');
            limitersSelect.prop('disabled', true).html('');

            if (!placeId) {
                limitersSelect.append('<option value="">Selecciona un lugar primero</option>');
                return;
            }

            $.get(`/places/${placeId}/sub-sites`, function (response) {

                // LIMITADORES
                limitersSelect.append('<option value="">Selecciona</option>');
                for (let i = 1; i <= response.max_limiters; i++) {
                    limitersSelect.append(`<option value="${i}">${i}</option>`);
                }
                limitersSelect.prop('disabled', false);

                // SUB-SITIOS
                response.sub_sites.forEach(subSite => {

                    const html = `
                                                                                                                                                                                                                                                        <div class="sub-site-item mb-3">
                                                                                                                                                                                                                                                            <label class="sub-site-card">
                                                                                                                                                                                                                                                                <input type="checkbox"
                                                                                                                                                                                                                                                                       class="sub-site-checkbox"
                                                                                                                                                                                                                                                                       name="sub_sites[]"
                                                                                                                                                                                                                                                                       value="${subSite.id}"
                                                                                                                                                                                                                                                                       data-name="${subSite.name}">
                                                                                                                                                                                                                                                                <div class="sub-site-content">
                                                                                                                                                                                                                                                                    <strong>${subSite.name}</strong>
                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                            </label>
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                    `;

                    $('#subSitesContainer').append(html);
                });
            });
        });

        /* ================================
           SELECCIÓN DE SUB-SITIO
        ================================ */
        $(document).on('change', '.sub-site-checkbox', function () {

            const subSiteId = $(this).val();
            const subSiteName = $(this).data('name');

            if (this.checked) {

                const filesHtml = `
                                                                                                                                                                                                                                                    <div class="card mb-4 sub-site-files" id="files-${subSiteId}">
                                                                                                                                                                                                                                                        <div class="card-body">

                                                                                                                                                                                                                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                                                                                                                                                                                                                <h6 class="mb-0">${subSiteName}</h6>
                                                                                                                                                                                                                                                                <span class="badge badge-light-info">Esperando archivos</span>
                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                            <div class="form-group">
                                                                                                                                                                                                                                                                <label>
                                                                                                                                                                                                                                                                    Archivos del sub-sitio <span class="text-danger">*</span>
                                                                                                                                                                                                                                                                </label>

                                                                                                                                                                                                                                                                <div class="custom-file-upload">
                                                                                                                                                                                                                                                                    <button type="button"
                                                                                                                                                                                                                                                                            class="btn btn-light-primary btn-sm select-files-btn"
                                                                                                                                                                                                                                                                            data-subsite="${subSiteId}">
                                                                                                                                                                                                                                                                        <i class="fas fa-paperclip mr-1"></i>
                                                                                                                                                                                                                                                                        Subir archivos
                                                                                                                                                                                                                                                                    </button>

                                                                                                                                                                                                                                                                    <span class="selected-files text-muted ml-2">
                                                                                                                                                                                                                                                                        Ningún archivo seleccionado
                                                                                                                                                                                                                                                                    </span>

                                                                                                                                                                                                                                                                    <input type="file"
                                                                                                                                                                                                                                                                           name="files[${subSiteId}][]"
                                                                                                                                                                                                                                                                           class="d-none sub-site-file-input"
                                                                                                                                                                                                                                                                           data-subsite="${subSiteId}"
                                                                                                                                                                                                                                                                           multiple
                                                                                                                                                                                                                                                                           accept=".csv,.pdf"
                                                                                                                                                                                                                                                                           >
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

            } else {
                $(`#files-${subSiteId}`).remove();
            }
        });

        /* ================================
           BOTÓN BONITO → INPUT REAL
        ================================ */
        $(document).on('click', '.select-files-btn', function () {
            const subSiteId = $(this).data('subsite');
            $(`input.sub-site-file-input[data-subsite="${subSiteId}"]`).click();
        });

        /* ================================
           DETECCIÓN DE ARCHIVOS
        ================================ */
        $(document).on('change', '.sub-site-file-input', function () {

            const subSiteId = $(this).data('subsite');
            const files = this.files;
            const container = $(`#files-${subSiteId}`);

            // Mostrar nº de archivos
            container.find('.selected-files').text(
                files.length
                    ? `${files.length} archivo${files.length > 1 ? 's' : ''} seleccionado${files.length > 1 ? 's' : ''}`
                    : 'Ningún archivo seleccionado'
            );

            let found = { datos: false, prog: false, inst: false };

            Array.from(files).forEach(file => {
                const name = file.name.toUpperCase();
                const ext = file.name.split('.').pop().toLowerCase();

                if (ext === 'csv') found.datos = true;

                if (ext === 'pdf' && (name.includes('PROG') || name.includes('SET'))) {
                    found.prog = true;
                }

                if (ext === 'pdf' && (name.includes('INST') || name.includes('INS'))) {
                    found.inst = true;
                }
            });

            container.find('.status-item.datos')
                .toggleClass('ok', found.datos)
                .text(found.datos ? 'DATOS ✔' : 'DATOS ❌');

            container.find('.status-item.prog')
                .toggleClass('ok', found.prog)
                .text(found.prog ? 'PROG ✔' : 'PROG ❌');

            container.find('.status-item.inst')
                .toggleClass('ok', found.inst)
                .text(found.inst ? 'INST ✔' : 'INST ❌');

            const badge = container.find('.badge');

            if (found.datos && found.prog && found.inst) {
                badge.removeClass('badge-light-info')
                    .addClass('badge-light-success')
                    .text('Archivos completos');
            } else {
                badge.removeClass('badge-light-success')
                    .addClass('badge-light-info')
                    .text('Faltan archivos');
            }
        });
        $('form').on('submit', function (e) {

            let incompleteSubSites = [];

            $('.sub-site-files').each(function () {

                const badgeText = $(this).find('.badge').text();

                if (badgeText !== 'Archivos completos') {
                    incompleteSubSites.push(
                        $(this).find('h6').text().trim()
                    );
                }
            });

            // Si hay sub-sitios sin archivos completos
            if (incompleteSubSites.length > 0) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Instalación sin archivos completos',
                    html: `
                                                                                                                                                                                                                                                <p>Los siguientes sub-sitios no tienen todos los archivos:</p>
                                                                                                                                                                                                                                                <ul style="text-align:left">
                                                                                                                                                                                                                                                    ${incompleteSubSites.map(s => `<li>${s}</li>`).join('')}
                                                                                                                                                                                                                                                </ul>
                                                                                                                                                                                                                                                <p>¿Deseas guardar la instalación igualmente?</p>
                                                                                                                                                                                                                                            `,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3699ff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        e.target.submit();
                    }
                });
            }
        });
        $(document).on('click', '.installation-row', function (e) {

            // Evitar conflictos con selects, botones, links, etc.
            if ($(e.target).closest('a, button, input, label').length) {
                return;
            }

            window.location = $(this).data('href');
        });

    </script>

@endpush