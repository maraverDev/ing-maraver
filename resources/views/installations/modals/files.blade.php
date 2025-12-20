@foreach($installation->subSites as $subSite)
    @php
        $byType = $installation->files
            ->where('sub_site_id', $subSite->id)
            ->keyBy('type');

        $currentCsv = $byType->get('csv');
        $currentProg = $byType->get('pdf_programming');
        $currentInst = $byType->get('pdf_installation');
    @endphp

    <div class="modal fade" id="modalFilesSubSite{{ $subSite->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title">
                        Archivos · {{ $subSite->name }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    {{-- ================= DATOS (CSV) ================= --}}
                    <div class="mb-4">
                        <label class="fw-bold">Descarga de datos (CSV)</label>

                        @if($currentCsv)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $currentCsv->original_name }}</div>
                                    <div class="text-muted small">
                                        {{ number_format(($currentCsv->file_size ?? 0) / 1024, 1) }} KB
                                    </div>
                                </div>

                                {{-- FORM ELIMINAR CSV --}}
                                <form method="POST" action="{{ route('installation-files.destroy', $currentCsv) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @else
                            <small class="text-muted d-block mb-2">
                                No existe archivo DATOS.
                            </small>
                        @endif

                        {{-- FORM GUARDAR CSV --}}
                        <form method="POST" action="{{ route('installations.files.store', [$installation, $subSite]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="csv">

                            <input type="file" name="file" accept=".csv" class="form-control mb-2">

                            <button type="submit" class="btn btn-sm btn-primary">
                                Guardar DATOS
                            </button>
                        </form>
                    </div>

                    <hr>

                    {{-- ================= PROG (PDF) ================= --}}
                    <div class="mb-4">
                        <label class="fw-bold">Informe de programación (PDF)</label>

                        @if($currentProg)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $currentProg->original_name }}</div>
                                    <div class="text-muted small">
                                        {{ number_format(($currentProg->file_size ?? 0) / 1024, 1) }} KB
                                    </div>
                                </div>

                                {{-- FORM ELIMINAR PROG --}}
                                <form method="POST" action="{{ route('installation-files.destroy', $currentProg) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @else
                            <small class="text-muted d-block mb-2">
                                No existe archivo PROG.
                            </small>
                        @endif

                        {{-- FORM GUARDAR PROG --}}
                        <form method="POST" action="{{ route('installations.files.store', [$installation, $subSite]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="pdf_programming">

                            <input type="file" name="file" accept=".pdf" class="form-control mb-2">

                            <button type="submit" class="btn btn-sm btn-primary">
                                Guardar PROG
                            </button>
                        </form>
                    </div>

                    <hr>

                    {{-- ================= INST (PDF) ================= --}}
                    <div class="mb-4">
                        <label class="fw-bold">Informe de instalación (PDF)</label>

                        @if($currentInst)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $currentInst->original_name }}</div>
                                    <div class="text-muted small">
                                        {{ number_format(($currentInst->file_size ?? 0) / 1024, 1) }} KB
                                    </div>
                                </div>

                                {{-- FORM ELIMINAR INST --}}
                                <form method="POST" action="{{ route('installation-files.destroy', $currentInst) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @else
                            <small class="text-muted d-block mb-2">
                                No existe archivo INST.
                            </small>
                        @endif

                        {{-- FORM GUARDAR INST --}}
                        <form method="POST" action="{{ route('installations.files.store', [$installation, $subSite]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="pdf_installation">

                            <input type="file" name="file" accept=".pdf" class="form-control mb-2">

                            <button type="submit" class="btn btn-sm btn-primary">
                                Guardar INST
                            </button>
                        </form>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
@endforeach

<script>
// Interceptar todos los formularios de eliminación
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los formularios que tienen método DELETE
    const deleteForms = document.querySelectorAll('form[method="POST"]');
    
    deleteForms.forEach(form => {
        // Verificar si el formulario tiene el input _method con valor DELETE
        const methodInput = form.querySelector('input[name="_method"][value="DELETE"]');
        
        if (methodInput) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
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
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
});
</script>