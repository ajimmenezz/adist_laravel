import Events from "../../shared/Events";
import Buttons from "../../shared/Buttons";
import Modal from "../../shared/Modals";
import Validate from "../../shared/Validates";
import Swal from "sweetalert2";

$(function () {
    const events = new Events();
    const buttons = new Buttons();
    const modal = new Modal('branch-inventory-new-device-modal');
    const validate = new Validate();

    let models = [];

    $("#new-device-model option").each(function () {
        models.push({
            id: $(this).val(),
            text: $(this).text(),
            subline: $(this).data("subline"),
        });
    });


    let get_params = new URLSearchParams(window.location.search);
    goToDevice(get_params.get("device"));

    initDeviceSelects();

    buttons.initClic(".point-button", function (btn) {
        const point = btn.data("point");
        goToPoint(point);
    });

    buttons.initClic("#add-point-button", function (btn) {
        const service = btn.data("service");
        const area = btn.data("area");
        events.post("/api/v1/Support/Branch-Inventory/" + service + "/" + area, {
            api_key: api_key,
            _method: "PUT",
        }, function (r) {
            if (r.code == 200) {
                goToPoint(r.data.point);
            } else {
                Swal.fire({
                    text: r.message,
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            }
        }, function () {
            Swal.fire({
                text: "Ha ocurrido un error al intentar crear el punto. Recargue la página e intente nuevamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        });
    });

    $(".censo-model-list").on("change", function () {
        const select = $(this);
        const data = {
            service: select.data("service"),
            area: select.data("area"),
            point: select.data("point"),
            old_model: select.data("model"),
            id: select.data("id"),
            model: select.val(),
        };

        if (data.model == "") {
            Swal.fire({
                text: "Debe seleccionar un modelo.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            select.val(data.old_model).trigger("change");
            return;
        }

        if (data.model != data.old_model) {
            Swal.fire({
                title: "¿Continuar?",
                text: "Al cambiar el modelo se eliminarán todas las características y accesorios del equipo",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Actualizar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    events.post("/api/v1/Support/Branch-Inventory/UpdateModel/" + data.id, {
                        api_key: api_key,
                        _method: "POST",
                        model: data.model,
                    }, function (r) {
                        if (r.code == 200) {
                            events.loading();
                            goToPoint(data.point, data.id);
                        } else {
                            Swal.fire({
                                text: r.message,
                                icon: "error",
                                confirmButtonText: "Aceptar"
                            });
                            select.val(data.old_model).trigger("change");
                        }
                    }, function () {
                        Swal.fire({
                            text: "Ha ocurrido un error al intentar actualizar el modelo del equipo. Recargue la página e intente nuevamente.",
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                        select.val(data.old_model).trigger("change");
                    });
                } else {
                    select.val(data.old_model).trigger("change");
                }
            });
        }
    });

    $(".censo-serial").on("blur", function () {
        const input = $(this);
        const data = {
            point: input.data("point"),
            id: input.data("id"),
            old_serial: input.data("serial").toString().trim(),
            serial: input.val().toString().trim(),
        };

        if (data.serial == "") {
            Swal.fire({
                title: "¿Guardar?",
                text: "Si no se captura el número de serie, se guardará como 'ILEGIBLE'",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    updateSerial(input, data);
                } else {
                    input.val(data.old_serial);
                }
            });
        } else if (data.serial != data.old_serial) {
            updateSerial(input, data);
        }
    });

    $(".censo-status").on("change", function () {
        const select = $(this);
        const data = {
            point: select.data("point"),
            id: select.data("id"),
            old_status: select.data("status"),
            status: select.val(),
        };

        events.post("/api/v1/Support/Branch-Inventory/UpdateStatus/" + data.id, {
            api_key: api_key,
            _method: "POST",
            status: data.status,
        }, function (r) {
            if (r.code != 200) {
                Swal.fire({
                    text: r.message,
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
                select.val(data.old_status).trigger("change");
            } else {
                if (data.status == 17) {
                    select.closest(".device-card").removeClass("border-danger");
                } else {
                    select.closest(".device-card").addClass("border-danger");
                }
            }
        }, function () {
            Swal.fire({
                text: "Ha ocurrido un error al intentar actualizar el estado del equipo. Recargue la página e intente nuevamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            select.val(data.old_status).trigger("change");
        });

    });

    $(".censo-feature-value-list").on("change", function () {
        const select = $(this);
        const data = {
            api_key: api_key,
            _method: "POST",
            id: select.data("id"),
            feature: select.data("feature"),
            old_value: select.data("value"),
            value: select.find("option:selected").text().trim(),
        };

        if (data.value == "" && data.old_value == "") return;

        events.post("/api/v1/Support/Branch-Inventory/UpdateFeatureValue/" + data.id,
            data,
            function (r) {
                if (r.code != 200) {
                    Swal.fire({
                        text: r.message,
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                }
            }, function () {
                Swal.fire({
                    text: "Ha ocurrido un error al intentar actualizar la característica del equipo. Recargue la página e intente nuevamente.",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            });
    });

    $(".device-accesories").on("blur", function () {
        const input = $(this);
        const data = {
            api_key: api_key,
            _method: "POST",
            id: input.data("id"),
            component: input.data("component"),
            old_quantity: parseInt(input.data("quantity")),
            quantity: parseInt(input.val()),
        };

        if (data.quantity == data.old_quantity) return;

        events.post("/api/v1/Support/Branch-Inventory/UpdateAccesory/" + data.id, data, function (r) {
            if (r.code != 200) {
                Swal.fire({
                    text: r.message,
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
                input.val(data.old_quantity);
            }
        }, function () {
            Swal.fire({
                text: "Ha ocurrido un error al intentar actualizar la cantidad del accesorio. Recargue la página e intente nuevamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            input.val(data.old_quantity);
        })
    });

    function updateSerial(input, data) {
        events.post("/api/v1/Support/Branch-Inventory/UpdateSerial/" + data.id, {
            api_key: api_key,
            _method: "POST",
            serial: data.serial,
        }, function (r) {
            if (r.code == 200) {
                events.loading();
                goToPoint(data.point, data.id);
            } else {
                Swal.fire({
                    text: r.message,
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
                input.val(data.old_serial);
            }
        }, function () {
            Swal.fire({
                text: "Ha ocurrido un error al intentar actualizar el número de serie del equipo. Recargue la página e intente nuevamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            input.val(data.old_serial);
        });
    }

    function goToPoint(point, device = null) {
        let url = window.location.href;
        const urlObject = new URL(url);
        urlObject.search = '';
        urlObject.hash = '';
        let newUrl = urlObject.toString() + "?point=" + point + (device ? "&device=" + device : "");
        window.location.href = newUrl;
    }

    function goToDevice(device) {
        if (device) {
            if ($("#device-card-" + device).length > 0) {
                const scrollDiv = $("#device-card-" + device).offset().top - 100;
                window.scrollTo({ top: scrollDiv, behavior: 'smooth' });
            }
        }
    }

    function initDeviceSelects() {
        $(".censo-model-list").select2({
            theme: "bootstrap-5",
        });

        // initDeviceModelSelect();
    }

    buttons.initClic("#new-device-form-button, .missing-subline-button", function (btn) {
        let subline = btn.data("subline") ? btn.data("subline") : null;

        resetNewDeviceForm(subline);
        modal.show();

        modal.onhide(function () {
            resetNewDeviceForm(subline);
        });

        buttons.initClic("#branch-inventory-new-device-button", function (btn) {
            if (validate.form("#new-device-form")) {
                const data = {
                    api_key: api_key,
                    _method: "PUT",
                    model: $("#new-device-model").val(),
                    serial: $("#new-device-serial").val(),
                    status: $("#new-device-status").val(),
                    service: $("#new-device-service").val(),
                    area: $("#new-device-area").val(),
                    point: $("#new-device-point").val(),
                };

                events.post("/api/v1/Support/Branch-Inventory/" + data.service + "/" + data.area + "/" + data.point + "/Device",
                    data, function (r) {
                        if (r.code == 200) {
                            goToPoint(data.point, r.data.device);
                        } else {
                            Swal.fire({
                                text: r.message,
                                icon: "error",
                                confirmButtonText: "Aceptar"
                            });
                        }
                    }, function () {
                        Swal.fire({
                            text: "Ha ocurrido un error al intentar registrar el nuevo equipo. Recargue la página e intente nuevamente.",
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    });
            }
        });
    });

    function resetNewDeviceForm(subline = null) {
        validate.form_reset("#new-device-form");
        setModelOptions(subline);

        $("#new-device-model").val("").trigger("change");
        $("#new-device-serial").val("");
        $("#new-device-status").val(17);
    }

    function setModelOptions(subline = null) {
        $("#new-device-model option").remove();
        let filter_models = models;
        if (subline) {
            filter_models = models.filter(function (model) {
                return model.subline == subline;
            });
        }

        $.each(filter_models, function (i, model) {
            $("#new-device-model").append("<option value='" + model.id + "' data-subline='" + model.subline + "' data-hide='0'>" + model.text + "</option>");
        });

        $("#new-device-model").select2({
            theme: "bootstrap-5",
            dropdownParent: $('#branch-inventory-new-device-modal'),
            placeholder: "Seleccione un modelo"
        });

    }

    buttons.initClic(".censo-delete-device", function (btn) {
        Swal.fire({
            title: "¿Eliminar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            html: "¿Está seguro de eliminar el equipo?<br><strong>Esta acción no se puede deshacer</strong>",
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    api_key: api_key,
                    _method: "DELETE",
                };

                events.post("/api/v1/Support/Branch-Inventory/Device/" + btn.data("id"),
                    data, function (r) {
                        if (r.code == 200) {
                            goToPoint(btn.data("point"));
                        } else {
                            Swal.fire({
                                text: r.message,
                                icon: "error",
                                confirmButtonText: "Aceptar"
                            });
                        }
                    }, function () {
                        Swal.fire({
                            text: "Ha ocurrido un error al intentar eliminar el equipo. Recargue la página e intente nuevamente.",
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    });
            }
        });
    });

    events.loaded();
}); 