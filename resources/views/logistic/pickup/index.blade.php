@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/logistic/pickups.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse">
                <div>
                    <button id="logistic-new-pickup-form-button" class="btn btn-success text-uppercase fs-7 fw-bold">
                        <i class="bi bi-plus"></i>
                        {{ __('Nuevo') }}
                    </button>
                </div>
            </div>
            <div class="row mt-4"></div>
            <div class="row">
                <div class="col table-responsive">
                    <x-generals.table id="pickups-table" :headers="$table_headers" />
                </div>
            </div>
        </div>
    </div>
    <x-generals.modal id="logistic-new-pickup-modal" title="Nueva Recolección" :body="$pickupForm" buttonAcceptId="logistic-new-pickup-button"
        buttonAcceptLabel="Crear" buttonCloseLabel="Cerrar" />
@endsection
