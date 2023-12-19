import{A as M,E as O}from"./Events-fb55d1f4.js";import{T as V}from"./Table-f78b8306.js";import"./sweetalert2-374f0b4f.js";$(function(){const i=new O,s=new V,o=new M,f=new bootstrap.Offcanvas("#destinationDrawer"),p=document.getElementById("destinationDrawer"),l=document.getElementById("newDestinationForm"),g=document.querySelector("#destinationDrawer .close-drawer-button"),u=new bootstrap.Offcanvas("#assignToSupportDrawer"),m=document.getElementById("assignToSupportDrawer"),c=document.getElementById("assignToSupportForm"),v=document.querySelector("#assignToSupportDrawer .close-drawer-button"),b=new bootstrap.Modal("#destinationDevicesModal",{keyboard:!1}),w=document.getElementById("destinationDevicesModal"),y=document.getElementById("assignDevices-btn");let r;i.loaded(),d();function d(){i.api("/api/v3/Warehouse/Distribution/"+g_distribution_id+"/Devices","GET",{},{Authorization:"Bearer "+api_key},function(e){s.init("destinations-table",e.branches,h(),E(),function(){s.sortByColumn("destinations-table",0,"desc"),W()}),s.init("details-table",e.devices,I(),B())})}function D(e){switch(e.StatusId){case 66:case"66":return`
                <li class="my-2"><a role="button" class="table-action-logistic dropdown-item fw-bold text-primary"><i class="bi bi-truck"></i> Entregar a Logística</a></li>
                <li class="my-2"><a role="button" class="table-action-support dropdown-item fw-bold text-secondary"><i class="bi bi-person-badge"></i> Entregar a Soporte</a></li>
                <li class="my-2"><a role="button" class="table-action-cancel dropdown-item fw-bold text-danger"><i class="bi bi-x"></i> Cancelar</a></li>`;case 68:case"68":return`
                <li class="my-2"><a role="button" class="table-action-cancel-logistic dropdown-item fw-bold text-danger"><i class="bi bi-x"></i> Cancelar entrega</a></li>
                `;case 69:case"69":return`
                <li class="my-2"><a role="button" class="table-action-cancel-warehouse-to-support dropdown-item fw-bold text-danger"><i class="bi bi-x"></i> Cancelar entrega</a></li>
                `}}function h(){return[{data:"Id",render:{_:function(t,n,a){return`
                        <div class="btn-group">
                            <button type="button" class="btn border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                `+D(a)+`
                            </ul>
                        </div>`}}},{data:"Branch"},{data:"State"},{data:"Devices",render:{_:function(t,n,a){return'<span class="fs-5 fw-bold">'+t+"</span>"}}},{data:"Status",render:{_:function(t,n,a){return'<span class="fs-5 fw-bold">'+t+'</span><br><span class="fs-7">'+a.ResponsibleName+"</span>"}}},{data:"CurrentTransfer",render:{_:function(t,n,a){return t!==null?'<span class="fs-6 fw-bold">'+t+"</span>":""}}}]}function E(){return[{targets:["_all"],className:"align-middle"},{targets:[0,3,5],className:"text-center"}]}function I(){return[{data:"Line",render:{_:function(t,n,a){return'<span class="fs-7 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Model",render:{_:function(t,n,a){return'<span class="fs-9">'+a.Brand+'</span><br><span class="fs-7 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Serial",render:{_:function(t,n,a){return'<span class="fs-6 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Branch"},{data:"Area"},{data:"State"},{data:"Status",render:{_:function(t,n,a){return'<span class="fs-6 fw-bold">'+t+'</span><br><span class="fs-7">'+a.ResponsibleName+"</span>"}}}]}function B(){return[{targets:["_all"],className:"align-middle"}]}g.addEventListener("click",function(e){e.preventDefault(),f.hide()}),p.addEventListener("hide.bs.offcanvas",e=>{l.reset(),l.classList.remove("was-validated"),r!==void 0&&r.devices!==void 0&&(r.devices=[])}),v.addEventListener("click",function(e){e.preventDefault(),u.hide()}),m.addEventListener("hide.bs.offcanvas",e=>{c.reset(),c.classList.remove("was-validated")}),c.addEventListener("submit",function(e){if(c.classList.add("was-validated"),e.preventDefault(),!c.checkValidity())e.stopPropagation();else{const t={branchId:c.getAttribute("data-branch"),technician:document.getElementById("technicians").value,from:"WAREHOUSE"};i.loading(),i.api("/api/v3/Warehouse/Destination/ToSupport/"+g_distribution_id,"POST",t,{Authorization:"Bearer "+api_key},function(n){u.hide(),i.loaded(),o.toast(n.message,"success"),s.destroy("destinations-table"),s.destroy("details-table"),d()})}}),l.addEventListener("submit",function(e){l.classList.add("was-validated"),e.preventDefault(),l.checkValidity()?(r={branch:document.getElementById("branch").value,area:document.getElementById("attentionArea").value,devices:[]},i.loading(),b.show(),_()):e.stopPropagation()}),w.addEventListener("hide.bs.modal",function(e){s.destroy("inventory-table"),s.destroy("assigned-inventory-table"),r.devices=[]});function _(){i.api("/api/v3/Warehouse/Distribution/AvailableInventory/"+g_customer_id,"GET",{},{Authorization:"Bearer "+api_key},function(e){s.init("inventory-table",e.inventory,L(),S(),function(){k()}),s.init("assigned-inventory-table",{},A(),T())})}function L(){return[{data:"Line",render:{_:function(t,n,a){return'<span class="fs-9 fw-bold">'+t+'</span><br><span class="fs-7 fw-bold">'+a.Subline+"</span>"},sort:function(t,n,a){return t}}},{data:"Model",render:{_:function(t,n,a){return'<span class="fs-9">'+a.Brand+'</span><br><span class="fs-7 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Serial",render:{_:function(t,n,a){return'<span class="fs-6 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Id",render:{_:function(t,n,a){return'<a role="button" class="fs-5"><i class="bi bi-arrow-right-square-fill assignDevice-btn"></i></a>'}}}]}function A(){return[{data:"Id",render:{_:function(t,n,a){return'<a role="button" class="fs-5"><i class="bi bi-arrow-left-square-fill removeDevice-btn"></i></a>'}}},{data:"Line",render:{_:function(t,n,a){return'<span class="fs-7 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Model",render:{_:function(t,n,a){return'<span class="fs-9">'+a.Brand+'</span><br><span class="fs-7 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}},{data:"Serial",render:{_:function(t,n,a){return'<span class="fs-6 fw-bold">'+t+"</span>"},sort:function(t,n,a){return t}}}]}function S(){return[{targets:["_all"],className:"align-middle"},{targets:[3],className:"text-center"}]}function T(){return[{targets:["_all"],className:"align-middle"},{targets:[0],className:"text-center"}]}function k(){document.getElementById("inventory-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("assignDevice-btn")){let t=s.rowData("inventory-table",e.target.closest("tr"));t!==void 0&&(r.devices.push(t),s.addRow("assigned-inventory-table",t),s.removeRow("inventory-table",e.target.closest("tr")))}}),document.getElementById("assigned-inventory-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("removeDevice-btn")){let t=s.rowData("assigned-inventory-table",e.target.closest("tr"));r.devices=r.devices.filter(function(n){return n.Id!==t.Id}),s.addRow("inventory-table",t),s.removeRow("assigned-inventory-table",e.target.closest("tr"))}})}y.addEventListener("click",function(e){if(e.preventDefault(),r.devices.length>0){const t={branch:document.getElementById("branch").value,area:document.getElementById("attentionArea").value,devices:r.devices.map(function(n){return n.Id})};i.loading(),i.api("/api/v3/Warehouse/Distribution/"+g_distribution_id+"/Devices","PUT",t,{Authorization:"Bearer "+api_key},function(n){b.hide(),f.hide(),i.loaded(),o.toast("Dispositivos asignados correctamente","success"),s.destroy("destinations-table"),s.destroy("details-table"),d()})}else o.toast("Debe seleccionar al menos un dispositivo","error")});function W(){x(),C(),N(),R(),z()}function x(){document.getElementById("destinations-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("table-action-cancel")){let t=s.rowData("destinations-table",e.target.closest("tr"));t!==void 0&&o.confirm("¿Está seguro de cancelar la entrega?",function(){i.api("/api/v3/Warehouse/Distribution/"+g_distribution_id,"DELETE",{branchId:t.BranchId,statusId:t.StatusId},{Authorization:"Bearer "+api_key},function(n){s.destroy("destinations-table"),s.destroy("details-table"),d(),o.toast(n.message,"success")})})}})}function C(){document.getElementById("destinations-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("table-action-logistic")){let t=s.rowData("destinations-table",e.target.closest("tr"));t!==void 0&&o.confirm("Se va a generar un traspaso al área de logística ¿Desea continuar?",function(){i.api("/api/v3/Warehouse/Destination/ToLogistic/"+g_distribution_id,"POST",{from:"WAREHOUSE",branchId:t.BranchId,statusId:t.StatusId},{Authorization:"Bearer "+api_key},function(n){s.destroy("destinations-table"),s.destroy("details-table"),d(),o.toast(n.message,"success")})})}})}function N(){document.getElementById("destinations-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("table-action-cancel-logistic")){let t=s.rowData("destinations-table",e.target.closest("tr"));t!==void 0&&o.confirm("Cancelaremos la entrega a logística. ¿Desea continuar?",function(){i.api("/api/v3/Warehouse/Destination/ToLogistic/"+g_distribution_id,"DELETE",{branchId:t.BranchId,transfer:t.CurrentTransfer},{Authorization:"Bearer "+api_key},function(n){s.destroy("destinations-table"),s.destroy("details-table"),d(),o.toast(n.message,"success")})})}})}function R(){document.getElementById("destinations-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("table-action-support")){let t=s.rowData("destinations-table",e.target.closest("tr"));t!==void 0&&(u.show(),c.setAttribute("data-branch",t.BranchId))}})}function z(){document.getElementById("destinations-table").addEventListener("click",function(e){if(e.target&&e.target.classList.contains("table-action-cancel-warehouse-to-support")){let t=s.rowData("destinations-table",e.target.closest("tr"));t!==void 0&&o.confirm("Cancelaremos la entrega al personal de soporte. ¿Desea continuar?",function(){i.api("/api/v3/Warehouse/Destination/ToSupport/"+g_distribution_id,"DELETE",{from:"WAREHOUSE",branchId:t.BranchId,warehouseId:t.WarehouseId,statusId:t.StatusId,transfer:t.CurrentTransfer},{Authorization:"Bearer "+api_key},function(n){s.destroy("destinations-table"),s.destroy("details-table"),d(),o.toast(n.message,"success")})})}})}});
