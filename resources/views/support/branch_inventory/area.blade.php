@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/support/branch_inventories/area.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-9 col-md-10 col-lg-11 my-2 table-responsive">
                    <div class="d-flex">
                        @isset($points_by_area[$area->Id])
                            @for ($i = 1; $i <= $points_by_area[$area->Id]; $i++)
                                <div class="mx-1 pb-3">
                                    <button data-point="{{ $i }}"
                                        class="point-button btn-{{ isset($inputs['point']) && $inputs['point'] == $i ? '' : 'outline-' }}secondary
                                    btn lh-sm">
                                        <span class="fs-1">{{ $i }}</span><br>
                                        <span class="fs-7 text-uppercase">Punto</span>
                                    </button>
                                </div>
                            @endfor
                        @else
                            <div>No hay puntos registrados.</div>
                        @endisset
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-2 col-lg-1 p-0 my-2 ps-sm-3">
                    <button id="add-point-button" data-service="{{ $service->Id }}" data-area="{{ $area->Id }}"
                        class="btn-success btn btn-block">
                        <i class="bi bi-plus-circle-fill fs-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if (isset($inputs['point']) && $inputs['point'] <= $points_by_area[$area->Id])
        <div class="card mb-5">
            <div class="card-body">
                @foreach ($point_devices as $device)
                    <div id="device-card-{{ $device->Id }}"
                        class="device-card card border border-1 border-rounded bg-custom-gray {{ $device->IdEstatus == 17 ? '' : 'border-danger' }}">
                        <div class="card-header table-responsive bg-transparent">
                            <ul class="nav nav-tabs border-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="device-info-tab-{{ $device->Id }}" data-bs-toggle="tab"
                                        href="#device-info-section-{{ $device->Id }}" role="tab"
                                        aria-controls="Equipo / Item" aria-selected="true">Equipo /Item</a>
                                </li>
                                @if (isset($device->features) && count($device->features) > 0)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="device-features-tab-{{ $device->Id }}"
                                            data-bs-toggle="tab" href="#device-features-section-{{ $device->Id }}"
                                            role="tab" aria-controls="Características" aria-selected="false"
                                            tabindex="-1">Características</a>
                                    </li>
                                @endif
                                @if (isset($device->accesories) && count($device->accesories) > 0)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="device-accesories-tab-{{ $device->Id }}"
                                            data-bs-toggle="tab" href="#device-accesories-section-{{ $device->Id }}"
                                            role="tab" aria-controls="Accesorios" aria-selected="false"
                                            tabindex="-1">Accesorios</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-body p-2">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="device-info-section-{{ $device->Id }}"
                                    role="tabpanel" aria-labelledby="device-info-tab-{{ $device->Id }}">
                                    <div class="row">
                                        <div class="col-12 col-lg-9 form-group lh-sm">
                                            <label class="form-label require">Equipo</label>
                                            <select data-service="{{ $service->Id }}" data-area="{{ $area->Id }}"
                                                data-point="{{ $inputs['point'] }}" data-model="{{ $device->IdModelo }}"
                                                data-id="{{ $device->Id }}" class="form-control censo-model-list">
                                                <option value="0">Seleccione un equipo</option>
                                                @foreach ($models as $model)
                                                    <option value="{{ $model->Id }}"
                                                        {{ $model->Id == $device->IdModelo ? 'selected' : '' }}>
                                                        {{ mb_strtoupper($model->ModelCompact) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-6 form-group">
                                            <label>Serie</label>
                                            <input type="text" class="form-control censo-serial"
                                                data-id="{{ $device->Id }}" data-point="{{ $inputs['point'] }}"
                                                data-serial="{{ $device->Serie }}"
                                                value="{{ in_array($device->Serie, ['', 'ILEGIBLE']) ? 'ILEGIBLE' : $device->Serie }}">
                                            <div class="form-text"><i class="bi bi-question-circle-fill me-2"></i>Si el
                                                equipo
                                                no
                                                tiene
                                                serie
                                                o es ilegile, deje en blanco</div>
                                        </div>
                                        <div class="col-12 col-lg-6 form-group">
                                            <label class="require">Estado</label>
                                            <select class="form-control censo-status" data-id="{{ $device->Id }}"
                                                data-point="{{ $inputs['point'] }}"
                                                data-status="{{ $device->IdEstatus }}">
                                                @foreach ($device_status as $status)
                                                    <option value="{{ $status->Id }}"
                                                        {{ $status->Id == $device->IdEstatus ? 'selected' : '' }}>
                                                        {{ mb_strtoupper($status->Nombre) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @if (isset($device->features) && count($device->features) > 0)
                                    <div class="tab-pane fade" id="device-features-section-{{ $device->Id }}"
                                        role="tabpanel" aria-labelledby="device-features-tab-{{ $device->Id }}">
                                        <div class="row mt-3">
                                            @foreach ($device->features as $feature)
                                                <div class="col-12 col-md-6 my-2 form-group">
                                                    <label class="form-label">{{ $feature->Name }}</label>
                                                    @if (isset($feature->Values) && count($feature->Values) > 0)
                                                        <select class="form-control censo-feature-value-list"
                                                            data-id="{{ $device->Id }}"
                                                            data-feature="{{ $feature->Id }}"
                                                            data-value="{{ isset($device->features_values[$feature->Id]) ? $device->features_values[$feature->Id] : '' }}">
                                                            <option value="">Seleccione un valor</option>
                                                            @foreach ($feature->Values as $value)
                                                                <option
                                                                    {{ isset($device->features_values[$feature->Id]) && $device->features_values[$feature->Id] == mb_strtoupper($value->Value) ? 'selected' : '' }}
                                                                    value="{{ $value->Id }}">
                                                                    {{ mb_strtoupper($value->Value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control censo-feature-value-text"
                                                            data-id="{{ $device->Id }}"
                                                            data-feature="{{ $feature->Id }}">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if (isset($device->accesories) && count($device->accesories) > 0)
                                    <div class="tab-pane fade" id="device-accesories-section-{{ $device->Id }}"
                                        role="tabpanel" aria-labelledby="device-accesories-tab-{{ $device->Id }}">
                                        <div class="m-0 py-2 px-3">
                                            @foreach ($device->accesories as $accesory)
                                                <div class="row align-items-center">
                                                    <div class="col-auto fs-7 text-uppercase fw-bold">
                                                        {{ $accesory->Nombre }}
                                                    </div>
                                                    <div class="col-auto">
                                                        <input data-component="{{ $accesory->Id }}"
                                                            data-id="{{ $device->Id }}"
                                                            data-quantity="{{ isset($device->accesories_values[$accesory->Id]) && $device->accesories_values[$accesory->Id] > 0 ? $device->accesories_values[$accesory->Id] : 0 }}"
                                                            type="number" class="form-control device-accesories"
                                                            value="{{ isset($device->accesories_values[$accesory->Id]) && $device->accesories_values[$accesory->Id] > 0 ? $device->accesories_values[$accesory->Id] : 0 }}" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex flex-row-reverse">
                                <button type="button" class="btn btn-danger censo-delete-device"
                                    data-id="{{ $device->Id }}" data-point="{{ $inputs['point'] }}">
                                    <span class="fw-bold fs-7 text-uppercase">Eliminar</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="my-5"></div>

        <div class="fixed-bottom w-100 pt-3">
            <div class="row m-0 p-0">
                <div class="col-12 offset-md-3 offset-lg-4 col-md-6 col-lg-4 p-0">
                    <button id="new-device-form-button" data-point="{{ $inputs['point'] }}"
                        class="btn btn-success btn-block py-3 my-0">
                        <span class="fw-bold fs-7 text-uppercase">Agregar Equipo</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <x-generals.modal id="branch-inventory-new-device-modal" title="Agregar Equipo al Punto" :body="$new_device_form"
        buttonAcceptId="branch-inventory-new-device-button" buttonAcceptLabel="Guardar" buttonCloseLabel="Cerrar" />
@endsection
