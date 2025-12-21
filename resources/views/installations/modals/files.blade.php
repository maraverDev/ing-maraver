@foreach($installation->subSites as $subSite)
    @php
        $byType = $installation->files
            ->where('sub_site_id', $subSite->id)
            ->keyBy('type');

        $currentCsv = $byType->get('csv');
        $currentProg = $byType->get('pdf_programming');
        $currentInst = $byType->get('pdf_installation');
    @endphp

    <div class="modal fade" id="modalFilesSubSite{{ $subSite->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">

                {{-- HEADER --}}
                <div class="modal-header bg-faded py-3 border-bottom-0">
                    <h5 class="modal-title font-weight-bold text-dark">
                        <i class="la la-folder-open m--font-brand mr-2"></i> Gestión de Archivos · <span
                            class="m--font-brand">{{ $subSite->name }}</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4 p-md-5">

                    {{-- ================= DATOS (CSV) ================= --}}
                    <div class="m-portlet m-portlet--bordered m-portlet--unair mb-4 shadow-none border-light">
                        <div class="m-portlet__head bg-light py-2 h-auto" style="min-height: 40px;">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h6 class="m-portlet__head-text font-weight-bold text-dark small text-uppercase mb-0">
                                        <i class="la la-file-code-o mr-2 m--font-brand"></i> Descarga de datos (CSV)
                                    </h6>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                @if($currentCsv)
                                    <span
                                        class="m-badge m-badge--success m-badge--wide m-badge--rounded font-weight-bold">Presente</span>
                                @else
                                    <span class="m-badge m-badge--danger m-badge--wide m-badge--rounded font-weight-bold">Falta
                                        archivo</span>
                                @endif
                            </div>
                        </div>
                        <div class="m-portlet__body py-3">
                            @if($currentCsv)
                                <div
                                    class="d-flex justify-content-between align-items-center mb-4 p-3 bg-faded rounded border border-light">
                                    <div class="d-flex align-items-center">
                                        <i class="la la-file-text-o fs-2 text-dark mr-3"></i>
                                        <div>
                                            <div class="fw-semibold text-dark font-weight-bold">{{ $currentCsv->original_name }}
                                            </div>
                                            <div class="text-muted small">
                                                <i class="la la-hdd-o mr-1"></i>
                                                {{ number_format(($currentCsv->file_size ?? 0) / 1024, 1) }} KB
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('installation-files.destroy', $currentCsv) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="Eliminar">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('installations.files.store', [$installation, $subSite]) }}"
                                enctype="multipart/form-data" class="m-form">
                                @csrf
                                <input type="hidden" name="type" value="csv">

                                <div class="form-group m-form__group mb-0">
                                    <div class="input-group">
                                        <div class="custom-file border-faded">
                                            <input type="file" name="file" accept=".csv" class="custom-file-input"
                                                id="csvFile{{ $subSite->id }}" required>
                                            <label class="custom-file-label" for="csvFile{{ $subSite->id }}">Actualizar
                                                archivo CSV...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary m-btn m-btn--icon">
                                                <span><i class="la la-cloud-upload"></i> <span>Subir</span></span>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="m-form__help text-muted small mt-1 d-block">Sube un nuevo archivo .CSV para
                                        reemplazar el actual.</span>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ================= PROG (PDF) ================= --}}
                    <div class="m-portlet m-portlet--bordered m-portlet--unair mb-4 shadow-none border-light">
                        <div class="m-portlet__head bg-light py-2 h-auto" style="min-height: 40px;">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h6 class="m-portlet__head-text font-weight-bold text-dark small text-uppercase mb-0">
                                        <i class="la la-file-pdf-o mr-2 m--font-brand"></i> Informe de programación (PDF)
                                    </h6>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                @if($currentProg)
                                    <span
                                        class="m-badge m-badge--success m-badge--wide m-badge--rounded font-weight-bold">Presente</span>
                                @else
                                    <span class="m-badge m-badge--danger m-badge--wide m-badge--rounded font-weight-bold">Falta
                                        archivo</span>
                                @endif
                            </div>
                        </div>
                        <div class="m-portlet__body py-3">
                            @if($currentProg)
                                <div
                                    class="d-flex justify-content-between align-items-center mb-4 p-3 bg-faded rounded border border-light">
                                    <div class="d-flex align-items-center">
                                        <i class="la la-file-pdf-o fs-2 text-danger mr-3"></i>
                                        <div>
                                            <div class="fw-semibold text-dark font-weight-bold">
                                                {{ $currentProg->original_name }}</div>
                                            <div class="text-muted small">
                                                <i class="la la-hdd-o mr-1"></i>
                                                {{ number_format(($currentProg->file_size ?? 0) / 1024, 1) }} KB
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('installation-files.destroy', $currentProg) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="Eliminar">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('installations.files.store', [$installation, $subSite]) }}"
                                enctype="multipart/form-data" class="m-form">
                                @csrf
                                <input type="hidden" name="type" value="pdf_programming">

                                <div class="form-group m-form__group mb-0">
                                    <div class="input-group">
                                        <div class="custom-file border-faded">
                                            <input type="file" name="file" accept=".pdf" class="custom-file-input"
                                                id="progFile{{ $subSite->id }}" required>
                                            <label class="custom-file-label" for="progFile{{ $subSite->id }}">Actualizar
                                                archivo PROG...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary m-btn m-btn--icon">
                                                <span><i class="la la-cloud-upload"></i> <span>Subir</span></span>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="m-form__help text-muted small mt-1 d-block">Sube un nuevo informe PDF de
                                        programación.</span>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ================= INST (PDF) ================= --}}
                    <div class="m-portlet m-portlet--bordered m-portlet--unair mb-0 shadow-none border-light">
                        <div class="m-portlet__head bg-light py-2 h-auto" style="min-height: 40px;">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h6 class="m-portlet__head-text font-weight-bold text-dark small text-uppercase mb-0">
                                        <i class="la la-file-image-o mr-2 m--font-brand"></i> Informe de instalación (PDF)
                                    </h6>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                @if($currentInst)
                                    <span
                                        class="m-badge m-badge--success m-badge--wide m-badge--rounded font-weight-bold">Presente</span>
                                @else
                                    <span class="m-badge m-badge--danger m-badge--wide m-badge--rounded font-weight-bold">Falta
                                        archivo</span>
                                @endif
                            </div>
                        </div>
                        <div class="m-portlet__body py-3">
                            @if($currentInst)
                                <div
                                    class="d-flex justify-content-between align-items-center mb-4 p-3 bg-faded rounded border border-light">
                                    <div class="d-flex align-items-center">
                                        <i class="la la-file-pdf-o fs-2 text-danger mr-3"></i>
                                        <div>
                                            <div class="fw-semibold text-dark font-weight-bold">
                                                {{ $currentInst->original_name }}</div>
                                            <div class="text-muted small">
                                                <i class="la la-hdd-o mr-1"></i>
                                                {{ number_format(($currentInst->file_size ?? 0) / 1024, 1) }} KB
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('installation-files.destroy', $currentInst) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="Eliminar">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('installations.files.store', [$installation, $subSite]) }}"
                                enctype="multipart/form-data" class="m-form">
                                @csrf
                                <input type="hidden" name="type" value="pdf_installation">

                                <div class="form-group m-form__group mb-0">
                                    <div class="input-group">
                                        <div class="custom-file border-faded">
                                            <input type="file" name="file" accept=".pdf" class="custom-file-input"
                                                id="instFile{{ $subSite->id }}" required>
                                            <label class="custom-file-label" for="instFile{{ $subSite->id }}">Actualizar
                                                archivo INST...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary m-btn m-btn--icon">
                                                <span><i class="la la-cloud-upload"></i> <span>Subir</span></span>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="m-form__help text-muted small mt-1 d-block">Sube un nuevo informe PDF de
                                        instalación.</span>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 py-3">
                    <button type="button" class="btn btn-secondary m-btn m-btn--pill" data-dismiss="modal">
                        Cerrar ventana
                    </button>
                </div>

            </div>
        </div>
    </div>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Manejo de inputs de archivo personalizados (Bootstrap custom-file)
    $(document).on('change', '.custom-file-input', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName || 'Seleccionar archivo...');
    });

    // 2. Interceptar todos los formularios de eliminación (Swal)
    $(document).on('submit', 'form', function(e) {
        // Solo actuar si el formulario tiene un _method input con valor DELETE
        const methodInput = this.querySelector('input[name="_method"][value="DELETE"]');
        if (!methodInput) return;

        e.preventDefault();
        const form = this;
        
        // Obtener el nombre del archivo del elemento anterior
        const fileNameElement = form.closest('.d-flex').querySelector('.fw-semibold');
        const fileName = fileNameElement ? fileNameElement.textContent : 'este archivo';
        
        Swal.fire({
            title: '¿Eliminar archivo?',
            text: `Se eliminará "${fileName}"`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger m-btn m-btn--pill',
                cancelButton: 'btn btn-secondary m-btn m-btn--pill'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>