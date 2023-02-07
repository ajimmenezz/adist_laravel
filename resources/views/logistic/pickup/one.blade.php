@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/logistic/pickup.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body py-5">
            <input type="hidden" id="logistic-pickup-id" value="{{ $id }}">
        </div>
        <div class="card-footer bg-transparent py-3">
            @include('logistic.pickup.sections.tabs')
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content">
                @include('logistic.pickup.sections.inventory')
                @include('logistic.pickup.sections.boxes')
            </div>
        </div>
    </div>

    <x-generals.modal id="logistic-pickup-box-selection-modal" title="Asignar número de caja" :body="$boxSelectionForm"
        buttonAcceptId="logistic-pickup-box-selection-button" buttonAcceptLabel="Asignar" buttonCloseLabel="Cerrar" />

    <x-generals.modal id="logistic-pickup-not-censo-item-modal" title="Agregar equipo fuera de Censo" :body="$notCensoItemForm"
        buttonAcceptId="save-not-censo-item-button" buttonAcceptLabel="Guardar" buttonCloseLabel="Cerrar" />
@endsection
