@extends('layouts.app')

@section('module')
    @vite(['resources/js/modules/support/branch_inventories/one.js'])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-row-reverse">
                <div>
                    <a target="_blank" class="btn btn-success"
                        href="{{ route('support.branch_inventory.export', ['id' => $service->Id]) }}">
                        <i class="bi bi-file-earmark-excel-fill"></i>
                        <span>Exportar a Excel</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-4"></div>
            <div class="row">
                <div class="col-12">
                    <div class="line-content d-flex flex-wrap">
                        @foreach ($attention_areas as $area)
                            <div class="col-12 col-md-8 col-lg-6">
                                <div role="button" data-serviceid="{{ $service->Id }}" data-areaid="{{ $area->Id }}"
                                    class="support-censo-item-area my-1 my-sm-2 mx-0 mx-sm-2 mx-md-3 px-4 py-4 rounded d-flex flex-column">
                                    <div class="fw-bold text-nowrap text-uppercase text-center fs-5">
                                        {{ $area->Nombre }}
                                    </div>
                                    <div class="d-flex w-100 mt-4">
                                        <div class="d-flex flex-column">
                                            <div class="text-center fs-3 fw-bold">{{ $points_by_area[$area->Id] ?? 0 }}
                                            </div>
                                            <div class="text-center text-uppercase fs-8">Puntos</div>
                                        </div>
                                        <div class="flex-grow-1" style="min-width:30px"></div>
                                        <div class="d-flex flex-column">
                                            <div class="text-center fs-3 fw-bold">{{ $items_by_area[$area->Id] ?? 0 }}</div>
                                            <div class="text-center text-uppercase fs-8">Equipos</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
