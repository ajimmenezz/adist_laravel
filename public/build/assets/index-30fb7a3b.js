import{E as f}from"./Events-89d9745c.js";import{T as m,S as b}from"./Status-92ab773d.js";import{B as w}from"./Buttons-e9a12f53.js";import{A as h}from"./Alerts-020b696c.js";import"./sweetalert2.all-92414cd4.js";/* empty css                    */$(function(){const a=new f,s=new m,r=new w,i=new h,c=new b;l();function l(){a.post("/api/v1/Support/Branch-Inventory",{api_key,_method:"GET"},function(e){e.code==200&&s.init("branch-inventories-table",e.data.BranchInventories,d(),u(),function(){s.sortByColumn("branch-inventories-table",5,"desc")},function(){})})}function d(){return[{data:"Id",render:function(t,o,n){return`
                    <div class="dropdown">
                        <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="Censos/${t}" class="fw-bold text-uppercase fs-7 dropdown-item" type="button"><i class="fs-5 bi bi-pencil-square me-3"></i> Capturar datos</a></li>
                            <li><button data-serviceid="${t}" class="fw-bold text-uppercase fs-7 dropdown-item" type="button"><i class="fs-5 bi bi-person-check-fill me-3"></i> Asignar</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button data-serviceid="${t}" class="fw-bold text-uppercase fs-7 dropdown-item" type="button"><i class="fs-5 bi bi-trash3-fill me-3"></i>Cancelar</button></li>
                        </ul>
                    </div>
                    `},className:"text-center"},{data:"Id"},{data:"Branch"},{data:"Attendant"},{data:"StatusId",render:function(t,o,n){return c.render(n.StatusId)},className:"text-center"},{data:"Created_at",render:{_:function(t,o,n){return moment(t).format("DD/MM/YYYY HH:mm")},sort:function(t,o,n){return moment(t).format("YYYYMMDDHHmm")}}}]}function u(){return[{targets:["_all"],className:"align-middle"}]}r.initClic("#logistic-new-pickup-form-button",function(e){$("#pickup-branch option:first").prop("selected",!0),modal.show(),p()});function p(){validate.form_reset("#new-pickup-form"),r.initClic("#logistic-new-pickup-button",function(e){validate.form("#new-pickup-form")&&a.post("/api/v1/Logistic/Pickup",{api_key,_method:"PUT",branch:$("#pickup-branch").val()},function(t){t.code==200?i.success(t.message,4e3,function(){modal.hide(),window.location.href="/Logistica/Recoleccion/"+t.data.Pickup.Id}):i.error(t.message)},function(){i.error("No se pudo crear el registro de recolección. Recargue la página e intente nuevamente.",4e3,function(){modal.hide()})})})}});