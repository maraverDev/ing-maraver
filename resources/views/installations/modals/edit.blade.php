<div class="modal fade" id="modalEditInstallation" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('installations.update', $installation) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Editar instalación</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label>Fecha y hora</label>
                        <input type="datetime-local" name="installation_date" class="form-control"
                            value="{{ $installation->installation_date->format('Y-m-d\TH:i') }}" required>
                    </div>

                    {{-- Limitadores --}}
                    <div class="form-group">
                        <label>Nº de limitadores</label>
                        <input type="number" name="limiters_installed" class="form-control" min="1"
                            value="{{ $installation->limiters_installed }}" required>
                    </div>

                    {{-- Sub-sitios --}}
                    <div class="form-group">
                        <label>Sub-sitios</label>

                        @foreach($installation->place->subSites as $subSite)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sub_sites[]"
                                    value="{{ $subSite->id }}" {{ $installation->subSites->contains($subSite) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $subSite->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button class="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>