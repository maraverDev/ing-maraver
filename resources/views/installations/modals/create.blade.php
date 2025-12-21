<style>
    .modal-installation-wide {
        max-width: 50%;
    }
</style>
<div class="modal fade" id="modalCreateInstallation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-installation-wide" role="document">
        <div class="modal-content">

            <form id="formCreateInstallation" action="{{ route('installations.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title">Nueva instalación</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    @include('installations.modals.partials.installation-data')

                    <hr>

                    @include('installations.modals.partials.sub-sites')

                    <hr>

                    @include('installations.modals.partials.files')

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar instalación
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>