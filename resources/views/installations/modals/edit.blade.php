<div class="modal fade" id="modalEditInstallation" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('installations.update', $installation) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar instalación</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body p-lg-10">

                    {{-- Fecha y Hora --}}
                    <div class="form-group mb-8">
                        <label class="form-label fw-bold text-dark fs-6 mb-2">Fecha y hora de instalación</label>
                        <input type="datetime-local" name="installation_date" class="form-control form-control-solid"
                            value="{{ $installation->installation_date->format('Y-m-d\TH:i') }}" required>
                    </div>

                    {{-- Limitadores Instalados --}}
                    <div class="form-group mb-8">
                        <label class="form-label fw-bold text-dark fs-6 mb-2">Nº de limitadores instalados</label>
                        <select name="limiters_installed" class="form-control form-control-solid" required>
                            @for($i = 1; $i <= ($installation->place->max_limiters ?? 1); $i++)
                                <option value="{{ $i }}" {{ $installation->limiters_installed == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'limitador' : 'limitadores' }}
                                </option>
                            @endfor
                        </select>
                        <span class="text-muted fs-7 mt-1">Capacidad máxima del lugar: {{ $installation->place->max_limiters }}</span>
                    </div>

                    {{-- Selección de Sub-sitios --}}
                    <div class="form-group mb-0">
                        <label class="form-label fw-bold text-dark fs-6 mb-4">Sub-sitios incluidos</label>
                        
                        <div class="row g-4" id="editSubSitesContainer">
                            @foreach($installation->place->subSites as $subSite)
                                <div class="col-md-6 col-lg-4">
                                    <label class="sub-site-card d-flex align-items-center p-4 h-100">
                                        <input type="checkbox" name="sub_sites[]" value="{{ $subSite->id }}"
                                            class="form-check-input me-3"
                                            {{ $installation->subSites->contains($subSite) ? 'checked' : '' }}>
                                        <div class="sub-site-content">
                                            <span class="fw-bold text-gray-800">{{ $subSite->name }}</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light-primary fw-bold" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>