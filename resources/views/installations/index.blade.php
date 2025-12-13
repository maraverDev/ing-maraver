@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Control de instalaciones</h3>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInstallationModal">
            Nueva instalación
        </button>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Limitadores</th>
                <th>Archivos</th>
            </tr>
        </thead>
        <tbody>
            @forelse($installations as $installation)
                <tr>
                    <td>{{ $installation->installation_date->format('d/m/Y') }}</td>
                    <td>{{ $installation->place->name }}</td>
                    <td>{{ $installation->limiters_installed }}</td>
                    <td>
                        <span class="text-muted">Descargar</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No hay instalaciones registradas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('installations.partials.create-modal')

@endsection