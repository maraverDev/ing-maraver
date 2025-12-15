@extends('layouts.metronic.app')

@section('title', 'Instalaciones')

@section('content')

    <!-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif -->

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">Control de instalaciones</h3>
            </div>

            <div class="card-toolbar">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateInstallation">
                    Nueva instalación
                </button>
            </div>
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
                        <th>Técnico</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($installations as $installation)
                        <tr>
                            <td>{{ $installation->installation_date }}</td>
                            <td>{{ $installation->place->name }}</td>
                            <td>{{ $installation->subSites->count() }}</td>
                            <td>{{ $installation->limiters_installed }}</td>
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
        let selectedSubSites = [];

        $('select[name="place_id"]').on('change', function () {

            const placeId = $(this).val();

            // Reset
            $('#subSitesContainer').html('');
            $('#subSiteFilesContainer').html('');

            const limitersSelect = $('#limitersSelect');
            limitersSelect.html('').prop('disabled', true);

            if (!placeId) {
                limitersSelect.append('<option value="">Selecciona un lugar primero</option>');
                return;
            }

            $.get(`/places/${placeId}/sub-sites`, function (response) {

                // --- LIMITADORES ---
                limitersSelect.append('<option value="">Selecciona</option>');

                for (let i = 1; i <= response.max_limiters; i++) {
                    limitersSelect.append(
                        `<option value="${i}">${i}</option>`
                    );
                }

                limitersSelect.prop('disabled', false);

                // --- SUB-SITIOS ---
                response.sub_sites.forEach(subSite => {

                    const html = `
                                        <div class="form-check mb-2">
                                            <input class="form-check-input sub-site-checkbox"
                                                   type="checkbox"
                                                   name="sub_sites[]"
                                                   value="${subSite.id}"
                                                   data-name="${subSite.name}">
                                            <label class="form-check-label">
                                                ${subSite.name}
                                            </label>
                                        </div>
                                    `;

                    $('#subSitesContainer').append(html);
                });
            });
        });

        // Al marcar / desmarcar sub-sitios
        $(document).on('change', '.sub-site-checkbox', function () {

            const subSiteId = $(this).val();
            const subSiteName = $(this).data('name');

            if (this.checked) {
                selectedSubSites.push(subSiteId);
                const filesHtml = `
                                                    <div class="card mb-4" id="files-${subSiteId}">
                                                        <div class="card-body">
                                                            <h6 class="mb-3">${subSiteName}</h6>

                                                            <div class="form-group">
                                                                <label>
                                                                    Archivos del sub-sitio
                                                                    <span class="text-danger">*</span>
                                                                </label>

                                                                <input type="file"
                                                                       name="files[${subSiteId}][]"
                                                                       class="form-control"
                                                                       multiple
                                                                       accept=".csv,.pdf"
                                                                       required>

                                                                <small class="text-muted">
                                                                    Debes subir:
                                                                    1 CSV + 1 PDF de programación (SET) + 1 PDF de instalación (INS)
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;

                $('#subSiteFilesContainer').append(filesHtml);

            } else {
                selectedSubSites = selectedSubSites.filter(id => id != subSiteId);
                $(`#files-${subSiteId}`).remove();
            }
        });
    </script>

@endpush