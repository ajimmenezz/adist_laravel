<form id="newDestinationForm" novalidate>
    <div class="form-group">
        <label for="branch" class="require">Sucursal</label>
        <select class="form-select" id="branch" aria-label="Sucursal" aria-describedby="branchHelp" required>
            <option selected disabled value="">Selecciona una sucursal</option>
            @if (isset($branches) && count($branches) > 0)
                @foreach ($branches as $branch)
                    <option value="{{ $branch->Id }}">{{ $branch->Nombre }}</option>
                @endforeach
            @endif
        </select>
        <div class="invalid-feedback fw-bold">Por favor selecciona una sucursal</div>
        <small id="branchHelp" class="form-text text-muted lh-1">¿En que sucursal deben ser entregados los
            equipos?</small>
    </div>
    <div class="form-group">
        <label for="branch">Área de Atención</label>
        <select class="form-select" id="attentionArea" aria-label="Área" aria-describedby="areaHelp">
            <option selected disabled value="">Selecciona un área</option>
            @if (isset($areas) && count($areas) > 0)
                @foreach ($areas as $area)
                    <option value="{{ $area->Id }}">{{ $area->Nombre }}</option>
                @endforeach
            @endif
        </select>
        <small id="areaHelp" class="form-text text-muted lh-1">Seleccione el área destinada para los
            equipos</small>
    </div>
    <button type="submit" id="save-destination-button" class="btn btn-success btn-block my-4 fw-bold">Selección de
        Equipos</button>
    <div class="text-center">
        <button type="button" class="close-drawer-button btn btn-outline-danger btn-sm fw-bold">Cancelar</button>
    </div>
</form>
