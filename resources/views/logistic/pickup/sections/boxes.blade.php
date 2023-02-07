<div class="tab-pane fade" id="boxes-section" role="tabpanel" aria-labelledby="boxes-tab">
    <div class="table-responsive py-4">
        <div class="d-flex">
            @for ($i = 1; $i <= 100; $i++)
                <div role="button" data-box="{{ $i }}" id="box-selection-{{ $i }}"
                    class="box-selection px-4 py-2 text-center mx-2 border rounded lh-sm">
                    <i
                        class="fs-1 text-brown bi bi-box2{{ array_key_exists($i, $boxedItems) || (isset($extraItems[$i]) && count($extraItems[$i]['c']) > 0) || (isset($extraItems[$i]) && count($extraItems[$i]['d']) > 0) ? '-fill' : '' }}"></i>
                    <span class="fs-8 text-nowrap">{{ __('Caja ') . $i }}</span>
                </div>
            @endfor
        </div>
    </div>
    <div class="row mt-3 p-0 mx-0">
        <div class="col-12 p-0 mx-0">
            @for ($i = 1; $i <= 100; $i++)
                <div id="box-content-{{ $i }}" data-box="{{ $i }}"
                    class="my-4 py-3 fs-8 text-uppercase d-none box-content">
                    <div class="d-flex flex-row-reverse">
                        <div>
                            <button class="btn-pdf btn btn-danger" data-box="{{$i}}">
                                <i class="bi bi-filetype-pdf"></i> Exportar Caja
                            </button>
                        </div>
                    </div>

                    <div class="row align-items-start my-4">
                        <div class="col-12 col-sm-6 fs-2">{{ __('Caja ') . $i }} - <span class="fs-6">Solo
                                censo</span></div>
                    </div>
                    <div class="box-content-items">
                        @isset($boxedItems[$i])
                            @foreach ($boxedItems[$i] as $item)
                                <div class="row box-content-item">
                                    <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                                        {{ $item->Linea }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                                        {{ $item->Sublinea }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        {{ $item->Marca }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        {{ $item->Modelo }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bold fs-7">
                                        {{ $item->Serie }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        <button data-censoid="{{ $item->Id }}" data-box="{{ $i }}"
                                            class="remove-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                                            <i class="bi bi-arrow-bar-up"></i>
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-1">
                                    </div>
                                </div>
                            @endforeach
                        @endisset
                    </div>

                    <div class="row align-items-start mt-5 pt-3 mb-3">
                        <div class="col-12 col-sm-6 fs-2">{{ __('Caja ') . $i }} - <span class="fs-6">Extras</span>
                        </div>
                        <div class="col-12 col-sm-6 fs-2">
                            <button data-box="{{ $i }}"
                                class="btn btn-success float-end not-censo-item-form-button">
                                <i class="bi bi-plus"></i>Agregar Items
                            </button>
                        </div>
                    </div>
                    <div id="box-extra-items-{{ $i }}" class="m-0 p-0">
                        @if (isset($extraItems[$i]) && count($extraItems[$i]['d']) > 0)
                            @foreach ($extraItems[$i]['d'] as $item)
                                <div class="row box-content-extra-item">
                                    <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                                        {{ $item->Linea }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                                        {{ $item->Sublinea }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        {{ $item->Marca }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        {{ $item->Modelo }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bold fs-7">
                                        {{ $item->SerialNumber }}
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        <button data-id="{{ $item->Id }}" data-box="{{ $i }}"
                                            class="remove-extra-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                                            <i class="bi bi-arrow-bar-up"></i>
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-1">
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if (isset($extraItems[$i]) && count($extraItems[$i]['c']) > 0)
                            @foreach ($extraItems[$i]['c'] as $item)
                                <div class="row box-content-extra-component">
                                    <div class="col-12 col-sm-8 col-md-10 mb-0">
                                        <span class="fw-bold fs-6">{{ $item->Quantity }}</span>
                                        <span class="fw-bold">{{ $item->Componente }}</span>
                                        <span class="text-lowercase">de</span>
                                        <span class="fw-bold">{{ $item->Modelo }}</span> ({{ $item->Marca }})
                                    </div>
                                    <div class="col-12 col-sm-4 col-md-2 mb-0">
                                        <button data-id="{{ $item->Id }}" data-box="{{ $i }}"
                                            class="remove-extra-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                                            <i class="bi bi-arrow-bar-up"></i>
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-1">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
