import{A as f,E as h}from"./Events-fb55d1f4.js";import"./sweetalert2-374f0b4f.js";class p{capitalize(e){return e.charAt(0).toUpperCase()+e.slice(1).toLowerCase()}slugify(e){return e.toLowerCase().replace(/[^\w ]+/g,"").replace(/ +/g,"-")}}class y{constructor(){this.string=new p}columnGrid(e){return'<div class="col-lg-4 col-md-3 col-sm-6 col-12" data-search="'+(e.ItemKey+" "+e.ItemLine+" "+e.Item)+'" id="c-'+e.Id+'">'+this.itemCard(e)+"</div>"}itemCard(e){return`
                <div class="card bg-`+(e.LastUpdateUser==1?"light":"success bg-opacity-10")+`">
                    <div class="card-body px-2 py-4">
                        <div class="d-flex px-3">
                            <div class="fw-bold fs-4 flex-fill">`+e.ItemKey+`</div>
                            <div class="text-muted text-end">`+e.ItemLine+`</div>
                        </div>
                        <div class="row my-4 align-items-center">
                            <div class="col text-center">
                                <h1 class="my-0 lh-1">`+e.Quantity+`</h1>
                                <span class="fs-7 my-0 lh-1">`+e.Measure+`</span>
                            </div>
                            <div class="col">
                                <div class="input-group mb-3">
                                    <input type="number" id="validated-quantity-`+e.Id+'" class="form-control fs-1 form-control-lg text-center d-flex justify-content-center align-items-center w-60" placeholder="0" value="'+e.ValidatedQuantity+'" aria-describedby="button-addon-'+e.Id+`">
                                    <button class="btn btn-outline-success save-validated-quantity" data-id="`+e.Id+'" type="button" id="button-addon-'+e.Id+`"><i class="bi bi-check fs-4"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="fs-3 ps-3 text-`+(e.LastUpdateUser==1?"danger":"success")+'">'+(e.LastUpdateUser==1?'<i class="bi bi-clock"></i>':'<i class="bi bi-check"></i>')+`</div>
                            <div class="flex-fill fw-bold text-end fs-5">`+e.Item+`</div>
                        </div>
                    </div>
                </div>`}}$(function(){const n=new h,e=new f,c=new y;n.loaded();const d=document.getElementById("warehouse-list"),i=document.getElementById("search-section"),r=document.getElementById("export-button");if(d){const o=document.getElementById("warehouse-stock");d.addEventListener("change",function(){o.innerHTML="",i.classList.add("d-none");const s=this.value;n.loading(),n.api("/api/v3/Warehouse/Inventory2023/"+s,"GET",{},{Authorization:"Bearer "+api_key},function(t){if(t.data.length==0){e.toast("No se encontraron resultados","error"),n.loaded(),i.classList.add("d-none");return}t.data.forEach(function(a){o.innerHTML+=c.columnGrid(a)}),l()})})}function l(){i.classList.remove("d-none"),document.querySelectorAll(".save-validated-quantity").forEach(function(s){s.removeEventListener("click",function(){}),s.addEventListener("click",function(){const t=this.getAttribute("data-id"),a=document.getElementById("validated-quantity-"+t).value;if(a==0){e.toast("La cantidad revisada no puede ser 0","error");return}n.loading(),n.api("/api/v3/Warehouse/Inventory2023/"+t,"POST",{quantity:a},{Authorization:"Bearer "+api_key},function(u){const v=document.getElementById("c-"+t);v.innerHTML=c.itemCard(u.data)})})});const o=document.getElementById("search");o&&(o.value="",o.addEventListener("keyup",function(){const s=this.value.trim().toLowerCase();if(s==""){document.querySelectorAll("[data-search]").forEach(function(t){t.classList.remove("d-none")});return}document.querySelectorAll("[data-search]").forEach(function(t){const a=t.getAttribute("data-search").toLowerCase();a.indexOf(s)>-1?(console.log(a+""+s),t.classList.remove("d-none")):t.classList.add("d-none")})})),r.removeEventListener("click",function(){}),r.addEventListener("click",function(){const s=d.value;n.loading(),window.open("/pa/Warehouse/Inventory2023/export/"+s),n.loaded()})}});
