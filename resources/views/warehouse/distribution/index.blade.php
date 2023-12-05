@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/warehouse/distribution/index.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse">
                <div>
                    <button id="new-project-distribution-button" class="btn btn-success text-uppercase fs-7 fw-bold"
                        data-bs-toggle="offcanvas" data-bs-target="#generalDrawer" aria-controls="generalDrawer"
                        aria-label="Toggle Drawer">
                        <i class="bi bi-plus"></i>
                        {{ __('Nuevo Proyecto') }}
                    </button>
                </div>
            </div>
            <div class="row mt-4"></div>
            <div class="row">
                <div class="col table-responsive">
                    <x-generals.table id="distributions-table" :headers="$table_headers" />
                </div>
            </div>
        </div>
    </div>
    <x-generals.drawer id="generalDrawer" title="Nuevo Proyecto" :content="$forms['new_distribution']" />
@endsection
