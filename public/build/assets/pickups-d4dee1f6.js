import{E as w}from"./Events-89d9745c.js";import{T as k,S as g}from"./Status-92ab773d.js";import{B as b,A as h}from"./Alerts-6102510b.js";import{M as _,V as y}from"./Validates-7d06d876.js";import"./sweetalert2-985235ef.js";$(function(){const a=new w,c=new k,s=new b,n=new _("logistic-new-pickup-modal"),r=new y,i=new h,l=new g;p();function p(){a.post("/api/v1/Logistic/Pickup",{api_key,_method:"GET"},function(e){e.code==200&&c.init("pickups-table",e.data.Pickups,d(),m(),function(){c.sortByColumn("prices-lists-table",1,"asc")},function(){})})}function d(){return[{data:"Id",render:function(t,u,o){return`
                    <a class="fs-5 fw-bold" href="/Logistica/Recoleccion/`+t+`">
                        <i class="bi bi-eye"></i>
                    </a>`},className:"text-center"},{data:"Id"},{data:"BranchName"},{data:"UserName"},{data:"StatusName",render:function(t,u,o){return l.render(o.StatusId)},className:"text-center"},{data:"created_at",render:function(t,u,o){return moment(t).format("DD/MM/YYYY HH:mm")}}]}function m(){return[{targets:["_all"],className:"align-middle"}]}s.initClic("#logistic-new-pickup-form-button",function(e){$("#pickup-branch option:first").prop("selected",!0),n.show(),f()});function f(){r.form_reset("#new-pickup-form"),s.initClic("#logistic-new-pickup-button",function(e){r.form("#new-pickup-form")&&a.post("/api/v1/Logistic/Pickup",{api_key,_method:"PUT",branch:$("#pickup-branch").val()},function(t){t.code==200?i.success(t.message,4e3,function(){n.hide(),window.location.href="/Logistica/Recoleccion/"+t.data.Pickup.Id}):i.error(t.message)},function(){i.error("No se pudo crear el registro de recolección. Recargue la página e intente nuevamente.",4e3,function(){n.hide()})})})}});
