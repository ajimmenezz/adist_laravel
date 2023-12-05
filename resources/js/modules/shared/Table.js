class Tables {
    init(id, data = {}, columns = [], defs = [], callback = function () { }, onDraw = function () { }) {
        $("#" + id).DataTable({
            data: data,
            columns: columns,
            columnDefs: defs,
            responsive: {
                details: {
                    type: "column",
                    target: "tr"
                },
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            initComplete: function () {
                callback();
            },
            drawCallback: function () {
                onDraw();
            }
        });
    }

    simpleInit(id) {
        $("#" + id).DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            }
        });
    }

    addRow(id, data = {}) {
        $("#" + id).DataTable().row.add(data).draw();
    }

    destroy(id) {
        $("#" + id).DataTable().clear().destroy();
    }

    object(id) {
        return $("#" + id).DataTable();
    }

    removeRow(id, row) {
        $("#" + id).DataTable().row(row).remove().draw();
    }

    sortByColumn(id, column, order = "asc") {
        $("#" + id).DataTable().order([column, order]).draw();
    }

    rowData(id, row) {
        return $("#" + id).DataTable().row(row).data();
    }

    clear(id) {
        $("#" + id).DataTable().clear().draw();
    }

    allData(id) {
        return $("#" + id).DataTable().rows().data();
    }

    isEmpty(id) {
        return $("#" + id).DataTable().data().count() === 0;
    }

    isNotEmpty(id) {
        return !this.isEmpty(id);
    }
}

export default Tables;