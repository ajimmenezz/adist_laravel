<form id="new-pickup-form" class="needs-validation" novalidate>
    <div class="mb-3">
        <div class="form-floating">
            <select class="form-select form-control" id="pickup-branch" aria-label="{{ __('Sucursal') }}" required>
                <option value="" selected>{{ __('Selecciona una sucursal') }}</option>
                @if (isset($branches) && count($branches) > 0)
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->Id }}">{{ $branch->Nombre }}</option>
                    @endforeach
                @endif
            </select>
            <label for="pickup-branch" class="require">{{ __('Sucursal') }}</label>
            <div class="invalid-feedback">
                {{ __('Por favor selecciona una sucursal') }}
            </div>
        </div>
    </div>
</form>
