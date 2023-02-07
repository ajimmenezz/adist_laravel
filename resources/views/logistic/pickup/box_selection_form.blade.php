<form id="assign-box-form" class="needs-validation" novalidate>
    <div class="mb-3">
        <div class="form-floating">
            <select class="form-select form-control" id="pickup-box" aria-label="{{ __('Caja') }}" required>
                <option value="" selected>{{ __('Selecciona una caja') }}</option>
                @for($i = 1; $i <= 100; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <label for="pickup-box" class="require">{{ __('# Caja') }}</label>
            <div class="invalid-feedback">
                {{ __('Por favor selecciona un número de caja') }}
            </div>
        </div>
    </div>
</form>
