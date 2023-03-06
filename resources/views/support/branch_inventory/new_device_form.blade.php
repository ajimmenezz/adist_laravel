<form id="new-device-form" class="needs-validation" novalidate>
    <input type="hidden" id="new-device-service" value="{{ $serviceId }}">
    <input type="hidden" id="new-device-area" value="{{ $areaId }}">
    <input type="hidden" id="new-device-point" value="{{ $point }}">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-9 form-group">
            <label class="form-label require">Equipo</label>
            <select id="new-device-model" class="form-control" style="width:100%">
                <option value="0">Seleccione un equipo</option>
                @foreach ($models as $model)
                    <option value="{{ $model->Id }}">
                        {{ mb_strtoupper($model->ModelCompact) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 form-group">
            <label for="new-device-serial">{{ __('Serie') }}</label>
            <input type="text" class="form-control" id="new-device-serial" placeholder="{{ __('Serie') }}">
            <div class="form-text"><i
                    class="bi bi-question-circle-fill me-2"></i>{{ __('Si el equipo no tiene serie o es ilegile, deje en blanco') }}
            </div>
        </div>
        <div class="col-12 col-md-6 form-group">
            <label class="require" for="new-device-status">Estado</label>
            <select id="new-device-status" class="form-control">
                @foreach ($device_status as $status)
                    <option value="{{ $status->Id }}">
                        {{ mb_strtoupper($status->Nombre) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</form>
