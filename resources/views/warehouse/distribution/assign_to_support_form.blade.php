<form id="assignToSupportForm" novalidate>
    <div class="form-group">
        <label for="technicians" class="require">Personal de soporte</label>
        <select class="form-select" id="technicians" aria-label="Sucursal" aria-describedby="techniciansHelp" required>
            <option selected disabled value="">Selecciona al personal de soporte</option>
            @if (isset($users) && count($users) > 0)
                @foreach ($users as $user)
                    <option value="{{ $user->Id }}">{{ $user->User_name . ' (' . $user->User_profile . ')' }}
                    </option>
                @endforeach
            @endif
        </select>
        <div class="invalid-feedback fw-bold">Por favor selecciona a un personal de soporte</div>
        <small id="techniciansHelp" class="form-text text-muted lh-1">¿A qué personal de soporte le entregará los
            equipos?</small>
    </div>
    <button type="submit" id="save-support-assign-button" class="btn btn-success btn-block my-4 fw-bold">Asignar a
        soporte</button>
    <div class="text-center">
        <button type="button" class="btn btn-outline-danger btn-sm fw-bold close-drawer-button">Cancelar</button>
    </div>
</form>
