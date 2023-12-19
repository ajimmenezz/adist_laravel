@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/warehouse/inventory2023/index.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mt-4"></div>
            <div class="row">
                <div class="col">
                    <select class="form-select" aria-label="Almacén Virtual" id="warehouse-list">
                        <option selected>Selecciona Almacén</option>
                        @if (isset($warehouses))
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->CVE_ALM }}">{{ $warehouse->DESCR }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex w-100 justify-content-end d-none" id="search-section">
                <div>
                    <input type="text" class="form-control form-control-lg fs-5" placeholder="Buscar" aria-label="Buscar"
                        id="search">
                </div>
                <div class="ms-4">
                    <button class="btn btn-success fs-4" id="export-button"><i class="bi bi-cloud-download"></i></button>
                </div>
            </div>
            <div class="row mt-4" id="warehouse-stock">

            </div>
        </div>
    </div>
@endsection
