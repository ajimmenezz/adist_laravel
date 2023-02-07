import Events from "../shared/Events";
import Table from "../shared/Table";
import Buttons from "../shared/Buttons";
import Modals from "../shared/Modals";
import Validate from "../shared/Validates";
import Alerts from "../shared/Alerts";
import TagStatus from "../components/tags/Status";

$(function() {
    const events = new Events();
    const table = new Table();
    const buttons = new Buttons();
    const modal = new Modals("logistic-new-pickup-modal");
    const validate = new Validate();
    const alerts = new Alerts();
    const tagStatus = new TagStatus();

    loadList();

    function loadList() {
        events.post("/api/v1/Logistic/Pickup", {
            api_key: api_key,
            _method: "GET"
        }, function(r) {
            if (r.code == 200) {
                table.init("pickups-table", r.data.Pickups, columns(), columnDefs(), function() {
                    //initMoreButtons();
                    table.sortByColumn('prices-lists-table', 1, 'asc');
                }, function() {
                    //initMoreButtons();
                });
            }
        });
    }

    function columns() {
        let columns = [{
                data: "Id",
                render: function(data, type, row) {
                    return `
                    <a class="fs-5 fw-bold" href="/Logistica/Recoleccion/` + data + `">
                        <i class="bi bi-eye"></i>
                    </a>`;
                },
                className: "text-center"
            },
            {
                data: "Id"
            },
            {
                data: "BranchName"
            },
            {
                data: "UserName",
            },
            {
                data: "StatusName",
                render: function(data, type, row) {
                    return tagStatus.render(row.StatusId);
                },
                className: "text-center"
            },
            {
                data: "created_at",
                render: function(data, type, row) {
                    return moment(data).format("DD/MM/YYYY HH:mm");
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

    buttons.initClic("#logistic-new-pickup-form-button", function(btn) {
        $("#pickup-branch option:first").prop("selected", true);
        modal.show();
        initNewPickupForm();
    });

    function initNewPickupForm() {
        validate.form_reset("#new-pickup-form");
        buttons.initClic("#logistic-new-pickup-button", function(btn) {
            if (validate.form("#new-pickup-form")) {
                events.post("/api/v1/Logistic/Pickup", {
                        api_key: api_key,
                        _method: "PUT",
                        branch: $("#pickup-branch").val(),
                    },
                    function(r) {
                        if (r.code == 200) {
                            alerts.success(r.message, 4000, function() {
                                modal.hide();
                                window.location.href = "/Logistica/Recoleccion/" + r.data.Pickup.Id;
                            });
                        } else {
                            alerts.error(r.message);
                        }
                    },
                    function() {
                        alerts.error('No se pudo crear el registro de recolección. Recargue la página e intente nuevamente.', 4000, function() {
                            modal.hide();
                        });
                    });
            }
        });
    }

});