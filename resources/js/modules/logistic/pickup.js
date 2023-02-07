import Events from "../shared/Events";
import Table from "../shared/Table";
import Buttons from "../shared/Buttons";
import Alerts from "../shared/Alerts";
import Modals from "../shared/Modals";
import Validate from "../shared/Validates";
import Swal from "sweetalert2";

$(function() {
    const events = new Events();
    const table = new Table();
    const buttons = new Buttons();
    const alerts = new Alerts();
    const modal = new Modals("logistic-pickup-box-selection-modal");
    const modal_item = new Modals("logistic-pickup-not-censo-item-modal");
    const validate = new Validate();

    events.loaded();

    initRemoveItemFromBox();
    initTabChange();
    initNotCensoItemForm();
    initRemoveExtraItemFromBox();

    $(".line-container .line-header").css("top", $("#main-header").height() + "px");

    buttons.initClic(".inventory-item", function(elem) {
        elem.toggleClass("text-white");
        elem.toggleClass("bg-success");

        if (!elem.hasClass("bg-success") && $("#show-only-selected-button").hasClass("btn-success")) {
            showSelected();
        }

        if ($(".inventory-item.bg-success").length > 0) {
            $(".inventory-action-buttons").removeClass("d-none");
        } else {
            $(".inventory-action-buttons").addClass("d-none");
            $("#show-only-selected-button").removeClass("btn-success").addClass("btn-secondary");
            showAll();
        }
    });

    buttons.initClic("#show-only-selected-button", function(elem) {
        elem.toggleClass("btn-secondary");
        elem.toggleClass("btn-success");

        if (elem.hasClass("btn-success")) {
            showSelected();
        } else {
            showAll();
        }
    });

    buttons.initClic("#assign-box-button", function(elem) {
        if ($(".inventory-item.bg-success").length <= 0) {
            alerts.error("Seleccione al menos un equipo para asignar a la caja");
            return;
        } else {
            $("#pickup-box option:first").prop("selected", true);
            modal.show();
            initAssignBoxForm();
        }
    });

    function initAssignBoxForm() {
        validate.form_reset("#assign-box-form");
        buttons.initClic("#logistic-pickup-box-selection-button", function(btn) {
            if (validate.form("#assign-box-form")) {
                const pickup_id = $("#logistic-pickup-id").val();
                const data = {
                    api_key: api_key,
                    _method: "PUT",
                    box: $("#pickup-box").val(),
                    items: getSelectedItems()
                };

                events.post("/api/v1/Logistic/Pickup/" + pickup_id + "/BoxedCensoItems",
                    data,
                    function(r) {
                        if (r.code == 200) {
                            alerts.success(r.message, 3000, function() {
                                clearBoxedItems();
                                setBoxedItems($("#pickup-box").val(), r.data.Items);
                                modal.hide();
                            });
                        } else {
                            alerts.error(r.message);
                        }
                    },
                    function() {
                        alerts.error("Error al asignar los equipos a la caja");
                    });
            }
        });
    }

    function setBoxedItems(box, items) {
        $.each(items, function(index, item) {
            $("#box-content-" + box + " .box-content-items").append(boxedItemComponent(box, item));
        });

        $("#box-selection-" + box + " i").removeClass("bi-box2").addClass("bi-box2-fill");
        initRemoveItemFromBox();
    }

    function getSelectedItems() {
        let items = [];
        $(".inventory-item.bg-success").each(function() {
            items.push($(this).data("id"));
        });

        return items;
    }

    function showSelected() {
        $(".line-container").each(function() {
            let section = $(this);
            if (section.find(".inventory-item.bg-success").length <= 0) {
                section.addClass("d-none");
            }

            section.find(".inventory-item").each(function() {
                let item = $(this);
                if (!item.hasClass("bg-success")) {
                    item.addClass("d-none");
                }
            });
        });
    }

    function showAll() {
        $(".line-container").removeClass("d-none");
        $(".inventory-item").removeClass("d-none");
    }

    function clearBoxedItems() {
        $(".line-container").each(function() {
            let section = $(this);

            section.find(".inventory-item").each(function() {
                let item = $(this);
                if (item.hasClass("bg-success")) {
                    item.remove();
                }
            });

            if (section.find(".inventory-item").length <= 0) {
                section.remove();
            }
        });
    }

    buttons.initClic(".box-selection", function(elem) {
        const box = elem.data("box");
        $(".box-content").addClass("d-none");
        $("#box-content-" + box).removeClass("d-none");
    });

    function initRemoveItemFromBox() {

        buttons.initClic(".remove-item-from-box-button", function(elem) {
            Swal.fire({
                title: "¿Está seguro?",
                text: "Se eliminará el equipo de la caja",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then(result => {
                if (result.value) {
                    const pickup_id = $("#logistic-pickup-id").val();
                    const data = {
                        api_key: api_key,
                        _method: "DELETE",
                        censoId: elem.data("censoid")
                    };

                    events.post("/api/v1/Logistic/Pickup/" + pickup_id + "/BoxedCensoItems",
                        data,
                        function(r) {
                            if (r.code == 200) {
                                alerts.success(r.message, 2000, function() {
                                    elem.closest(".row.box-content-item").remove();
                                    if (
                                        $("#box-content-" + elem.data("box") + " .row.box-content-item").length <= 0 &&
                                        $("#box-extra-items-" + elem.data("box") + " .row").length <= 0) {
                                        $("#box-selection-" + elem.data("box") + " i").removeClass("bi-box2-fill").addClass("bi-box2");
                                    }
                                });
                            } else {
                                alerts.error(r.message);
                            }
                        },
                        function() {
                            alerts.error("Error al eliminar el equipo de la caja");
                        });
                }
            });
        });
    }

    function boxedItemComponent(box, item) {
        return `
        <div class="row box-content-item">
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                ` + item.Linea + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                ` + item.Sublinea + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                ` + item.Marca + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                ` + item.Modelo + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bold fs-7">
                ` + item.Serie + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                <button data-censoid="` + item.Id + ` " data-box="` + box + `"
                    class="remove-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                    <i class="bi bi-arrow-bar-up"></i>
                </button>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>
        `;
    }

    function initTabChange() {
        const tabEl = document.querySelector('a[data-bs-toggle="tab"]');
        tabEl.addEventListener('shown.bs.tab', event => {
            if (event.target.id == "inventory-tab") {
                events.loading();
                window.location.reload();
            }
        });
    }

    buttons.initClic(".not-censo-item-form-button", function(elem) {
        resetNotCensoItemForm();
        const pickup_id = $("#logistic-pickup-id").val();
        const box = elem.data("box");

        $("#not-censo-item-box").empty().append(box);
        $("#not-censo-item-box-input").val(box);

        modal_item.show();
    });

    function resetNotCensoItemForm() {
        $("#not-censo-item-switch-type").prop("checked", false);
        $("#pickup-model-item option:first").prop('selected', true).trigger('change');
        $("#pickup-serial-item").val("");
        $("#components-list").empty();
        $(".full-device-type").removeClass("d-none");
        $(".components-type").addClass("d-none");
    }

    function initNotCensoItemForm() {
        initModelSelect();
        initItemType();
        initSaveNotCensoItem();


    }

    function initModelSelect() {
        $("#pickup-model-item").select2({
            dropdownParent: $('#logistic-pickup-not-censo-item-modal')
        });

        $("#pickup-model-item").on("change", function() {
            let model = $(this).val();
            if (model == "") {
                $("#components-list").empty();
                return;
            }
            events.post("/api/v1/Devices/" + model + "/Components", {
                api_key: api_key,
                _method: "GET",
                model: model
            }, function(r) {
                if (r.code == 200) {
                    $("#components-list").empty();
                    r.data.Components.forEach(function(component) {
                        $("#components-list").append(componentItemComponent(component));
                    });
                } else {
                    alerts.error(r.message);
                }
            });
        });
    }

    function initItemType() {
        $("#not-censo-item-switch-type").on("change", function() {

            let checked = $(this).is(":checked");
            if (checked) {
                $(".full-device-type").addClass("d-none");
                $(".components-type").removeClass("d-none");
            } else {
                $(".full-device-type").removeClass("d-none");
                $(".components-type").addClass("d-none");
            }

        });
    }

    function initSaveNotCensoItem() {
        buttons.initClic("#save-not-censo-item-button", function(btn) {
            let data = {
                api_key: api_key,
                _method: "PUT",
                pickup_id: $("#logistic-pickup-id").val(),
                box: $("#not-censo-item-box-input").val(),
                type: $("#not-censo-item-switch-type").is(":checked") ? "c" : "d",
                model: $("#pickup-model-item option:selected").val(),
                serial: $("#pickup-serial-item").val(),
                components: []
            };

            $(".component-quantity").each(function(c) {
                let quantity = $(this).val();
                if (quantity > 0) {
                    data.components.push({
                        id: $(this).data("id"),
                        quantity: quantity
                    });
                }
            });

            if (data.model == "") {
                alerts.error("Debe seleccionar un modelo");
                return;
            }

            if (data.type == "c" && data.components.length == 0) {
                alerts.error("Si el tipo es componente debe agregar al menos un componente");
                return;
            }

            events.post("/api/v1/Logistic/Pickup/" + data.pickup_id + "/Items",
                data,
                function(r) {
                    if (r.code == 200) {
                        alerts.success(r.message, 2000, function() {
                            addExtraItemsToBox(data.box, r.data.Items);
                            modal_item.hide();
                        });
                    } else {
                        alerts.error(r.message);
                    }
                },
                function() {
                    alerts.error("Ocurrió un error al guardar los equipos / componentes extra");
                });

        });
    }

    function addExtraItemsToBox(box, items) {
        $("#box-extra-items-" + box).empty();
        $.each(items.d, function(i, item) {
            $("#box-extra-items-" + box).append(componentExtraItem(box, item));
        });

        $.each(items.c, function(i, item) {
            $("#box-extra-items-" + box).append(componentExtraComponent(box, item));
        });

        $("#box-selection-" + box + " i").removeClass("bi-box2").addClass("bi-box2-fill");

        initRemoveExtraItemFromBox();
    }


    function componentItemComponent(component) {
        return `
        <div class="row my-3">
            <div class="col-8 mb-0 fw-bolder">
                ` + component.Nombre + `
            </div>
            <div class="col-4 mb-0">
                <input type="number" data-id="` + component.Id + `" class="form-control form-control-sm component-quantity" >
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>`;
    }

    function componentExtraItem(box, item) {
        return `
        <div class="row box-content-extra-item">
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                ` + item.Linea + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                ` + item.Sublinea + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                ` + item.Marca + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                ` + item.Modelo + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bold fs-7">
                ` + item.SerialNumber + `
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                <button data-id="` + item.Id + `" data-box="` + box + `"
                    class="remove-extra-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                    <i class="bi bi-arrow-bar-up"></i>
                </button>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>
        `;
    }

    function componentExtraComponent(box, item) {
        return `
        <div class="row box-content-extra-component">
            <div class="col-12 col-sm-8 col-md-10 mb-0">
                <span class="fw-bold fs-6">` + item.Quantity + `</span>
                <span class="fw-bold">` + item.Componente + `</span>
                <span class="text-lowercase">de</span>
                <span class="fw-bold">` + item.Modelo + `</span> (` + item.Marca + `)
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                <button data-id="` + item.Id + `" data-box="` + box + `"
                    class="remove-extra-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                    <i class="bi bi-arrow-bar-up"></i>
                </button>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>
        `;
    }

    function initRemoveExtraItemFromBox() {
        buttons.initClic(".remove-extra-item-from-box-button", function(btn) {
            Swal.fire({
                title: '¿Está seguro de eliminar el equipo / componente extra?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    const pickup_id = $("#logistic-pickup-id").val();
                    let data = {
                        api_key: api_key,
                        _method: "DELETE"
                    };

                    events.post("/api/v1/Logistic/Pickup/" + pickup_id + "/Items/" + $(btn).data("id"),
                        data,
                        function(r) {
                            if (r.code == 200) {
                                alerts.success(r.message, 2000, function() {
                                    let box = $(btn).data("box");
                                    $(btn).parent().parent().remove();
                                    if (
                                        $("#box-content-" + box + " .row.box-content-item").length <= 0 &&
                                        $("#box-extra-items-" + box + " .row").length <= 0) {
                                        $("#box-selection-" + box + " i").removeClass("bi-box2-fill").addClass("bi-box2");
                                    }

                                });
                            } else {
                                alerts.error(r.message);
                            }
                        },
                        function() {
                            alerts.error("Ocurrió un error al guardar los equipos / componentes extra");
                        });
                }
            });
        });
    }

    buttons.initClic(".btn-pdf", function(btn) {
        const data = {
            api_key: api_key,
            _method: "GET",
            box: $(btn).data("box")
        };

        events.post("/api/v1/Logistic/Pickup/" + $("#logistic-pickup-id").val() + "/Pdf", data, function(r) {
            if (r.code == 200) {
                window.open(r.data.url, "_blank");
            } else {
                alerts.error(r.message);
            }
        }, function() {
            alerts.error("Ocurrió un error al generar el pdf");
        });
    });
});