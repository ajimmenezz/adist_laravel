import Events from "../../shared/Events";
import Table from "../../shared/Table";

$(function () {
    const events = new Events();
    const table = new Table();
    const closeDrawerButton = document.querySelector("#generalDrawer .close-drawer-button");
    const bsOffcanvas = new bootstrap.Offcanvas('#generalDrawer');
    const offCanvas = document.getElementById('generalDrawer');
    const newDistributionForm = document.getElementById('newDistributionForm');

    loadDistributions();

    function loadDistributions() {
        events.api("/api/v3/Warehouse/Distribution", "GET", {}, {
            "Authorization": "Bearer " + api_key,
        }, function (r) {
            table.init("distributions-table", r.data.distributions, columns(), columnDefs(), function () {
                //initMoreButtons();
                table.sortByColumn('distributions-table', 3, 'desc');
            }, function () {
                //initMoreButtons();
            });
        });
    }

    function columns() {
        let columns = [
            {
                data: "Project",
                render: {
                    _: function (data, type, row) {
                        return '<a href="/Almacen/Distribucion/' + row.Id + '">' + data + '</a>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            },
            {
                data: "Customer"
            },
            {
                data: "CreatedBy",
            },
            {
                data: "Created_at",

                render: {
                    _: function (data, type, row) {
                        return moment(data).format("DD/MM/YYYY HH:mm");
                    },
                    sort: function (data, type, row) {
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

    closeDrawerButton.addEventListener('click', function (e) {
        e.preventDefault();
        bsOffcanvas.hide();
    });

    offCanvas.addEventListener('hide.bs.offcanvas', event => {
        newDistributionForm.reset();
        newDistributionForm.classList.remove('was-validated');
    });

    newDistributionForm.addEventListener('submit', function (event) {
        newDistributionForm.classList.add('was-validated');
        event.preventDefault();
        if (!newDistributionForm.checkValidity()) {
            event.stopPropagation();
        } else {
            let data = {
                "customer": document.getElementById('customer').value,
                "project": document.getElementById('project_name').value
            };

            events.api("/api/v3/Warehouse/Distribution", "POST", data, {
                "Authorization": "Bearer " + api_key,
            }, function (r) {
                table.addRow('distributions-table', r.distribution);
                bsOffcanvas.hide();
            });
        }


    });

});