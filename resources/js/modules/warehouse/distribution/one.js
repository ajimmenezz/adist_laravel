import Events from "../../shared/Events";
import Table from "../../shared/Table";
import Alerts from "../../shared/Alerts";

$(function () {
    const events = new Events();
    const table = new Table();
    const alerts = new Alerts();

    const bsDestinationDrawer = new bootstrap.Offcanvas('#destinationDrawer');
    const destinationDrawer = document.getElementById('destinationDrawer');
    const newDestinationForm = document.getElementById('newDestinationForm');
    const closeDestinationDrawerButton = document.querySelector("#destinationDrawer .close-drawer-button");

    const bsAssignToSupportDrawer = new bootstrap.Offcanvas('#assignToSupportDrawer');
    const assignToSupportDrawer = document.getElementById('assignToSupportDrawer');
    const assignToSupportForm = document.getElementById('assignToSupportForm');
    const closeAssignToSupportDrawerButton = document.querySelector("#assignToSupportDrawer .close-drawer-button");


    const devicesModal = new bootstrap.Modal('#destinationDevicesModal', {
        keyboard: false
    });
    const devicesModalObject = document.getElementById('destinationDevicesModal');
    const assignDevicesButton = document.getElementById('assignDevices-btn');

    let destinationData;

    events.loaded();

    loadDevices();

    function loadDevices() {
        events.api("/api/v3/Warehouse/Distribution/" + g_distribution_id + "/Devices", "GET", {}, {
            "Authorization": "Bearer " + api_key,
        }, function (r) {
            table.init("destinations-table", r.branches, columnsBranchesView(), columnBranchesViewDefs(), function () {
                table.sortByColumn('destinations-table', 0, 'desc');
                initDistributionButtons();
            });
            table.init("details-table", r.devices, columnsDevicesView(), columnDevicesViewDefs());
        });
    }

    function branchActions(row) {
        switch (row.StatusId) {
            case 66:
            case '66':
                return `
                <li class="my-2"><a role="button" class="table-action-logistic dropdown-item fw-bold text-primary"><i class="bi bi-truck"></i> Entregar a Logística</a></li>
                <li class="my-2"><a role="button" class="table-action-support dropdown-item fw-bold text-secondary"><i class="bi bi-person-badge"></i> Entregar a Soporte</a></li>
                <li class="my-2"><a role="button" class="table-action-cancel dropdown-item fw-bold text-danger"><i class="bi bi-x"></i> Cancelar</a></li>`;
                break;

            case 68:
            case '68':
                return `
                <li class="my-2"><a role="button" class="table-action-cancel-logistic dropdown-item fw-bold text-danger"><i class="bi bi-x"></i> Cancelar entrega</a></li>
                `;
                break;
            case 69:
            case '69':
                return `
                <li class="my-2"><a role="button" class="table-action-cancel-warehouse-to-support dropdown-item fw-bold text-danger"><i class="bi bi-x"></i> Cancelar entrega</a></li>
                `;
                break;
        }
    }

    function columnsBranchesView() {
        let columns = [
            {
                data: "Id",
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
                data: "Branch",
            },
            {
                data: "State",
            },
            {
                data: "Devices",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-5 fw-bold">' + data + '</span>';
                    }
                }
            },
            {
                data: "Status",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-5 fw-bold">' + data + '</span><br><span class="fs-7">' + row.ResponsibleName + '</span>';
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

    function columnBranchesViewDefs() {
        let defs = [{
            targets: ["_all"],
            className: "align-middle"
        }, {
            targets: [0,3, 5],
            className: "text-center"
        }];

        return defs;
    }

    function columnsDevicesView() {
        let columns = [
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
            },
            {
                data: "Branch",
            },
            {
                data: "Area",
            },
            {
                data: "State",
            },
            {
                data: "Status",
                render: {
                    _: function (data, type, row) {
                        return '<span class="fs-6 fw-bold">' + data + '</span><br><span class="fs-7">' + row.ResponsibleName + '</span>';
                    }
                }
            }
        ];

        return columns;
    }

    function columnDevicesViewDefs() {
        let defs = [{
            targets: ["_all"],
            className: "align-middle"
        }];

        return defs;
    }

    closeDestinationDrawerButton.addEventListener('click', function (e) {
        e.preventDefault();
        bsDestinationDrawer.hide();
    });

    destinationDrawer.addEventListener('hide.bs.offcanvas', event => {
        newDestinationForm.reset();
        newDestinationForm.classList.remove('was-validated');
        if (destinationData !== undefined && destinationData.devices !== undefined)
            destinationData.devices = [];
    });

    closeAssignToSupportDrawerButton.addEventListener('click', function (e) {
        e.preventDefault();
        bsAssignToSupportDrawer.hide();
    });

    assignToSupportDrawer.addEventListener('hide.bs.offcanvas', event => {
        assignToSupportForm.reset();
        assignToSupportForm.classList.remove('was-validated');
    });

    assignToSupportForm.addEventListener('submit', function (event) {
        assignToSupportForm.classList.add('was-validated');
        event.preventDefault();
        if (!assignToSupportForm.checkValidity()) {
            event.stopPropagation();
        } else {
            const data = {
                branchId: assignToSupportForm.getAttribute('data-branch'),
                technician: document.getElementById('technicians').value,
                from: "WAREHOUSE"
            };
            events.loading();
            events.api("/api/v3/Warehouse/Destination/ToSupport/" + g_distribution_id, "POST", data, {
                "Authorization": "Bearer " + api_key,
            }, function (r) {
                bsAssignToSupportDrawer.hide();
                events.loaded();
                alerts.toast(r.message, 'success');
                table.destroy('destinations-table');
                table.destroy('details-table');
                loadDevices();
            });
        }
    });

    newDestinationForm.addEventListener('submit', function (event) {
        newDestinationForm.classList.add('was-validated');
        event.preventDefault();
        if (!newDestinationForm.checkValidity()) {
            event.stopPropagation();
        } else {
            destinationData = {
                "branch": document.getElementById('branch').value,
                "area": document.getElementById('attentionArea').value,
                "devices": []
            };
            events.loading();
            devicesModal.show();
            loadAvailableInventory();

        }
    });

    devicesModalObject.addEventListener('hide.bs.modal', function (event) {
        table.destroy('inventory-table');
        table.destroy('assigned-inventory-table');
        destinationData.devices = [];
    });

    function loadAvailableInventory() {
        events.api("/api/v3/Warehouse/Distribution/AvailableInventory/" + g_customer_id, "GET", {}, {
            "Authorization": "Bearer " + api_key,
        }, function (r) {
            table.init("inventory-table", r.inventory, columnsAvailableInventory(), columnDefsAvailableInventory(), function () {
                initAssignButtons();
            });
            table.init("assigned-inventory-table", {}, columnsAssignedDevices(), columnDefsAssignedDevices());
        });
    }

    function columnsAvailableInventory() {
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
                        return '<a role="button" class="fs-5"><i class="bi bi-arrow-right-square-fill assignDevice-btn"></i></a>';
                    }
                }
            }
        ];

        return columns;
    }

    function columnsAssignedDevices() {
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

    function columnDefsAvailableInventory() {
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

    function columnDefsAssignedDevices() {
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

    function initAssignButtons() {
        document.getElementById('inventory-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('assignDevice-btn')) {
                let rowData = table.rowData('inventory-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    destinationData.devices.push(rowData);
                    table.addRow('assigned-inventory-table', rowData);
                    table.removeRow('inventory-table', e.target.closest('tr'));
                }
            }
        });

        document.getElementById('assigned-inventory-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('removeDevice-btn')) {
                let rowData = table.rowData('assigned-inventory-table', e.target.closest('tr'));
                destinationData.devices = destinationData.devices.filter(function (item) {
                    return item.Id !== rowData.Id;
                });
                table.addRow('inventory-table', rowData);
                table.removeRow('assigned-inventory-table', e.target.closest('tr'));
            }
        });

    }

    assignDevicesButton.addEventListener('click', function (e) {
        e.preventDefault();
        if (destinationData.devices.length > 0) {
            const data = {
                branch: document.getElementById('branch').value,
                area: document.getElementById('attentionArea').value,
                devices: destinationData.devices.map(function (item) {
                    return item.Id;
                })
            };
            events.loading();
            events.api("/api/v3/Warehouse/Distribution/" + g_distribution_id + "/Devices", "PUT", data, {
                "Authorization": "Bearer " + api_key,
            }, function (r) {
                devicesModal.hide();
                bsDestinationDrawer.hide();
                events.loaded();
                alerts.toast('Dispositivos asignados correctamente', 'success');
                table.destroy('destinations-table');
                table.destroy('details-table');
                loadDevices();
            });
        } else {
            alerts.toast('Debe seleccionar al menos un dispositivo', 'error');
        }
    });

    function initDistributionButtons() {
        cancelDestinationAction();
        deliverToLogisticAction();
        cancelDeliveryToLogisticAction();
        deliverToSupportAction();
        cancelDeliveryWarehouseToSupportAction();
    }

    function cancelDestinationAction() {
        document.getElementById('destinations-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('table-action-cancel')) {
                let rowData = table.rowData('destinations-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    alerts.confirm('¿Está seguro de cancelar la entrega?', function () {
                        events.api("/api/v3/Warehouse/Distribution/" + g_distribution_id, "DELETE", {
                            branchId: rowData.BranchId,
                            statusId: rowData.StatusId
                        }, {
                            "Authorization": "Bearer " + api_key,
                        }, function (r) {
                            table.destroy('destinations-table');
                            table.destroy('details-table');
                            loadDevices();
                            alerts.toast(r.message, 'success');
                        });
                    });
                }
            }
        });
    }

    function deliverToLogisticAction() {
        document.getElementById('destinations-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('table-action-logistic')) {
                let rowData = table.rowData('destinations-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    alerts.confirm('Se va a generar un traspaso al área de logística ¿Desea continuar?', function () {
                        events.api("/api/v3/Warehouse/Destination/ToLogistic/" + g_distribution_id, "POST", {
                            from: "WAREHOUSE",
                            branchId: rowData.BranchId,
                            statusId: rowData.StatusId
                        }, {
                            "Authorization": "Bearer " + api_key,
                        }, function (r) {
                            table.destroy('destinations-table');
                            table.destroy('details-table');
                            loadDevices();
                            alerts.toast(r.message, 'success');
                        });
                    });
                }
            }
        });
    }

    function cancelDeliveryToLogisticAction() {
        document.getElementById('destinations-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('table-action-cancel-logistic')) {
                let rowData = table.rowData('destinations-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    alerts.confirm('Cancelaremos la entrega a logística. ¿Desea continuar?', function () {
                        events.api("/api/v3/Warehouse/Destination/ToLogistic/" + g_distribution_id, "DELETE", {
                            branchId: rowData.BranchId,
                            transfer: rowData.CurrentTransfer,
                        }, {
                            "Authorization": "Bearer " + api_key,
                        }, function (r) {
                            table.destroy('destinations-table');
                            table.destroy('details-table');
                            loadDevices();
                            alerts.toast(r.message, 'success');
                        });
                    });
                }
            }
        });
    }

    function deliverToSupportAction() {
        document.getElementById('destinations-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('table-action-support')) {
                let rowData = table.rowData('destinations-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    bsAssignToSupportDrawer.show();
                    assignToSupportForm.setAttribute('data-branch', rowData.BranchId);
                }
            }
        });
    }

    function cancelDeliveryWarehouseToSupportAction() {
        document.getElementById('destinations-table').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('table-action-cancel-warehouse-to-support')) {
                let rowData = table.rowData('destinations-table', e.target.closest('tr'));
                if (rowData !== undefined) {
                    alerts.confirm('Cancelaremos la entrega al personal de soporte. ¿Desea continuar?', function () {
                        events.api("/api/v3/Warehouse/Destination/ToSupport/" + g_distribution_id, "DELETE", {
                            from: "WAREHOUSE",
                            branchId: rowData.BranchId,
                            warehouseId: rowData.WarehouseId,
                            statusId: rowData.StatusId,
                            transfer: rowData.CurrentTransfer,
                        }, {
                            "Authorization": "Bearer " + api_key,
                        }, function (r) {
                            table.destroy('destinations-table');
                            table.destroy('details-table');
                            loadDevices();
                            alerts.toast(r.message, 'success');
                        });
                    });
                }
            }
        });
    }

});