@extends('layouts.metronic.app')

@section('title', 'Detalle de lugar')

@section('content')

    {{-- HEADER / TOOLBAR --}}
    <div class="m-portlet m-portlet--last m-portlet--head-lg m-portlet--responsive-mobile mb-5 shadow-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-wrapper">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="flaticon-placeholder-1 m--font-brand"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            Lugar · <span class="m--font-boldest">{{ $place->name }}</span>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="Acciones">
                        <a href="{{ route('places.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>Volver</span>
                            </span>
                        </a>
                        @can('update', $place)
                            <a href="{{ route('places.edit', $place) }}"
                                class="btn btn-primary m-btn m-btn--icon m-btn--pill m-btn--air">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>Editar lugar</span>
                                </span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__body py-4">
            {{-- INFO GENERAL --}}
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--brand m-badge--wide m-badge--rounded p-3">
                                <i class="la la-building font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Nombre del lugar</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $place->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--danger m-badge--wide m-badge--rounded p-3">
                                <i class="la la-tachometer font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Límite de
                                limitadores</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $place->max_limiters }} dispositivos</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--success m-badge--wide m-badge--rounded p-3">
                                <i class="la la-map-marker font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Sub-sitios
                                registrados</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $place->subSites->count() }} áreas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SUB-SITIOS Y ESTUDIOS ACÚSTICOS --}}
    <div class="row m--margin-top-20">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile mb-5 shadow-sm">
                <div class="m-portlet__head border-bottom-0">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-text-o m--font-brand"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Sub-sitios y estudios acústicos
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body pt-0">
                    @forelse($place->subSites as $subSite)
                        <div class="m-portlet m-portlet--bordered m-portlet--unair mb-4 shadow-none border-light">
                            <div class="m-portlet__head bg-faded py-0 h-auto" style="min-height: 50px;">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text font-weight-bold text-dark">
                                            <i class="la la-map-marker m--font-brand mr-2"></i> {{ $subSite->name }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">
                                    <button type="button"
                                        class="btn btn-outline-brand m-btn m-btn--icon m-btn--pill btn-sm btn-add-study"
                                        data-toggle="modal" data-target="#modalAddStudy" data-subsite-id="{{ $subSite->id }}"
                                        data-subsite-name="{{ $subSite->name }}">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>Añadir estudio</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="m-portlet__body py-3">
                                @forelse($subSite->acousticStudies as $study)
                                    <div
                                        class="d-flex align-items-center mb-3 p-3 bg-light rounded border border-light shadow-sm-hover transition-all">
                                        <div class="m--margin-right-15">
                                            <span class="m-badge m-badge--info m-badge--wide m-badge--rounded p-3 bg-white border">
                                                <i class="la la-file-pdf-o font-lg text-danger"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <a href="{{ route('acoustic-studies.download', $study) }}"
                                                class="text-dark m--font-boldest text-hover-primary mb-1">
                                                {{ $study->original_name }}
                                            </a>
                                            <span class="text-muted small">
                                                <i class="la la-clock-o mr-1"></i> Subido el
                                                {{ $study->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        <div class="m--margin-left-10">
                                            <a href="{{ route('acoustic-studies.download', $study) }}"
                                                class="btn btn-outline-metal btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                                title="Descargar">
                                                <i class="la la-download text-dark"></i>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="m-alert m-alert--outline alert alert-secondary fade show mb-0 py-2 px-3 border-dashed"
                                        role="alert">
                                        <i class="la la-info-circle mr-2 text-muted"></i> No hay estudios acústicos registrados para
                                        este sub-sitio.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="m-alert m-alert--outline alert alert-secondary fade show p-5 border-dashed text-center"
                            role="alert">
                            <i class="la la-info-circle fs-1x text-muted mb-3 d-block"></i>
                            <span class="fw-bold text-dark d-block mb-1">Sin sub-sitios</span>
                            <span class="text-muted small">Este lugar no tiene sub-sitios registrados actualmente.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL AÑADIR ESTUDIO --}}
    <div class="modal fade" id="modalAddStudy" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <form id="formAddStudy" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-faded py-3 border-bottom-0">
                        <h5 class="modal-title font-weight-bold text-dark">
                            <i class="la la-cloud-upload m--font-brand mr-2"></i> Añadir estudios a <span
                                id="modalSubSiteName" class="m--font-brand"></span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="m-alert m-alert--icon m-alert--outline alert alert-info mb-4 py-2 px-3 small border-dashed"
                            role="alert">
                            <div class="m-alert__icon mr-3">
                                <i class="la la-info-circle"></i>
                            </div>
                            <div class="m-alert__text">
                                Evita subir archivos con nombres idénticos para el mismo sub-sitio para prevenir conflictos.
                            </div>
                        </div>

                        <div class="form-group m-form__group mb-0">
                            <label class="form-control-label font-weight-bold text-dark">Estudios acústicos (PDF) <span
                                    class="text-danger">*</span></label>
                            <div class="custom-file border-faded">
                                <input type="file" name="files[]" class="custom-file-input" id="customFile" multiple
                                    accept=".pdf,application/pdf" required>
                                <label class="custom-file-label" for="customFile">Seleccionar archivos...</label>
                            </div>
                            <span class="m-form__help text-muted small mt-2 d-block">Puedes seleccionar varios archivos PDF
                                simultáneamente.</span>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 py-3">
                        <button type="button" class="btn btn-secondary m-btn m-btn--pill"
                            data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary m-btn m-btn--pill m-btn--air">
                            <span>
                                <i class="la la-cloud-upload"></i>
                                <span>Subir estudios</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Manejar selección de archivos en el input custom de Metronic/BS4
            $('.custom-file-input').on('change', function () {
                let fileName = $(this).val().split('\\').pop();
                if ($(this)[0].files.length > 1) {
                    fileName = $(this)[0].files.length + " archivos seleccionados";
                }
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            $('.btn-add-study').on('click', function () {
                const subSiteId = $(this).data('subsite-id');
                const subSiteName = $(this).data('subsite-name');
                const form = $('#formAddStudy');
                const url = "{{ route('acoustic-studies.store', ':id') }}".replace(':id', subSiteId);

                form.attr('action', url);
                $('#modalSubSiteName').text(subSiteName);

                // Reset file input label
                form.find('.custom-file-label').removeClass("selected").html("Seleccionar archivos...");
                form[0].reset();
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    confirmButtonClass: "btn btn-success m-btn m-btn--pill m-btn--air"
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonClass: "btn btn-danger m-btn m-btn--pill m-btn--air"
                }).then(() => {
                    // Reabrir modal si hay errores específicos de validación (opcional si el controlador redirige atrás con errores)
                    // $('#modalAddStudy').modal('show');
                });
            @endif
            });
    </script>
@endpush