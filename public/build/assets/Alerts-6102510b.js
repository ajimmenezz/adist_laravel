import{s as l}from"./sweetalert2-985235ef.js";class i{initClic(t,e=s=>{}){$(t).off("click").on("click",function(){e($(this))})}}class c{error(t="",e=null,s=function(){}){l.fire({icon:"error",text:t,timer:e}).then(function(){s()})}success(t="",e=null,s=function(){}){l.fire({icon:"success",text:t,allowOutsideClick:!1,allowEscapeKey:!1,allowEnterKey:!1,showConfirmButton:!1,timer:e}).then(function(){s()})}}export{c as A,i as B};
