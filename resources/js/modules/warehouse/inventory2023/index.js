import Events from "../../shared/Events";
import Alerts from "../../shared/Alerts";
import InventoryComponents from "./components";

$(function () {
    const events = new Events();
    const alerts = new Alerts();
    const c = new InventoryComponents();
    events.loaded();

    const warehouseList = document.getElementById('warehouse-list');
    const searchSection = document.getElementById('search-section');
    const exportButton = document.getElementById('export-button');

    if (warehouseList) {
        const section = document.getElementById('warehouse-stock');
        warehouseList.addEventListener('change', function () {
            section.innerHTML = '';
            searchSection.classList.add('d-none');
            const warehouseId = this.value;
            events.loading();
            events.api("/api/v3/Warehouse/Inventory2023/" + warehouseId, "GET", {}, {
                "Authorization": "Bearer " + api_key,
            }, function (r) {
                if (r.data.length == 0) {
                    alerts.toast('No se encontraron resultados', 'error');
                    events.loaded();
                    searchSection.classList.add('d-none');
                    return;
                }
                r.data.forEach(function (item) {
                    section.innerHTML += c.columnGrid(item);
                });

                initButtons();
            });
        });
    }

    function initButtons() {
        searchSection.classList.remove('d-none');

        document.querySelectorAll('.save-validated-quantity').forEach(function (btn) {
            btn.removeEventListener('click', function () { });
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const quantity = document.getElementById('validated-quantity-' + id).value;

                if (quantity == 0) {
                    alerts.toast('La cantidad revisada no puede ser 0', 'error');
                    return;
                }

                events.loading();
                events.api("/api/v3/Warehouse/Inventory2023/" + id, "POST", {
                    "quantity": quantity
                }, {
                    "Authorization": "Bearer " + api_key,
                }, function (r) {
                    const card = document.getElementById('c-' + id);
                    card.innerHTML = c.itemCard(r.data);
                });
            });
        });

        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.value = '';
            searchInput.addEventListener('keyup', function () {
                const value = this.value.trim().toLowerCase();
                if (value == '') {
                    document.querySelectorAll('[data-search]').forEach(function (item) {
                        item.classList.remove('d-none');
                    });
                    return;
                }

                document.querySelectorAll('[data-search]').forEach(function (item) {
                    const search = item.getAttribute('data-search').toLowerCase();

                    if (search.indexOf(value) > -1) {
                        console.log(search + "" + value);
                        item.classList.remove('d-none');
                    } else {
                        item.classList.add('d-none');
                    }
                });
            });
        }

        exportButton.removeEventListener('click', function () { });
        exportButton.addEventListener('click', function () {
            const warehouseId = warehouseList.value;
            events.loading();
            window.open("/pa/Warehouse/Inventory2023/export/" + warehouseId);
            events.loaded();
        });
    }

});