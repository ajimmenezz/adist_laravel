import Events from "../../shared/Events";
import Table from "../../shared/Table";
import Alerts from "../../shared/Alerts";

$(function () {
    const events = new Events();
    const table = new Table();
    const alerts = new Alerts();
    const closeAcceptTransferDrawerButton = document.querySelector("#acceptTransferDrawer .close-drawer-button");
    const bsAcceptTransferOffcanvas = new bootstrap.Offcanvas('#acceptTransferDrawer');
    const acceptTransferOffCanvas = document.getElementById('acceptTransferDrawer');
    const acceptTransferForm = document.getElementById('acceptTransferForm');


    const acceptTransferCodeForm = document.getElementById('acceptTransferCodeForm');
    const submitTransferCodeButton = document.getElementById('submitTransferCode-btn');
    const resendTransferCodeButton = document.getElementById('resendCode-btn');

    const devicesModal = new bootstrap.Modal('#acceptDevicesModal', {
        keyboard: false
    });
    const devicesModalObject = document.getElementById('acceptDevicesModal');
    const acceptDevicesButton = document.getElementById('acceptDevices-btn');

    const devicesCodeModal = new bootstrap.Modal('#acceptDevicesCodeModal', {
        keyboard: false
    });
    const devicesCodeModalObject = document.getElementById('acceptDevicesCodeModal');

    let destinationData;

    loadDestinations();

    function loadDestinations() {
        events.api("/api/v3/Logistic/Destinations", "GET", {}, {
            "Authorization": "Bearer " + api_key,
        }, function (r) {
            table.init("destinations-table", r.data.destinations, columns(), columnDefs(), function () {
                initMoreButtons();
                table.sortByColumn('destinations-table', 3, 'desc');
            }, function () {
                //initMoreButtons();
            });
        });
    }

    function branchActions(row) {
        switch (row.StatusId) {
            case 68:
            case '68':
                return `
                <li class="my-2"><a role="button" class="table-action-accept-transfer dropdown-item fw-bold text-primary"><i class="bi bi-truck"></i> Recepcionar Equipos</a></li>
                `;
                break;
        }
    }

    function columns() {
        let columns = [
            {
                data: "DistributionId",
                render: {
                    _: function (data, type, row) {
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                `+ branchActions(row) + `
                            </ul>
                        </div>`;
                    }
                }
            },
            {
                data: "Project",
            },
            {
                data: "Customer"
            },
            {
                data: "Branch"
            },
            {
                data: "State"
            },
            {
                data: "Devices",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-6 fw-bold">' + data + '</span>';
                    }
                }
            },
            {
                data: "Status",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-5 fw-bold">' + data + '</span>';
                    }
                }
            },
            {
                data: "CurrentTransfer",
                render: {
                    _: function (data, type, row) {
                        if (data !== null) {
                            return '<span class="fs-6 fw-bold">' + data + '</span>';
                        } else {
                            return '';
                        }
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
        },
        {
            targets: [0, 5, 7],
            orderable: false,
            className: "align-middle text-center"
        }];

        return defs;
    }

    function initMoreButtons() {
        acceptTransferAction();
    }

    function acceptTransferAction() {
        document.getElementById('destinations-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('table-action-accept-transfer')) {
                let rowData = table.rowData('destinations-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    events.loading();
                    devicesModal.show();
                    destinationData = {
                        "DistributionId": rowData.DistributionId,
                        "BranchId": rowData.BranchId,
                        "StatusId": rowData.StatusId,
                        "Devices": []
                    }
                    loadTransferDevices(rowData.DistributionId, rowData.BranchId, rowData.StatusId, rowData.CurrentTransfer);
                }
            }
        });
    }

    devicesModalObject.addEventListener('hide.bs.modal', function (event) {
        table.destroy('assigned-inventory-table');
        table.destroy('accepted-inventory-table');
    });

    function loadTransferDevices(distribution, branch, status, transfer) {
        events.api("/api/v3/Logistic/Distribution/Destination/Devices", "GET", {
            DistributionId: distribution,
            BranchId: branch,
            StatusId: status,
            TransferId: transfer
        }, {
            "Authorization": "Bearer " + api_key,
        }, function (r) {
            table.init("assigned-inventory-table", r.inventory, columnsAssignedDevices(), columnDefsAssignedDevices(), function () {
                initAcceptDevicesButtons();
            });

            table.init("accepted-inventory-table", {}, columnsAcceptedDevices(), columnDefsAcceptedDevices());

        });
    }

    function columnsAssignedDevices() {
        let columns = [
            {
                data: "Line",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-9 fw-bold">' + data + '</span><br><span class="fs-7 fw-bold">' + row.Subline + '</span>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            },
            {
                data: "Model",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-9">' + row.Brand + '</span><br><span class="fs-7 fw-bold">' + data + '</span>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            },
            {
                data: "Serial",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-6 fw-bold">' + data + '</span>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            },
            {
                data: "Id",
                render: {
                    _: function (data, type, row) {
                        return '<a role="button" class="fs-5"><i class="bi bi-arrow-right-square-fill acceptDevice-btn"></i></a>';
                    }
                }
            }
        ];

        return columns;
    }

    function columnsAcceptedDevices() {
        let columns = [
            {
                data: "Id",
                render: {
                    _: function (data, type, row) {
                        return '<a role="button" class="fs-5"><i class="bi bi-arrow-left-square-fill removeDevice-btn"></i></a>';
                    }
                }
            },
            {
                data: "Line",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-7 fw-bold">' + data + '</span>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            },
            {
                data: "Model",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-9">' + row.Brand + '</span><br><span class="fs-7 fw-bold">' + data + '</span>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            },
            {
                data: "Serial",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-6 fw-bold">' + data + '</span>';
                    },
                    sort: function (data, type, row) {
                        return data;
                    }
                }
            }
        ];

        return columns;
    }

    function columnDefsAssignedDevices() {
        let defs = [{
            targets: ["_all"],
            className: "align-middle"
        },
        {
            targets: [3],
            className: "text-center"
        }
        ];

        return defs;
    }

    function columnDefsAcceptedDevices() {
        let defs = [{
            targets: ["_all"],
            className: "align-middle"
        },
        {
            targets: [0],
            className: "text-center"
        }
        ];

        return defs;
    }

    closeAcceptTransferDrawerButton.addEventListener('click', function () {
        bsAcceptTransferOffcanvas.hide();
    });

    function initAcceptDevicesButtons() {
        document.getElementById('assigned-inventory-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('acceptDevice-btn')) {
                let rowData = table.rowData('assigned-inventory-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    table.addRow('accepted-inventory-table', rowData);
                    table.removeRow('assigned-inventory-table', e.target.closest('tr'));
                }
            }
        });

        document.getElementById('accepted-inventory-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('removeDevice-btn')) {
                let rowData = table.rowData('accepted-inventory-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    table.addRow('assigned-inventory-table', rowData);
                    table.removeRow('accepted-inventory-table', e.target.closest('tr'));
                }
            }
        });

        document.getElementById('acceptAllDevices-btn').addEventListener('click', function (e) {
            let dtable = table.allData('assigned-inventory-table');
            dtable.each(function (item) {
                table.addRow('accepted-inventory-table', item);
            });
            table.clear('assigned-inventory-table');
        });

        document.getElementById('returnAllDevices-btn').addEventListener('click', function (e) {
            let dtable = table.allData('accepted-inventory-table');
            dtable.each(function (item) {
                table.addRow('assigned-inventory-table', item);
            });
            table.clear('accepted-inventory-table');
        });

    }

    acceptDevicesButton.addEventListener('click', function () {
        if (table.isEmpty('accepted-inventory-table')) {
            alerts.toast('Debe seleccionar al menos un equipo para recepcionar.', 'error');
            return;
        }

        if (table.isNotEmpty('assigned-inventory-table')) {
            alerts.confirm('Existen equipos sin recepcionar. Todos los equipos que no sean recepcionados, regresaran al almacen de origen y se tendrá que realizar la asignación nuevamente. ¿Desea continuar?', function () {
                acceptDevices();
            });
            return;
        }
        acceptDevices();
    });

    function acceptDevices() {
        let dtable = table.allData('accepted-inventory-table');
        destinationData.Devices = [];
        dtable.each(function (item) {
            destinationData.Devices.push(item.InventoryId);
        });

        devicesModal.hide();
        devicesCodeModal.show();
    }

    devicesCodeModalObject.addEventListener('hide.bs.modal', function (event) {
        acceptTransferCodeForm.reset();
        acceptTransferCodeForm.classList.remove('was-validated');
    });

    submitTransferCodeButton.addEventListener('click', function (e) {
        acceptTransferCodeForm.dispatchEvent(new Event('submit'));
    });

    acceptTransferCodeForm.addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        acceptTransferCodeForm.classList.add('was-validated');

        if (acceptTransferCodeForm.checkValidity() === true) {
            events.api("/api/v3/Logistic/Distribution/Destination/AcceptDevices", "POST", {
                DistributionId: destinationData.DistributionId,
                BranchId: destinationData.BranchId,
                Devices: destinationData.Devices,
                TransferCode: document.getElementById('transfer_code').value,
                ProcessStatus: 70
            }, {
                "Authorization": "Bearer " + api_key,
            }, function (r) {
                alerts.toast(r.message, 'success');
                devicesCodeModal.hide();
                table.destroy('destinations-table');
                table.destroy('assigned-inventory-table');
                table.destroy('accepted-inventory-table');
                loadDestinations();
            });
        }
    });

    resendTransferCodeButton.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        events.api("/api/v3/Warehouse/Distribution/TransferCode", "POST", {
            DistributionId: destinationData.DistributionId,
            BranchId: destinationData.BranchId,
            Device: destinationData.Devices[0]
        }, {
            "Authorization": "Bearer " + api_key,
        }, function (r) {
            alerts.toast(r.message, 'success');
        });
    });





});