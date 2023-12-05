<div class="modal fade" id="acceptDevicesModal" tabindex="-1" aria-labelledby="acceptDevicesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="acceptDevicesModalLabel">Seleccione los equipos que esta recibiendo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="col-12 text-end my-3">
                            <button type="button" class="btn btn-outline-success btn-sm fw-bold ls-2"
                                id="acceptAllDevices-btn">Aceptar todos <i class="bi bi-check-all"></i></button>
                        </div>
                        <x-generals.table id="assigned-inventory-table" classes="fs-8" :headers="$table_headers_assigned_inventory" />
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="col-12 text-start my-3">
                            <button type="button" class="btn btn-outline-warning btn-sm fw-bold ls-2"
                                id="returnAllDevices-btn"><i class="bi bi-box-arrow-left"></i> Regresar todos</button>
                        </div>
                        <x-generals.table id="accepted-inventory-table" classes="fs-8" :headers="$table_headers_accepted_devices" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold me-4"
                    data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary fw-bold ls-2" id="acceptDevices-btn"><i
                        class="bi bi-save-fill"></i> Aceptar Equipos</button>
            </div>
        </div>
    </div>
</div>
