<div class="modal fade" id="destinationDevicesModal" tabindex="-1" aria-labelledby="destinationDevicesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="destinationDevicesModalLabel">Selección de equipos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <x-generals.table id="inventory-table" classes="fs-8" :headers="$table_headers_inventory" />
                    </div>
                    <div class="col-12 col-md-6">
                        <x-generals.table id="assigned-inventory-table" classes="fs-8" :headers="$table_headers_assigned_devices" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold me-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary fw-bold ls-2" id="assignDevices-btn"><i class="bi bi-save-fill"></i> Asignar Equipos</button>
            </div>
        </div>
    </div>
</div>
