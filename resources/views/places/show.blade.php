@extends('layouts.metronic.app')

@section('title', 'Detalle de lugar')

@section('content')

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-bold mb-1">
                {{ $place->name }}
            </h3>
            <div class="text-muted">
                Máx. limitadores permitidos: <span class="fw-bold text-dark">{{ $place->max_limiters }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('places.index') }}" class="btn btn-light-primary">
                Volver al listado
            </a>
        </div>
    </div>

    <hr class="mb-5">

    {{-- SUB-SITIOS Y ESTUDIOS ACÚSTICOS --}}
    <h5 class="fw-bold mb-4">Sub-sitios y estudios acústicos</h5>

    @forelse($place->subSites as $subSite)
        <div class="card mb-4">
            <div class="card-header border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title fw-bold mb-0">
                    {{ $subSite->name }}
                </h6>
                <button type="button" class="btn btn-sm btn-light-primary btn-add-study" data-toggle="modal"
                    data-target="#modalAddStudy" data-subsite-id="{{ $subSite->id }}" data-subsite-name="{{ $subSite->name }}">
                    <i class="la la-plus"></i> Añadir estudio
                </button>
            </div>
            <div class="card-body">

                @forelse($subSite->acousticStudies as $study)
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px me-3">
                            <span class="symbol-label bg-light-primary">
                                <i class="la la-file-pdf text-primary fs-2"></i>
                            </span>
                        </div>

                        <div class="d-flex flex-column">
                            <a href="{{ route('acoustic-studies.download', $study) }}"
                                class="text-dark fw-bold text-hover-primary mb-1">
                                {{ $study->original_name }}
                            </a>
                            <span class="text-muted fs-7">Estudio acústico</span>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light-warning d-flex align-items-center p-5 mb-0">
                        <i class="la la-warning fs-2hx text-warning me-4"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-warning">Aviso</span>
                            <p class="text-muted mb-0">No hay estudios acústicos asociados a este sub-sitio.</p>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    @empty
        <div class="alert alert-secondary d-flex align-items-center p-5">
            <i class="la la-info-circle fs-2hx text-secondary me-4"></i>
            <div class="d-flex flex-column">
                <span class="fw-bold text-dark">Información</span>
                <span>Este lugar no tiene sub-sitios registrados.</span>
            </div>
        </div>
    @endforelse

@endsection

{{-- MODAL AÑADIR ESTUDIO --}}
<div class="modal fade" id="modalAddStudy" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formAddStudy" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Añadir estudios a <span id="modalSubSiteName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light-info d-flex align-items-center p-3 mb-4">
                        <i class="la la-info-circle fs-2 text-info me-2"></i>
                        <span class="fs-7">Asegúrate de que los archivos no tengan nombres repetidos para el mismo sub-sitio. Al subir archivos con el mismo nombre se producirá un error.</span>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label">Estudios acústicos (PDF)<span class="text-danger">*</span></label>
                        <input type="file" name="files[]" class="form-control" multiple accept=".pdf,application/pdf" required>
                        <div class="text-muted fs-7 mt-2">Puedes seleccionar varios archivos PDF al mismo tiempo.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir archivos</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btn-add-study').on('click', function() {
                const subSiteId = $(this).data('subsite-id');
                const subSiteName = $(this).data('subsite-name');
                const form = $('#formAddStudy');
                const url = "{{ route('acoustic-studies.store', ':id') }}".replace(':id', subSiteId);

                form.attr('action', url);
                $('#modalSubSiteName').text(subSiteName);
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#modalAddStudy').modal('show');
                });
            @endif
        });
    </script>
@endpush