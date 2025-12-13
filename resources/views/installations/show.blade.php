@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Detalle de instalación</h3>

    <a href="{{ route('installations.index') }}" class="btn btn-secondary">
        Volver
    </a>
</div>

{{-- Info general --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>Información general</strong>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <strong>Lugar</strong><br>
                {{ $installation->place->name }}
            </div>

            <div class="col-md-4">
                <strong>Fecha de instalación</strong><br>
                {{ \Carbon\Carbon::parse($installation->installation_date)->format('d/m/Y') }}
            </div>

            <div class="col-md-4">
                <strong>Nº de limitadores instalados</strong><br>
                {{ $installation->limiters_installed }}
            </div>
        </div>
    </div>
</div>

{{-- Sub-sitios --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>Sub-sitios incluidos</strong>
    </div>

    <div class="card-body">
        @if($installation->subSites->isEmpty())
            <p class="text-muted mb-0">No hay sub-sitios asociados a esta instalación.</p>
        @else
            <ul class="mb-0">
                @foreach($installation->subSites as $subSite)
                    <li>{{ $subSite->name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

{{-- Archivos por sub-sitio --}}
<div class="card">
    <div class="card-header">
        <strong>Archivos técnicos por sub-sitio</strong>
    </div>

    <div class="card-body">
        @forelse($installation->subSites as $subSite)

            @php
                $files = $installation->files->where('sub_site_id', $subSite->id);

                // Si quieres un orden consistente en tabla:
                $order = ['csv' => 1, 'pdf_programming' => 2, 'pdf_installation' => 3];
                $files = $files->sortBy(fn($f) => $order[$f->type] ?? 99);
            @endphp

            <div class="mb-4">
                <h6 class="mb-3">{{ $subSite->name }}</h6>

                @if($files->isEmpty())
                    <p class="text-muted">No hay archivos asociados.</p>
                @else
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 180px;">Tipo</th>
                                <th>Archivo</th>
                                <th style="width: 140px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                <tr>
                                    <td>
                                        @switch($file->type)
                                            @case('csv')
                                                CSV
                                                @break
                                            @case('pdf_programming')
                                                PDF Programación
                                                @break
                                            @case('pdf_installation')
                                                PDF Instalación
                                                @break
                                            @default
                                                {{ $file->type }}
                                        @endswitch
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $file->original_name }}</div>
                                        <div class="text-muted small">
                                            {{ number_format(($file->file_size ?? 0) / 1024, 1) }} KB
                                            @if(!empty($file->mime_type))
                                                · {{ $file->mime_type }}
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('installation-files.download', $file) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Descargar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        @empty
            <p class="text-muted mb-0">No hay sub-sitios asociados a esta instalación.</p>
        @endforelse
    </div>
</div>
{{-- Notas técnicas --}}
<div class="card mt-4">
    <div class="card-header">
        <strong>Notas técnicas</strong>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @can('update', $installation)

            <form method="POST" action="{{ route('installations.notes.update', $installation) }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="5"
                        placeholder="Observaciones técnicas, incidencias, ajustes realizados…"
                    >{{ old('notes', $installation->notes) }}</textarea>
                </div>

                <button class="btn btn-primary">
                    Guardar notas
                </button>
            </form>

        @else

            @if($installation->notes)
                <p class="mb-0">{{ $installation->notes }}</p>
            @else
                <p class="text-muted mb-0">No hay notas técnicas registradas.</p>
            @endif

        @endcan

    </div>
</div>
{{-- Histórico de intervenciones --}}
<div class="card mt-4">
    <div class="card-header">
        <strong>Histórico de intervenciones</strong>
    </div>

    <div class="card-body">

        @if($installation->logs->isEmpty())
            <p class="text-muted mb-0">No hay intervenciones registradas.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach($installation->logs as $log)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $log->action }}</strong>
                            <span class="text-muted small">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <div class="text-muted small mb-1">
                            {{ $log->user->name }}
                        </div>

                        @if($log->description)
                            <div>{{ $log->description }}</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</div>

{{-- Incidencias técnicas --}}
<div class="card mt-4">
    <div class="card-header">
        <strong>Incidencias técnicas</strong>
    </div>

    <div class="card-body">

        @can('update', $installation)
            <form method="POST" action="{{ route('installations.issues.store', $installation) }}" class="mb-4">
                @csrf

                <div class="mb-2">
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Título de la incidencia"
                        required
                    >
                </div>

                <div class="mb-2">
                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                        placeholder="Descripción técnica"
                        required
                    ></textarea>
                </div>

                <button class="btn btn-warning">
                    Registrar incidencia
                </button>
            </form>
        @endcan

        @if($installation->issues->isEmpty())
            <p class="text-muted mb-0">No hay incidencias registradas.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach($installation->issues as $issue)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $issue->title }}</strong>
                                <div class="text-muted small">
                                    {{ $issue->user->name }} ·
                                    {{ $issue->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <span class="badge {{ $issue->status === 'open' ? 'bg-danger' : 'bg-success' }}">
                                {{ $issue->status === 'open' ? 'Abierta' : 'Cerrada' }}
                            </span>
                        </div>

                        <div class="mt-2">
                            {{ $issue->description }}
                        </div>

                        @can('update', $installation)
                            @if($issue->status === 'open')
                                <form
                                    method="POST"
                                    action="{{ route('installation-issues.close', $issue) }}"
                                    class="mt-2"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-sm btn-outline-success">
                                        Cerrar incidencia
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</div>

@endsection
