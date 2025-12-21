@extends('layouts.metronic.app')

@section('title', 'Detalle de instalación')

{{-- MODALES --}}
@include('installations.modals.edit')
@include('installations.modals.files')

@section('content')

    {{-- HEADER / TOOLBAR --}}
    <div class="m-portlet m-portlet--last m-portlet--head-lg m-portlet--responsive-mobile mb-5 shadow-sm" id="main_portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-wrapper">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="flaticon-map-location m--font-brand"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            Instalación · <span class="m--font-boldest">{{ $installation->place->name }}</span>
                            <small class="m--margin-left-10">{{ $installation->installation_date->format('d/m/Y H:i') }}</small>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="Acciones">
                        <li class="m-portlet__nav-item d-flex align-items-center mr-3">
                            @switch($installation->installation_status)
                                @case('complete')
                                    <span class="m-badge m-badge--success m-badge--wide m-badge--rounded font-weight-bold">
                                        Completa
                                    </span>
                                @break

                                @case('partial')
                                    <span class="m-badge m-badge--warning m-badge--wide m-badge--rounded font-weight-bold">
                                        Incompleta ({{ $installation->uploaded_files_count }}/{{ $installation->expected_files_count }})
                                    </span>
                                @break

                                @case('empty')
                                    <span class="m-badge m-badge--danger m-badge--wide m-badge--rounded font-weight-bold">
                                        Sin archivos
                                    </span>
                                @break
                            @endswitch
                        </li>
                        <a href="{{ route('installations.index') }}"
                            class="btn btn-secondary m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>Volver</span>
                            </span>
                        </a>
                        @can('update', $installation)
                            <button class="btn btn-primary m-btn m-btn--icon m-btn--pill btn-edit-installation" data-toggle="modal"
                                data-target="#modalEditInstallation">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>Editar</span>
                                </span>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__body py-4">
            {{-- INFO GENERAL --}}
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--brand m-badge--wide m-badge--rounded p-3">
                                <i class="la la-building-o font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Lugar</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $installation->place->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--info m-badge--wide m-badge--rounded p-3">
                                <i class="la la-calendar font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Fecha de intervención</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $installation->installation_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--danger m-badge--wide m-badge--rounded p-3">
                                <i class="la la-tachometer font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Limitadores</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $installation->limiters_installed }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center p-3 border rounded bg-light shadow-none h-100">
                        <div class="m--margin-right-15">
                            <span class="m-badge m-badge--success m-badge--wide m-badge--rounded p-3">
                                <i class="la la-map-marker font-lg text-white"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small d-block font-weight-bold text-uppercase">Sub-sitios</span>
                            <span class="font-lg m--font-boldest text-dark">{{ $installation->subSites->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row m--margin-top-20">
        <div class="col-xl-8">
            {{-- ARCHIVOS POR SUB-SITIO --}}
            <div class="m-portlet m-portlet--mobile mb-5 shadow-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-folder-open m--font-brand"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Archivos técnicos por sub-sitio
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    @forelse($installation->subSites as $subSite)
                        @php
                            $files = $installation->files->where('sub_site_id', $subSite->id);
                            $order = ['csv' => 1, 'pdf_programming' => 2, 'pdf_installation' => 3];
                            $files = $files->sortBy(fn($f) => $order[$f->type] ?? 99);
                        @endphp

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
                                    @can('update', $installation)
                                        <button class="btn btn-outline-brand m-btn m-btn--icon m-btn--pill btn-sm" data-toggle="modal"
                                            data-target="#modalFilesSubSite{{ $subSite->id }}">
                                            <span>
                                                <i class="la la-cloud-upload"></i>
                                                <span>Gestionar</span>
                                            </span>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                            <div class="m-portlet__body py-3">
                                @if ($files->isEmpty())
                                    <div class="m-alert m-alert--outline alert alert-secondary fade show mb-0 py-2 px-3 border-dashed" role="alert">
                                        <i class="la la-info-circle mr-2"></i> No hay archivos cargados para este sub-sitio.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover table-light m-table mb-0">
                                            <thead>
                                                <tr class="text-uppercase small">
                                                    <th class="border-top-0" style="width: 150px;">Tipo</th>
                                                    <th class="border-top-0">Nombre del archivo</th>
                                                    <th class="border-top-0 text-center" style="width: 100px;">Tamaño</th>
                                                    <th class="border-top-0 text-right" style="width: 80px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($files as $file)
                                                    <tr>
                                                        <td class="align-middle">
                                                            @php
                                                                $badgeClass = match($file->type) {
                                                                    'csv' => 'm-badge--accent',
                                                                    'pdf_programming' => 'm-badge--brand',
                                                                    'pdf_installation' => 'm-badge--info',
                                                                    default => 'm-badge--secondary',
                                                                };
                                                            @endphp
                                                            <span class="m-badge {{ $badgeClass }} m-badge--wide m-badge--rounded small py-1">
                                                                {{ strtoupper(str_replace('_', ' ', $file->type)) }}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="font-weight-bold text-dark">{{ $file->original_name }}</span>
                                                        </td>
                                                        <td class="text-center align-middle text-muted">
                                                            {{ number_format(($file->file_size ?? 0) / 1024, 1) }} KB
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <a href="{{ route('installation-files.download', $file) }}"
                                                                class="btn btn-outline-metal btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                                                title="Descargar">
                                                                <i class="la la-download text-dark"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="m-alert m-alert--outline alert alert-secondary fade show" role="alert">
                            No hay sub-sitios asociados a esta instalación.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- NOTAS TÉCNICAS --}}
            <div class="m-portlet mb-5 shadow-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-text m--font-brand"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Notas técnicas
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    @can('update', $installation)
                        <form method="POST" action="{{ route('installations.notes.update', $installation) }}"
                            class="m-form m-form--fit">
                            @csrf
                            @method('PATCH')
                            <div class="form-group m-form__group px-0">
                                <textarea name="notes" class="form-control m-input border-faded" rows="4"
                                    placeholder="Escribe aquí las notas técnicas...">{{ old('notes', $installation->notes) }}</textarea>
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--right px-0 pt-3">
                                    <button type="submit" class="btn btn-primary m-btn m-btn--pill m-btn--air">Guardar notas</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="m-section">
                            <div class="m-section__content">
                                <p class="lead text-dark">{{ $installation->notes ?? 'No hay notas técnicas registradas.' }}</p>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            {{-- INCIDENCIAS TÉCNICAS --}}
            <div class="m-portlet mb-5 m-portlet--head-sm shadow-sm borded-left-warning">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-warning m--font-warning"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Incidencias
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="#collapse_incidencias" data-toggle="collapse" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                    <i class="la la-angle-down"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="collapse {{ session('success_issue') || $errors->any() ? 'show' : '' }}" id="collapse_incidencias">
                    <div class="m-portlet__body">
                    @can('update', $installation)
                        <form method="POST" action="{{ route('installations.issues.store', $installation) }}"
                            class="m-form m-form--fit mb-4">
                            @csrf
                            <input type="text" name="title" class="form-control m-input mb-2 border-faded" placeholder="Título"
                                required>
                            <textarea name="description" class="form-control m-input mb-2 border-faded" rows="3" placeholder="Descripción" required></textarea>
                            <button class="btn btn-warning btn-block m-btn m-btn--pill m-btn--air">Registrar incidencia</button>
                        </form>
                    @endcan

                    @if ($installation->issues->isEmpty())
                        <div class="text-center py-4">
                            <i class="la la-check-circle-o font-lg text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">No hay incidencias registradas.</p>
                        </div>
                    @else
                        <div class="m-list-timeline">
                            <div class="m-list-timeline__items">
                                @foreach ($installation->issues as $issue)
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--warning"></span>
                                        <span class="m-list-timeline__text">
                                            <strong>{{ $issue->title }}</strong>
                                            <p class="mb-0 small text-muted">{{ $issue->description }}</p>
                                        </span>
                                        <span class="m-list-timeline__time text-nowrap" style="width: 80px;">
                                            {{ $issue->created_at->format('d/m H:i') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>

            {{-- HISTÓRICO --}}
            <div class="m-portlet m-portlet--head-sm shadow-sm border-left-brand">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-history m--font-brand"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Histórico
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="#collapse_historico" data-toggle="collapse" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                    <i class="la la-angle-down"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="collapse show" id="collapse_historico">
                    <div class="m-portlet__body">
                    @if ($installation->logs->isEmpty())
                        <div class="text-center py-4">
                            <i class="la la-info-circle font-lg text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">No hay intervenciones.</p>
                        </div>
                    @else
                        <div style="max-height: 400px; overflow-y: auto;" class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true">
                            <div class="m-timeline-2">
                                <div class="m-timeline-2__items m--padding-bottom-10">
                                    @foreach ($installation->logs as $log)
                                        <div class="m-timeline-2__item">
                                            <span
                                                class="m-timeline-2__item-time">{{ $log->created_at->format('H:i') }}</span>
                                            <div class="m-timeline-2__item-cricle">
                                                <i class="fa fa-genderless m--font-brand"></i>
                                            </div>
                                            <div class="m-timeline-2__item-text  m--padding-top-5">
                                                <strong class="text-dark">{{ $log->action }}</strong><br>
                                                <span class="text-muted small">{{ $log->user->name }} ·
                                                    {{ $log->created_at->format('d/m/Y') }}</span>
                                                @if ($log->description)
                                                    <p class="mt-2 m-0 small">{{ $log->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    {{-- Reabrir modal si se guardó/eliminó un archivo --}}
    @if(session('open_sub_site_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalId = '#modalFilesSubSite{{ session('open_sub_site_modal') }}';
                $(modalId).modal('show');
            });
        </script>
    @endif

    {{-- SweetAlert para mensajes de éxito --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Aceptar',
                    timer: 3000
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar estado de las flechas para los que empiecen colapsados (si los hay)
            $('.collapse').each(function() {
                if (!$(this).hasClass('show')) {
                    $('a[href="#' + this.id + '"] i').css('transform', 'rotate(0deg)');
                } else {
                    $('a[href="#' + this.id + '"] i').css('transform', 'rotate(180deg)');
                }
            });

            // Rotar flecha al colapsar/expandir
            $('.collapse').on('show.bs.collapse', function () {
                $('a[href="#' + this.id + '"] i').css('transform', 'rotate(180deg)');
            }).on('hide.bs.collapse', function () {
                $('a[href="#' + this.id + '"] i').css('transform', 'rotate(0deg)');
            });
        });
    </script>
@endpush

