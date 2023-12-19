import{j as ae,g as le,S as H}from"./sweetalert2-374f0b4f.js";var q={exports:{}};(function(w){(function(a){a(["jquery"],function(o){return function(){var n,d,p=0,l={error:"error",info:"info",success:"success",warning:"warning"},g={clear:K,remove:_,error:E,getContainer:h,info:O,options:{},subscribe:A,success:F,version:"2.1.4",warning:P},c;return g;function E(t,e,i){return T({type:l.error,iconClass:f().iconClasses.error,message:t,optionsOverride:i,title:e})}function h(t,e){return t||(t=f()),n=o("#"+t.containerId),n.length||e&&(n=R(t)),n}function O(t,e,i){return T({type:l.info,iconClass:f().iconClasses.info,message:t,optionsOverride:i,title:e})}function A(t){d=t}function F(t,e,i){return T({type:l.success,iconClass:f().iconClasses.success,message:t,optionsOverride:i,title:e})}function P(t,e,i){return T({type:l.warning,iconClass:f().iconClasses.warning,message:t,optionsOverride:i,title:e})}function K(t,e){var i=f();n||h(i),y(t,i,e)||N(i)}function _(t){var e=f();if(n||h(e),t&&o(":focus",t).length===0){b(t);return}n.children().length&&n.remove()}function N(t){for(var e=n.children(),i=e.length-1;i>=0;i--)y(o(e[i]),t)}function y(t,e,i){var m=i&&i.force?i.force:!1;return t&&(m||o(":focus",t).length===0)?(t[e.hideMethod]({duration:e.hideDuration,easing:e.hideEasing,complete:function(){b(t)}}),!0):!1}function R(t){return n=o("<div/>").attr("id",t.containerId).addClass(t.positionClass),n.appendTo(o(t.target)),n}function z(){return{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,closeMethod:!1,closeDuration:!1,closeEasing:!1,closeOnHover:!0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",escapeHtml:!1,target:"body",closeHtml:'<button type="button">&times;</button>',closeClass:"toast-close-button",newestOnTop:!0,preventDuplicates:!1,progressBar:!1,progressClass:"toast-progress",rtl:!1}}function I(t){d&&d(t)}function T(t){var e=f(),i=t.iconClass||e.iconClass;if(typeof t.optionsOverride<"u"&&(e=o.extend(e,t.optionsOverride),i=t.optionsOverride.iconClass||i),te(e,t))return;p++,n=h(e,!0);var m=null,r=o("<div/>"),M=o("<div/>"),k=o("<div/>"),B=o("<div/>"),x=o(e.closeHtml),u={intervalId:null,hideEta:null,maxHideTime:null},v={toastId:p,state:"visible",startTime:new Date,options:e,map:t};return J(),U(),Q(),I(v),e.debug&&console&&console.log(v),r;function S(s){return s==null&&(s=""),s.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function J(){V(),G(),W(),Y(),Z(),ee(),X(),L()}function L(){var s="";switch(t.iconClass){case"toast-success":case"toast-info":s="polite";break;default:s="assertive"}r.attr("aria-live",s)}function Q(){e.closeOnHover&&r.hover(se,ne),!e.onclick&&e.tapToDismiss&&r.click(C),e.closeButton&&x&&x.click(function(s){s.stopPropagation?s.stopPropagation():s.cancelBubble!==void 0&&s.cancelBubble!==!0&&(s.cancelBubble=!0),e.onCloseClick&&e.onCloseClick(s),C(!0)}),e.onclick&&r.click(function(s){e.onclick(s),C()})}function U(){r.hide(),r[e.showMethod]({duration:e.showDuration,easing:e.showEasing,complete:e.onShown}),e.timeOut>0&&(m=setTimeout(C,e.timeOut),u.maxHideTime=parseFloat(e.timeOut),u.hideEta=new Date().getTime()+u.maxHideTime,e.progressBar&&(u.intervalId=setInterval(oe,10)))}function V(){t.iconClass&&r.addClass(e.toastClass).addClass(i)}function X(){e.newestOnTop?n.prepend(r):n.append(r)}function G(){if(t.title){var s=t.title;e.escapeHtml&&(s=S(t.title)),M.append(s).addClass(e.titleClass),r.append(M)}}function W(){if(t.message){var s=t.message;e.escapeHtml&&(s=S(t.message)),k.append(s).addClass(e.messageClass),r.append(k)}}function Y(){e.closeButton&&(x.addClass(e.closeClass).attr("role","button"),r.prepend(x))}function Z(){e.progressBar&&(B.addClass(e.progressClass),r.prepend(B))}function ee(){e.rtl&&r.addClass("rtl")}function te(s,D){if(s.preventDuplicates){if(D.message===c)return!0;c=D.message}return!1}function C(s){var D=s&&e.closeMethod!==!1?e.closeMethod:e.hideMethod,ie=s&&e.closeDuration!==!1?e.closeDuration:e.hideDuration,re=s&&e.closeEasing!==!1?e.closeEasing:e.hideEasing;if(!(o(":focus",r).length&&!s))return clearTimeout(u.intervalId),r[D]({duration:ie,easing:re,complete:function(){b(r),clearTimeout(m),e.onHidden&&v.state!=="hidden"&&e.onHidden(),v.state="hidden",v.endTime=new Date,I(v)}})}function ne(){(e.timeOut>0||e.extendedTimeOut>0)&&(m=setTimeout(C,e.extendedTimeOut),u.maxHideTime=parseFloat(e.extendedTimeOut),u.hideEta=new Date().getTime()+u.maxHideTime)}function se(){clearTimeout(m),u.hideEta=0,r.stop(!0,!0)[e.showMethod]({duration:e.showDuration,easing:e.showEasing})}function oe(){var s=(u.hideEta-new Date().getTime())/u.maxHideTime*100;B.width(s+"%")}}function f(){return o.extend({},z(),g.options)}function b(t){n||(n=h()),!t.is(":visible")&&(t.remove(),t=null,n.children().length===0&&(n.remove(),c=void 0))}}()})})(function(a,o){w.exports?w.exports=o(ae):window.toastr=o(window.jQuery)})})(q);var ue=q.exports;const j=le(ue);class ce{constructor(){j.options={closeButton:!0,positionClass:"toast-top-center",preventDuplicates:!0,progressBar:!0,showDuration:400,hideDuration:1e3,timeOut:7e3,extendedTimeOut:1e3,showEasing:"swing",hideEasing:"linear",showMethod:"slideDown",hideMethod:"slideUp",tapToDismiss:!0}}error(a="",o=null,n=function(){}){H.fire({icon:"error",text:a,timer:o}).then(function(){n()})}success(a="",o=null,n=function(){}){H.fire({icon:"success",text:a,allowOutsideClick:!1,allowEscapeKey:!1,allowEnterKey:!1,showConfirmButton:!1,timer:o}).then(function(){n()})}toast(a="",o="success"){j[o](a)}confirm(a="",o=function(){}){H.fire({icon:"warning",text:a,showCancelButton:!0,confirmButtonText:"Confirmar",cancelButtonText:"Cancelar",reverseButtons:!0}).then(function(n){n.value&&o()})}}class fe{loading(){$(".loading").removeClass("d-none")}loaded(){$(".loading").addClass("d-none")}post(a,o,n=null,d=null,p=!0){let l=this;$.ajax({url:a,method:"post",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:o,dataType:"json",beforeSend:function(){l.loading()}}).done(function(g){n!=null&&n(g),p==!0&&l.loaded()}).fail(function(g,c,E){l.loaded(),d!==null&&d(c)})}api(a,o="post",n={},d={},p=function(){}){const l=this,g=new ce;$.ajax({url:a,method:o,headers:d,data:n,dataType:"json",beforeSend:function(){l.loading()}}).done(function(c){p(c),l.loaded()}).fail(function(c,E,h){const O=c.responseJSON.message||"Error desconocido, recargue la página e intente nuevamente.";g.toast(O,"error"),l.loaded()})}}export{ce as A,fe as E};