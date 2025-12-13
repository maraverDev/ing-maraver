@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h3>{{ $place->name }}</h3>

        <p class="text-muted mb-1">
            Máx. limitadores permitidos: <strong>{{ $place->max_limiters }}</strong>
        </p>
    </div>

    <hr>

    <h5 class="mb-3">Sub-sitios y estudios acústicos</h5>

    @forelse($place->subSites as $subSite)
        <div class="card mb-3">
            <div class="card-body">

                <h6 class="card-title mb-3">
                    {{ $subSite->name }}
                </h6>

                @forelse($subSite->acousticStudies as $study)
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">📄</span>

                        <a href="{{ route('acoustic-studies.download', $study) }}">
                            {{ $study->original_name }}
                        </a>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        No hay estudios acústicos asociados a este sub-sitio.
                    </p>
                @endforelse

            </div>
        </div>
    @empty
        <div class="alert alert-secondary">
            Este lugar no tiene sub-sitios registrados.
        </div>
    @endforelse

    <div class="mt-4">
        <a href="{{ route('places.index') }}" class="btn btn-outline-secondary">
            Volver al listado
        </a>
    </div>

@endsection