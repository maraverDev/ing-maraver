<div class="modal fade" id="modalCreatePlace" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">

            <form action="{{ route('places.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo lugar</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    {{-- DATOS DEL LUGAR --}}
                    @include('places.modals.partials.place-data')

                    <hr>

                    {{-- SUB-SITIOS --}}
                    @include('places.modals.partials.sub-sites')

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar lugar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>