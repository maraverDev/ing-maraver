@extends('layouts.metronic.app')

@section('title', 'Detalle de instalación')

{{-- MODALES --}}
@include('installations.modals.edit')
@include('installations.modals.files')

@section('content')

    {{-- HEADER / TOOLBAR --}}
    <div class="m-portlet m-portlet--last m-portlet--head-lg m-portlet--responsive-mobile mb-5" id="main_portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-progress">
                <!-- empty -->
            </div>
            <div class="m-portlet__head-wrapper">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="flaticon-map-location text-primary"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            Instalación · {{ $installation->place->name }}
                            <small>{{ $installation->installation_date->format('d/m/Y H:i') }}</small>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
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
                        <li class="m-portlet__nav-item">
                            <a href="{{ route('installations.index') }}"
                                class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m-btn--air">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>Volver</span>
                                </span>
                            </a>
                        </li>
                        @can('update', $installation)
                            <li class="m-portlet__nav-item">
                                <button class="btn btn-primary m-btn m-btn--icon m-btn--wide m-btn--md m-btn--air" data-toggle="modal"
                                    data-target="#modalEditInstallation">
                                    <span>
                                        <i class="la la-edit"></i>
                                        <span>Editar</span>
                                    </span>
                                </button>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {{-- INFO GENERAL --}}
            <div class="m-section">
                <div class="m-section__content">
                    <div class="row m-row--no-padding m-row--col-separator-xl">
                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <div class="m-widget24">
                                <div class="m-widget24__item">
                                    <h4 class="m-widget24__title">Lugar</h4>
                                    <span class="m-widget24__desc">Sede principal</span>
                                    <span class="m-widget24__stats m--font-brand">{{ $installation->place->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <div class="m-widget24">
                                <div class="m-widget24__item">
                                    <h4 class="m-widget24__title">Fecha</h4>
                                    <span class="m-widget24__desc">Día de intervención</span>
                                    <span
                                        class="m-widget24__stats m--font-info">{{ $installation->installation_date->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <div class="m-widget24">
                                <div class="m-widget24__item">
                                    <h4 class="m-widget24__title">Limitadores</h4>
                                    <span class="m-widget24__desc">Equipos instalados</span>
                                    <span class="m-widget24__stats m--font-danger">{{ $installation->limiters_installed }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <div class="m-widget24">
                                <div class="m-widget24__item">
                                    <h4 class="m-widget24__title">Sub-sitios</h4>
                                    <span class="m-widget24__desc">Áreas asociadas</span>
                                    <span
                                        class="m-widget24__stats m--font-success">{{ $installation->subSites->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row m--margin-top-20">
        <div class="col-xl-8">
            {{-- ARCHIVOS POR SUB-SITIO --}}
            <div class="m-portlet m-portlet--mobile mb-5">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
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

                        <div class="m-portlet m-portlet--bordered m-portlet--unair mb-4">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text font-weight-bold">
                                            {{ $subSite->name }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">
                                    @can('update', $installation)
                                        <button class="btn btn-outline-brand m-btn m-btn--icon btn-sm" data-toggle="modal"
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
                                    <div class="m-alert m-alert--outline alert alert-warning fade show mb-0" role="alert">
                                        No hay archivos cargados para este sub-sitio.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered m-table m-table--head-bg-brand mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Nombre del archivo</th>
                                                    <th class="text-center">Tamaño</th>
                                                    <th class="text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($files as $file)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <span
                                                                class="m-badge m-badge--info m-badge--wide">{{ strtoupper(str_replace('_', ' ', $file->type)) }}</span>
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <span class="font-weight-bold text-dark">{{ $file->original_name }}</span>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            {{ number_format(($file->file_size ?? 0) / 1024, 1) }} KB
                                                        </td>
                                                        <td class="text-right" style="vertical-align: middle;">
                                                            <a href="{{ route('installation-files.download', $file) }}"
                                                                class="btn btn-secondary m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                                                title="Descargar">
                                                                <i class="la la-download"></i>
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
            <div class="m-portlet mb-5">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Notas técnicas
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    @can('update', $installation)
                        <form method="POST" action="{{ route('installations.notes.update', $installation) }}"
                            class="m-form m-form--fit m-form--label-align-right">
                            @csrf
                            @method('PATCH')
                            <div class="form-group m-form__group">
                                <textarea name="notes" class="form-control m-input" rows="4"
                                    placeholder="Escribe aquí las notas técnicas...">{{ old('notes', $installation->notes) }}</textarea>
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--right px-0">
                                    <button type="submit" class="btn btn-primary">Guardar notas</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="m-section">
                            <div class="m-section__content">
                                <p class="lead">{{ $installation->notes ?? 'No hay notas técnicas registradas.' }}</p>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            {{-- INCIDENCIAS TÉCNICAS --}}
            <div class="m-portlet mb-5 m-portlet--head-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
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
                            <input type="text" name="title" class="form-control m-input mb-2" placeholder="Título"
                                required>
                            <textarea name="description" class="form-control m-input mb-2" rows="3" placeholder="Descripción" required></textarea>
                            <button class="btn btn-warning btn-block">Registrar incidencia</button>
                        </form>
                    @endcan

                    @if ($installation->issues->isEmpty())
                        <p class="text-muted text-center py-4">No hay incidencias registradas.</p>
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
                                        <span class="m-list-timeline__time text-nowrap" style="width: 100px;">
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
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Histórico de intervenciones
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
                <div class="collapse" id="collapse_historico">
                    <div class="m-portlet__body">
                    @if ($installation->logs->isEmpty())
                        <p class="text-muted text-center py-4">No hay intervenciones registradas.</p>
                    @else
                        <div style="max-height: 400px; overflow-y: auto;">
                            <div class="m-timeline-2">
                                <div class="m-timeline-2__items m--padding-bottom-30">
                                    @foreach ($installation->logs as $log)
                                        <div class="m-timeline-2__item">
                                            <span
                                                class="m-timeline-2__item-time">{{ $log->created_at->format('H:i') }}</span>
                                            <div class="m-timeline-2__item-cricle">
                                                <i class="fa fa-genderless m--font-brand"></i>
                                            </div>
                                            <div class="m-timeline-2__item-text  m--padding-top-5">
                                                <strong>{{ $log->action }}</strong><br>
                                                <span class="text-muted">{{ $log->user->name }} ·
                                                    {{ $log->created_at->format('d/m/Y') }}</span>
                                                @if ($log->description)
                                                    <p class="mt-2 m-0">{{ $log->description }}</p>
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

