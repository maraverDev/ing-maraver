@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Lugares</h3>

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('technician'))
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlaceModal">
                Nuevo lugar
            </button>
        @endif
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Nombre</th>
                <th>Sub-sitios</th>
                <th>Limitadores</th>
                <th>Estudios</th>
            </tr>
        </thead>
        <tbody>
            @forelse($places as $place)
                <tr>
                    <td>{{ $place->name }}</td>
                    <td>{{ $place->sub_sites_count }}</td>
                    <td>{{ $place->max_limiters }}</td>
                    <td>
                        <span class="text-muted">Pendiente</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No hay lugares registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @includeWhen(
        auth()->user()->hasRole('admin') || auth()->user()->hasRole('technician'),
        'places.partials.create-modal'
    )

@endsection
