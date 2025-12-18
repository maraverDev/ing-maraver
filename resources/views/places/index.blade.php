@extends('layouts.metronic.app')

@section('title', 'Lugares')

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
        <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
            <div class="card-title d-flex flex-column">
                <h3 class="fw-bold m-0 text-dark">
                    Control de Lugares
                </h3>
                <span class="text-muted fs-7">
                    Registro y seguimiento de lugares a los que se realizan estudios acústicos
                </span>
            </div>

            <div class="card-toolbar">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreatePlace">
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
                        <th>Nombre</th>
                        <th>Sub-sitios</th>
                        <th>Limitadores</th>
                        <th>Estudios</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($places as $place)
                        <tr>
                            <td>{{ $place->name }}</td>
                            <td>{{ $place->sub_sites_count }}</td>
                            <td>{{ $place->max_limiters }}</td>
                            <td>
                                <a href="{{ route('places.show', $place) }}" class="btn btn-sm btn-light">
                                    Ver estudios
                                </a>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No hay lugares registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!--end::Card body-->
    </div>

    @include('places.modals.create')
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
            $(document).ready(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Hay errores en el formulario',
                    text: 'Revisa los campos marcados en rojo',
                    confirmButtonText: 'Revisar'
                }).then(() => {
                    $('#modalCreatePlace').modal('show');
                });
            });
        </script>
    @endif
    @if ($errors->has('sub_sites') || collect($errors->keys())->contains(fn($k) => str_starts_with($k, 'sub_sites.')))
        <div class="alert alert-danger">
            Hay errores en los sub-sitios. Revisa los nombres y archivos.
        </div>
    @endif
    <script>
        let subSiteIndex = 0;

        document.getElementById('addSubSite').addEventListener('click', function () {

            const container = document.getElementById('subSitesContainer');

            const html = `
                                                                                                                                <div class="card mb-3 sub-site">
                                                                                                                                    <div class="card-body">

                                                                                                                                        <div class="d-flex justify-content-between mb-2">
                                                                                                                                            <strong>Sub-sitio</strong>
                                                                                                                                            <button type="button" class="btn btn-sm btn-danger remove-subsite">Eliminar</button>
                                                                                                                                        </div>

                                                                                                                                        <div class="form-group">
                                                                                                                                            <label>Nombre del sub-sitio<span class="text-danger">*</span></label>
                                                                                                                                            <input type="text" name="sub_sites[${subSiteIndex}][name]" class="form-control" required>

                                                                                                                                        </div>

                                                                                                                                        <div class="form-group">
                                                                                                                                            <label>Estudios acústicos (PDF)</label>
                                                                                                                                            <input type="file"
                                                                                                                                                    name="sub_sites[${subSiteIndex}][studies][]"
                                                                                                                                                    class="form-control"
                                                                                                                                                    multiple
                                                                                                                                                    accept=".pdf,application/pdf">
                                                                                                                                        </div>

                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            `;

            container.insertAdjacentHTML('beforeend', html);
            subSiteIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-subsite')) {
                e.target.closest('.sub-site').remove();
            }
        });
    </script>

@endpush