import String from "../../shared/String";

class InventoryComponents {
    constructor() {
        this.string = new String();
    }

    columnGrid(item) {
        const searchText = item.ItemKey + ' ' + item.ItemLine + ' ' + item.Item;
        return '<div class="col-lg-4 col-md-3 col-sm-6 col-12" data-search="' + searchText + '" id="c-' + item.Id + '">' + this.itemCard(item) + '</div>';
    }

    itemCard(item) {
        return `
                <div class="card bg-`+ (item.LastUpdateUser == 1 ? 'light' : 'success bg-opacity-10') + `">
                    <div class="card-body px-2 py-4">
                        <div class="d-flex px-3">
                            <div class="fw-bold fs-4 flex-fill">`+ item.ItemKey + `</div>
                            <div class="text-muted text-end">`+ item.ItemLine + `</div>
                        </div>
                        <div class="row my-4 align-items-center">
                            <div class="col text-center">
                                <h1 class="my-0 lh-1">`+ item.Quantity + `</h1>
                                <span class="fs-7 my-0 lh-1">`+ item.Measure + `</span>
                            </div>
                            <div class="col">
                                <div class="input-group mb-3">
                                    <input type="number" id="validated-quantity-`+ item.Id + `" class="form-control fs-1 form-control-lg text-center d-flex justify-content-center align-items-center w-60" placeholder="0" value="` + item.ValidatedQuantity + `" aria-describedby="button-addon-` + item.Id + `">
                                    <button class="btn btn-outline-success save-validated-quantity" data-id="`+ item.Id + `" type="button" id="button-addon-` + item.Id + `"><i class="bi bi-check fs-4"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="fs-3 ps-3 text-`+ (item.LastUpdateUser == 1 ? 'danger' : 'success') + `">` + (item.LastUpdateUser == 1 ? '<i class="bi bi-clock"></i>' : '<i class="bi bi-check"></i>') + `</div>
                            <div class="flex-fill fw-bold text-end fs-5">`+ item.Item + `</div>
                        </div>
                    </div>
                </div>`;
    }
}

export default InventoryComponents;