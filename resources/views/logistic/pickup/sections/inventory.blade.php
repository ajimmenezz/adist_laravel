<div class="tab-pane fade show active" id="inventory-section" role="tabpanel" aria-labelledby="inventory-tab">
    <div class="alert alert-light-primary color-primary fw-bold">
        <i class="bi bi-info"></i>
        Aqui se muestra el inventario previamente registrado en el sistema y que aún no ha sido asignado a una caja.
    </div>
    <div class="row">
        @if (isset($unboxedItems) && count($unboxedItems) > 0)
            @foreach ($unboxedItems as $k => $items)
                <div class="line-container mt-3">
                    <header class="line-header bg-primary p-2 text-white rounded fs-7 text-uppercase fw-bold">
                        {{ $k }}</header>
                    <div class="line-content d-flex flex-wrap">
                        @foreach ($items as $item)
                            <div role="button" data-id="{{ $item->Id }}" id="item-{{ $item->Id }}"
                                class="inventory-item my-1 my-sm-2 mx-0 mx-sm-2 mx-md-3 p-3 rounded row min-content-width">
                                <div class="text-nowrap fs-9">
                                    {{ $item->Marca }}
                                </div>
                                <div class="text-nowrap fs-9">
                                    {{ $item->Modelo }}
                                </div>
                                <div class="fw-bold text-nowrap">
                                    {{ $item->Serie }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="inventory-action-buttons d-none">
        <div class="d-flex flex-column">
            <div class="mr-2 my-2">
                <button id="show-only-selected-button" class="btn btn-secondary fs-3"><i
                        class="bi bi-bookmark-check"></i></button>
            </div>
            <div class="mr-2 my-2">
                <button id="assign-box-button" class="btn btn-warning fs-3"><i class="bi bi-box"></i></button>
            </div>
        </div>
    </div>
</div>
