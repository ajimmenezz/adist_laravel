import{A as P,E as F}from"./Events-fb55d1f4.js";import{B as N}from"./Buttons-e9a12f53.js";import{M as v,V as j}from"./Validates-7d06d876.js";import{S as x}from"./sweetalert2-374f0b4f.js";$(function(){const c=new F,s=new N,n=new P,d=new v("logistic-pickup-box-selection-modal"),r=new v("logistic-pickup-not-censo-item-modal"),m=new j;c.loaded(),b(),k(),I(),f(),$(".line-container .line-header").css("top",$("#main-header").height()+"px"),s.initClic(".inventory-item",function(e){e.toggleClass("text-white"),e.toggleClass("bg-success"),!e.hasClass("bg-success")&&$("#show-only-selected-button").hasClass("btn-success")&&u(),$(".inventory-item.bg-success").length>0?$(".inventory-action-buttons").removeClass("d-none"):($(".inventory-action-buttons").addClass("d-none"),$("#show-only-selected-button").removeClass("btn-success").addClass("btn-secondary"),p())}),s.initClic("#show-only-selected-button",function(e){e.toggleClass("btn-secondary"),e.toggleClass("btn-success"),e.hasClass("btn-success")?u():p()}),s.initClic("#assign-box-button",function(e){if($(".inventory-item.bg-success").length<=0){n.error("Seleccione al menos un equipo para asignar a la caja");return}else $("#pickup-box option:first").prop("selected",!0),d.show(),g()});function g(){m.form_reset("#assign-box-form"),s.initClic("#logistic-pickup-box-selection-button",function(e){if(m.form("#assign-box-form")){const o=$("#logistic-pickup-id").val(),t={api_key,_method:"PUT",box:$("#pickup-box").val(),items:C()};c.post("/api/v1/Logistic/Pickup/"+o+"/BoxedCensoItems",t,function(i){i.code==200?n.success(i.message,3e3,function(){y(),h($("#pickup-box").val(),i.data.Items),d.hide()}):n.error(i.message)},function(){n.error("Error al asignar los equipos a la caja")})}})}function h(e,o){$.each(o,function(t,i){$("#box-content-"+e+" .box-content-items").append(w(e,i))}),$("#box-selection-"+e+" i").removeClass("bi-box2").addClass("bi-box2-fill"),b()}function C(){let e=[];return $(".inventory-item.bg-success").each(function(){e.push($(this).data("id"))}),e}function u(){$(".line-container").each(function(){let e=$(this);e.find(".inventory-item.bg-success").length<=0&&e.addClass("d-none"),e.find(".inventory-item").each(function(){let o=$(this);o.hasClass("bg-success")||o.addClass("d-none")})})}function p(){$(".line-container").removeClass("d-none"),$(".inventory-item").removeClass("d-none")}function y(){$(".line-container").each(function(){let e=$(this);e.find(".inventory-item").each(function(){let o=$(this);o.hasClass("bg-success")&&o.remove()}),e.find(".inventory-item").length<=0&&e.remove()})}s.initClic(".box-selection",function(e){const o=e.data("box");$(".box-content").addClass("d-none"),$("#box-content-"+o).removeClass("d-none")});function b(){s.initClic(".remove-item-from-box-button",function(e){x.fire({title:"¿Está seguro?",text:"Se eliminará el equipo de la caja",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Sí, eliminar",cancelButtonText:"Cancelar"}).then(o=>{if(o.value){const t=$("#logistic-pickup-id").val(),i={api_key,_method:"DELETE",censoId:e.data("censoid")};c.post("/api/v1/Logistic/Pickup/"+t+"/BoxedCensoItems",i,function(a){a.code==200?n.success(a.message,2e3,function(){e.closest(".row.box-content-item").remove(),$("#box-content-"+e.data("box")+" .row.box-content-item").length<=0&&$("#box-extra-items-"+e.data("box")+" .row").length<=0&&$("#box-selection-"+e.data("box")+" i").removeClass("bi-box2-fill").addClass("bi-box2")}):n.error(a.message)},function(){n.error("Error al eliminar el equipo de la caja")})}})})}function w(e,o){return`
        <div class="row box-content-item">
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                `+o.Linea+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                `+o.Sublinea+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                `+o.Marca+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                `+o.Modelo+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bold fs-7">
                `+o.Serie+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                <button data-censoid="`+o.Id+' " data-box="'+e+`"
                    class="remove-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                    <i class="bi bi-arrow-bar-up"></i>
                </button>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>
        `}function k(){document.querySelector('a[data-bs-toggle="tab"]').addEventListener("shown.bs.tab",o=>{o.target.id=="inventory-tab"&&(c.loading(),window.location.reload())})}s.initClic(".not-censo-item-form-button",function(e){_(),$("#logistic-pickup-id").val();const o=e.data("box");$("#not-censo-item-box").empty().append(o),$("#not-censo-item-box-input").val(o),r.show()});function _(){$("#not-censo-item-switch-type").prop("checked",!1),$("#pickup-model-item option:first").prop("selected",!0).trigger("change"),$("#pickup-serial-item").val(""),$("#components-list").empty(),$(".full-device-type").removeClass("d-none"),$(".components-type").addClass("d-none")}function I(){E(),B(),S()}function E(){$("#pickup-model-item").select2({dropdownParent:$("#logistic-pickup-not-censo-item-modal")}),$("#pickup-model-item").on("change",function(){let e=$(this).val();if(e==""){$("#components-list").empty();return}c.post("/api/v1/Devices/"+e+"/Components",{api_key,_method:"GET",model:e},function(o){o.code==200?($("#components-list").empty(),o.data.Components.forEach(function(t){$("#components-list").append(q(t))})):n.error(o.message)})})}function B(){$("#not-censo-item-switch-type").on("change",function(){$(this).is(":checked")?($(".full-device-type").addClass("d-none"),$(".components-type").removeClass("d-none")):($(".full-device-type").removeClass("d-none"),$(".components-type").addClass("d-none"))})}function S(){s.initClic("#save-not-censo-item-button",function(e){let o={api_key,_method:"PUT",pickup_id:$("#logistic-pickup-id").val(),box:$("#not-censo-item-box-input").val(),type:$("#not-censo-item-switch-type").is(":checked")?"c":"d",model:$("#pickup-model-item option:selected").val(),serial:$("#pickup-serial-item").val(),components:[]};if($(".component-quantity").each(function(t){let i=$(this).val();i>0&&o.components.push({id:$(this).data("id"),quantity:i})}),o.model==""){n.error("Debe seleccionar un modelo");return}if(o.type=="c"&&o.components.length==0){n.error("Si el tipo es componente debe agregar al menos un componente");return}c.post("/api/v1/Logistic/Pickup/"+o.pickup_id+"/Items",o,function(t){t.code==200?n.success(t.message,2e3,function(){T(o.box,t.data.Items),r.hide()}):n.error(t.message)},function(){n.error("Ocurrió un error al guardar los equipos / componentes extra")})})}function T(e,o){$("#box-extra-items-"+e).empty(),$.each(o.d,function(t,i){$("#box-extra-items-"+e).append(L(e,i))}),$.each(o.c,function(t,i){$("#box-extra-items-"+e).append(M(e,i))}),$("#box-selection-"+e+" i").removeClass("bi-box2").addClass("bi-box2-fill"),f()}function q(e){return`
        <div class="row my-3">
            <div class="col-8 mb-0 fw-bolder">
                `+e.Nombre+`
            </div>
            <div class="col-4 mb-0">
                <input type="number" data-id="`+e.Id+`" class="form-control form-control-sm component-quantity" >
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>`}function L(e,o){return`
        <div class="row box-content-extra-item">
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                `+o.Linea+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bolder">
                `+o.Sublinea+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                `+o.Marca+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                `+o.Modelo+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0 fw-bold fs-7">
                `+o.SerialNumber+`
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                <button data-id="`+o.Id+'" data-box="'+e+`"
                    class="remove-extra-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                    <i class="bi bi-arrow-bar-up"></i>
                </button>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>
        `}function M(e,o){return`
        <div class="row box-content-extra-component">
            <div class="col-12 col-sm-8 col-md-10 mb-0">
                <span class="fw-bold fs-6">`+o.Quantity+`</span>
                <span class="fw-bold">`+o.Componente+`</span>
                <span class="text-lowercase">de</span>
                <span class="fw-bold">`+o.Modelo+"</span> ("+o.Marca+`)
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-0">
                <button data-id="`+o.Id+'" data-box="'+e+`"
                    class="remove-extra-item-from-box-button btn btn-danger btn-sm py-0 px-1 float-end">
                    <i class="bi bi-arrow-bar-up"></i>
                </button>
            </div>
            <div class="col-12">
                <hr class="my-1">
            </div>
        </div>
        `}function f(){s.initClic(".remove-extra-item-from-box-button",function(e){x.fire({title:"¿Está seguro de eliminar el equipo / componente extra?",text:"Esta acción no se puede deshacer",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Si, eliminar",cancelButtonText:"Cancelar"}).then(o=>{if(o.value){const t=$("#logistic-pickup-id").val();let i={api_key,_method:"DELETE"};c.post("/api/v1/Logistic/Pickup/"+t+"/Items/"+$(e).data("id"),i,function(a){a.code==200?n.success(a.message,2e3,function(){let l=$(e).data("box");$(e).parent().parent().remove(),$("#box-content-"+l+" .row.box-content-item").length<=0&&$("#box-extra-items-"+l+" .row").length<=0&&$("#box-selection-"+l+" i").removeClass("bi-box2-fill").addClass("bi-box2")}):n.error(a.message)},function(){n.error("Ocurrió un error al guardar los equipos / componentes extra")})}})})}s.initClic(".btn-pdf",function(e){const o={api_key,_method:"GET",box:$(e).data("box")};c.post("/api/v1/Logistic/Pickup/"+$("#logistic-pickup-id").val()+"/Pdf",o,function(t){t.code==200?window.open(t.data.url,"_blank"):n.error(t.message)},function(){n.error("Ocurrió un error al generar el pdf")})})});
