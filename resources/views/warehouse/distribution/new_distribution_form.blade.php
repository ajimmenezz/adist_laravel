<form id="newDistributionForm" novalidate>
    <div class="form-group">
        <label for="customer" class="require">Cliente</label>
        <select class="form-select" id="customer" aria-label="Cliente" aria-describedby="customerHelp" required>
            <option selected disabled value="">Selecciona un cliente</option>
            @if (isset($customers) && count($customers) > 0)
                @foreach ($customers as $customer)
                    <option value="{{ $customer->Id }}">{{ $customer->Nombre }}</option>
                @endforeach
            @endif
        </select>
        <div class="invalid-feedback fw-bold">Por favor selecciona un cliente</div>
        <small id="customerHelp" class="form-text text-muted lh-1">Los proyectos de distribución solo aplican para un
            cliente a la vez</small>
    </div>
    <div class="form-group">
        <label for="project_name" class="require">Nombre del Proyecto</label>
        <input type="text" class="form-control" id="project_name" aria-describedby="projectHelp"
            placeholder="Nombre del Proyecto" required>
        <div class="invalid-feedback fw-bold">El nombre del proyecto es un campo obligatorio</div>
        <small id="projectHelp" class="form-text text-muted lh-1">Elige un nombre para este proyecto. Será útil en el
            seguimiento de todos los destinos de distribución asociados</small>
    </div>

    <button type="submit" id="save-distribution-button" class="btn btn-success btn-block my-4 fw-bold">Guardar</button>
    <div class="text-center">
        <button type="button" class="close-drawer-button btn btn-outline-danger btn-sm fw-bold">Cancelar</button>
    </div>
</form>
