@extends('layouts.app')

@section('module')
    <script>
        const g_customer_id = "{{ $distribution->CustomerId }}";
        const g_distribution_id = "{{ $distribution->Id }}";
    </script>
    @vite(['resources/js/modules/warehouse/distribution/one.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse">
                <div>
                    <button id="new-project-distribution-button" class="btn btn-success text-uppercase fs-7 fw-bold"
                        data-bs-toggle="offcanvas" data-bs-target="#destinationDrawer" aria-controls="destinationDrawer"
                        aria-label="Toggle Drawer">
                        <i class="bi bi-plus"></i>
                        {{ __('Agregar destino') }}
                    </button>
                </div>
            </div>
            <div class="row my-4">
                <div class="col">
                    <ul class="nav nav-tabs siccob-nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="branch_view_tab" data-bs-toggle="tab"
                                data-bs-target="#branch_view" type="button" role="tab" aria-controls="home-tab-pane"
                                aria-selected="true">Vista Sucursales</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="details_view_tab" data-bs-toggle="tab"
                                data-bs-target="#details_view" type="button" role="tab" aria-controls="details_view"
                                aria-selected="false">Vista de Detalles</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="branch_view">
                    <div class="row">
                        <div class="col table-responsive">
                            <x-generals.table id="destinations-table" :headers="$table_headers_branch_view" />
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="details_view">
                    <div class="row">
                        <div class="col table-responsive">
                            <x-generals.table id="details-table" :headers="$table_headers_device_view" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('warehouse.distribution.destination_devices_modal')
    <x-generals.drawer id="destinationDrawer" title="Nuevo Destino" :content="$forms['new_destination']" />
    <x-generals.drawer id="assignToSupportDrawer" title="Asignar equipos a Soporte" :content="$forms['assign_to_support']" />
@endsection
