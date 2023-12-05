@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/logistic/distribution/index.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mt-4">
                <div class="col table-responsive">
                    <x-generals.table id="destinations-table" :headers="$table_headers_destination_view" />
                </div>
            </div>
        </div>
    </div>
    @include('warehouse.distribution.accept_devices_modal')
    <x-generals.modal id="acceptDevicesCodeModal" title="Ingresar Código" :body="$forms['accept_code']" buttonAcceptId="submitTransferCode-btn" buttonAcceptLabel="Validar Código" />
    <x-generals.drawer id="acceptTransferDrawer" title="Aceptar Transferencia" :content="$forms['accept_transfer']" />
@endsection
