import{E as m}from"./Events-89d9745c.js";import{T as f,S as b}from"./Status-92ab773d.js";import{B as w,A as h}from"./Alerts-6102510b.js";import"./sweetalert2-985235ef.js";$(function(){const i=new m,a=new f,c=new w,e=new h,s=new b;u();function u(){i.post("/api/v1/Support/Branch-Inventory",{api_key,_method:"GET"},function(n){n.code==200&&a.init("branch-inventories-table",n.data.BranchInventories,d(),l(),function(){a.sortByColumn("branch-inventories-table",2,"asc")},function(){})})}function d(){return[{data:"Id",render:function(t,r,o){return`
                    <div class="dropdown">
                        <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                    `},className:"text-center"},{data:"Id"},{data:"Branch"},{data:"Attendant"},{data:"StatusId",render:function(t,r,o){return s.render(o.StatusId)},className:"text-center"},{data:"Created_at",render:function(t,r,o){return moment(t).format("DD/MM/YYYY HH:mm")}}]}function l(){return[{targets:["_all"],className:"align-middle"}]}c.initClic("#logistic-new-pickup-form-button",function(n){$("#pickup-branch option:first").prop("selected",!0),modal.show(),p()});function p(){validate.form_reset("#new-pickup-form"),c.initClic("#logistic-new-pickup-button",function(n){validate.form("#new-pickup-form")&&i.post("/api/v1/Logistic/Pickup",{api_key,_method:"PUT",branch:$("#pickup-branch").val()},function(t){t.code==200?e.success(t.message,4e3,function(){modal.hide(),window.location.href="/Logistica/Recoleccion/"+t.data.Pickup.Id}):e.error(t.message)},function(){e.error("No se pudo crear el registro de recolección. Recargue la página e intente nuevamente.",4e3,function(){modal.hide()})})})}});
