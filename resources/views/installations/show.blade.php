@extends('layouts.metronic.app')

@section('title', 'Detalle de instalación')

{{-- MODALES --}}
@include('installations.modals.edit')
@include('installations.modals.files')

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h3 class="fw-bold mb-1">
            Instalación · {{ $installation->place->name }}
        </h3>
        <div class="text-muted">
            {{ $installation->installation_date->format('d/m/Y H:i') }}
        </div>

        @can('update', $installation)
            <button class="btn btn-light-primary mt-2"
                    data-toggle="modal"
                    data-target="#modalEditInstallation">
                Editar instalación
            </button>
        @endcan
    </div>

    <div class="d-flex align-items-center gap-3">
        @switch($installation->installation_status)
            @case('complete')
                <span class="badge badge-light-success text-success fw-bold">
                    Completa
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

        <a href="{{ route('installations.index') }}" class="btn btn-light">
            Volver
        </a>
    </div>
</div>

{{-- INFO GENERAL --}}
<div class="card mb-5">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="text-muted mb-1">Lugar</div>
                <div class="fw-bold">{{ $installation->place->name }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted mb-1">Fecha</div>
                <div class="fw-bold">{{ $installation->installation_date->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted mb-1">Limitadores</div>
                <div class="fw-bold">{{ $installation->limiters_installed }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted mb-1">Sub-sitios</div>
                <div class="fw-bold">{{ $installation->subSites->count() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ARCHIVOS POR SUB-SITIO --}}
<div class="card mb-5">
    <div class="card-header">
        <strong>Archivos técnicos por sub-sitio</strong>
    </div>

    <div class="card-body">

        @forelse($installation->subSites as $subSite)

            @php
                $files = $installation->files
                    ->where('sub_site_id', $subSite->id);

                $order = ['csv' => 1, 'pdf_programming' => 2, 'pdf_installation' => 3];
                $files = $files->sortBy(fn($f) => $order[$f->type] ?? 99);
            @endphp

            <div class="card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ $subSite->name }}</h6>

                    @can('update', $installation)
                        <button class="btn btn-sm btn-light-primary"
                                data-toggle="modal"
                                data-target="#modalFilesSubSite{{ $subSite->id }}">
                            Gestionar archivos
                        </button>
                    @endcan
                </div>

                <div class="card-body py-3">

                    @if($files->isEmpty())
                        <span class="badge badge-light-warning text-warning fw-bold">
                            Sin archivos
                        </span>
                    @else
                        <table class="table table-sm align-middle mb-0">
                            <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td style="width:180px">
                                            <span class="badge badge-light-info text-info fw-bold">
                                                {{ strtoupper(str_replace('_', ' ', $file->type)) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">{{ $file->original_name }}</div>
                                            <div class="text-muted small">
                                                {{ number_format(($file->file_size ?? 0) / 1024, 1) }} KB
                                            </div>
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('installation-files.download', $file) }}"
                                               class="btn btn-sm btn-light-primary">
                                                Descargar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

        @empty
            <p class="text-muted mb-0">No hay sub-sitios asociados.</p>
        @endforelse

    </div>
</div>

{{-- NOTAS --}}
<div class="card mb-5">
    <div class="card-header">
        <strong>Notas técnicas</strong>
    </div>

    <div class="card-body">
        @can('update', $installation)
            <form method="POST" action="{{ route('installations.notes.update', $installation) }}">
                @csrf
                @method('PATCH')

                <textarea name="notes"
                          class="form-control mb-3"
                          rows="4">{{ old('notes', $installation->notes) }}</textarea>

                <button class="btn btn-primary">Guardar notas</button>
            </form>
        @else
            <p>{{ $installation->notes ?? 'No hay notas técnicas.' }}</p>
        @endcan
    </div>
</div>

{{-- HISTÓRICO --}}
<div class="card mb-5">
    <div class="card-header">
        <strong>Histórico de intervenciones</strong>
    </div>

    <div class="card-body">
        @if($installation->logs->isEmpty())
            <p class="text-muted mb-0">No hay intervenciones registradas.</p>
        @else
            <ul class="timeline">
                @foreach($installation->logs as $log)
                    <li class="timeline-item">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-content">
                            <div class="fw-bold">{{ $log->action }}</div>
                            <div class="text-muted small">
                                {{ $log->user->name }} · {{ $log->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($log->description)
                                <div class="mt-1">{{ $log->description }}</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

{{-- INCIDENCIAS --}}
<div class="card mb-5">
    <div class="card-header">
        <strong>Incidencias técnicas</strong>
    </div>

    <div class="card-body">
        @can('update', $installation)
            <form method="POST"
                  action="{{ route('installations.issues.store', $installation) }}"
                  class="mb-4">
                @csrf
                <input type="text" name="title" class="form-control mb-2" placeholder="Título" required>
                <textarea name="description" class="form-control mb-2" rows="3" placeholder="Descripción" required></textarea>
                <button class="btn btn-warning">Registrar incidencia</button>
            </form>
        @endcan

        @if($installation->issues->isEmpty())
            <p class="text-muted mb-0">No hay incidencias.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach($installation->issues as $issue)
                    <li class="list-group-item">
                        <strong>{{ $issue->title }}</strong>
                        <div class="text-muted small">
                            {{ $issue->user->name }} · {{ $issue->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="mt-2">{{ $issue->description }}</div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

@endsection
@push('scripts')
    @if(session('open_sub_site_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalId = '#modalFilesSubSite{{ session('open_sub_site_modal') }}';
                $(modalId).modal('show');
            });
        </script>
    @endif
@endpush

