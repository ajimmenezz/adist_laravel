<form id="not-censo-item-form" class="needs-validation" novalidate>
    <div class="row mb-3 align-items-end">
        <div class="col-12 col-sm-6">
            <h3 class="m-0 p-0">{{ __('Caja ') }}<span id="not-censo-item-box"></span></h3>
            <input type="hidden" id="not-censo-item-box-input" value="0">
        </div>
        <div class="col-12 col-sm-6">
            <div class="form-check form-switch float-end">
                <input class="form-check-input" type="checkbox" role="switch" id="not-censo-item-switch-type">
                <label class="form-check-label" for="not-censo-item-switch-type">¿Componente?</label>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <select class="form-select form-control" style="width:100%" id="pickup-model-item" aria-label="{{ __('Modelo') }}"
                required>
                <option value="" selected>{{ __('Selecciona un modelo') }}</option>
                @if (isset($models) && count($models) > 0)
                    @foreach ($models as $model)
                        <option value="{{ $model->Id }}">{{ $model->ModelCompact }}</option>
                    @endforeach
                @endif
            </select>
            <div class="invalid-feedback">
                {{ __('Por favor selecciona un modelo de equipo') }}
            </div>
        </div>
    </div>
    <div class="row mb-3 full-device-type">
        <div class="col">
            <div class="form-floating">
                <input type="text" class="form-control" id="pickup-serial-item" placeholder="{{ __('Serial') }}">
                <label for="pickup-serial-item">{{ __('Serie') }}</label>
            </div>
        </div>
    </div>
    <div class="row components-type d-none">
        <div class="col-12">
            <h5 class="m-0 p-0">{{ __('Componentes') }}</h5>
        </div>
        <div id="components-list" class="col-12"></div>
    </div>
</form>
