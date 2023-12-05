<form id="acceptTransferCodeForm" novalidate>
    <div class="form-group">
        <label for="transfer_code" class="require">Código de aceptación</label>
        <input type="text" class="form-control" id="transfer_code" aria-describedby="transfer_codeHelp" maxlength="7"
            required>
        <div class="invalid-feedback fw-bold">Por favor ingresa el codigo de autorización</div>
        <small id="transfer_codeHelp" class="form-text text-muted lh-1">Introduzca el código del traspaso. El código fué
            enviado vía telegram al encargado del almacén virtual</small>
    </div>
</form>
<div class="d-flex flex-column">
    <div>¿No has recibido el código? <button class="btn btn-link" id="resendCode-btn">Reenviar ahora</button></div>
</div>
