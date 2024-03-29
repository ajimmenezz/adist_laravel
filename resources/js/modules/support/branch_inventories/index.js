import Events from "../../shared/Events";
import Table from "../../shared/Table";
import Buttons from "../../shared/Buttons";
import Modals from "../../shared/Modals";
import Alerts from "../../shared/Alerts";
import TagStatus from "../../components/tags/Status";

$(function () {
    const events = new Events();
    const table = new Table();
    const buttons = new Buttons();
    const alerts = new Alerts();
    const tagStatus = new TagStatus();

    loadList();

    function loadList() {
        events.post("/api/v1/Support/Branch-Inventory", {
            api_key: api_key,
            _method: "GET"
        }, function (r) {
            if (r.code == 200) {
                table.init("branch-inventories-table", r.data.BranchInventories, columns(), columnDefs(), function () {
                    //initMoreButtons();
                    table.sortByColumn('branch-inventories-table', 5, 'desc');
                }, function () {
                    //initMoreButtons();
                });
            }
        });
    }

    function columns() {
        let columns = [{
            data: "Id",
            render: function (data, type, row) {
                return `
                    <div class="dropdown">
                        <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="Censos/${data}" class="fw-bold text-uppercase fs-7 dropdown-item" type="button"><i class="fs-5 bi bi-pencil-square me-3"></i> Capturar datos</a></li>
                            <li><button data-serviceid="${data}" class="fw-bold text-uppercase fs-7 dropdown-item" type="button"><i class="fs-5 bi bi-person-check-fill me-3"></i> Asignar</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button data-serviceid="${data}" class="fw-bold text-uppercase fs-7 dropdown-item" type="button"><i class="fs-5 bi bi-trash3-fill me-3"></i>Cancelar</button></li>
                        </ul>
                    </div>
                    `;
            },
            className: "text-center"
        },
        {
            data: "Id"
        },
        {
            data: "Branch"
        },
        {
            data: "Attendant",
        },
        {
            data: "StatusId",
            render: function (data, type, row) {
                return tagStatus.render(row.StatusId);
            },
            className: "text-center"
        },
        {
            data: "Created_at",

            render: {
                _: function(data, type, row) {
                    return moment(data).format("DD/MM/YYYY HH:mm");
                },
                sort: function(data, type, row) {
                    return moment(data).format("YYYYMMDDHHmm");
                }
            }
        }
        ];

        return columns;
    }

    function columnDefs() {
        let defs = [{
            targets: ["_all"],
            className: "align-middle"
        }];

        return defs;
    }

    buttons.initClic("#logistic-new-pickup-form-button", function (btn) {
        $("#pickup-branch option:first").prop("selected", true);
        modal.show();
        initNewPickupForm();
    });

    function initNewPickupForm() {
        validate.form_reset("#new-pickup-form");
        buttons.initClic("#logistic-new-pickup-button", function (btn) {
            if (validate.form("#new-pickup-form")) {
                events.post("/api/v1/Logistic/Pickup", {
                    api_key: api_key,
                    _method: "PUT",
                    branch: $("#pickup-branch").val(),
                },
                    function (r) {
                        if (r.code == 200) {
                            alerts.success(r.message, 4000, function () {
                                modal.hide();
                                window.location.href = "/Logistica/Recoleccion/" + r.data.Pickup.Id;
                            });
                        } else {
                            alerts.error(r.message);
                        }
                    },
                    function () {
                        alerts.error('No se pudo crear el registro de recolección. Recargue la página e intente nuevamente.', 4000, function () {
                            modal.hide();
                        });
                    });
            }
        });
    }

});