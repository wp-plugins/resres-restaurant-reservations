!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){function b(a){if(a.minTime&&(a.minTime=r(a.minTime)),a.maxTime&&(a.maxTime=r(a.maxTime)),a.durationTime&&"function"!=typeof a.durationTime&&(a.durationTime=r(a.durationTime)),a.disableTimeRanges.length>0){for(var b in a.disableTimeRanges)a.disableTimeRanges[b]=[r(a.disableTimeRanges[b][0]),r(a.disableTimeRanges[b][1])];a.disableTimeRanges=a.disableTimeRanges.sort(function(a,b){return a[0]-b[0]});for(var b=a.disableTimeRanges.length-1;b>0;b--)a.disableTimeRanges[b][0]<=a.disableTimeRanges[b-1][1]&&(a.disableTimeRanges[b-1]=[Math.min(a.disableTimeRanges[b][0],a.disableTimeRanges[b-1][0]),Math.max(a.disableTimeRanges[b][1],a.disableTimeRanges[b-1][1])],a.disableTimeRanges.splice(b,1))}return a}function c(b){var c=b.data("timepicker-settings"),e=b.data("timepicker-list");if(e&&e.length&&(e.remove(),b.data("timepicker-list",!1)),c.useSelect){e=a("<select />",{"class":"ui-timepicker-select"});var f=e}else{e=a("<ul />",{"class":"ui-timepicker-list"});var f=a("<div />",{"class":"ui-timepicker-wrapper",tabindex:-1});f.css({display:"none",position:"absolute"}).append(e)}if(c.noneOption){var h=c.useSelect?"Time...":"None",j="string"==typeof c.noneOption?c.noneOption:h;c.useSelect?e.append(a('<option value="">'+j+"</option>")):e.append(a("<li>"+j+"</li>"))}c.className&&f.addClass(c.className),null===c.minTime&&null===c.durationTime||!c.showDuration||f.addClass("ui-timepicker-with-duration");var k=c.minTime;"function"==typeof c.durationTime?k=r(c.durationTime()):null!==c.durationTime&&(k=c.durationTime);var m=null!==c.minTime?c.minTime:0,n=null!==c.maxTime?c.maxTime:m+u-1;m>=n&&(n+=u),n===u-1&&-1!==c.timeFormat.indexOf("H")&&(n=u);for(var s=c.disableTimeRanges,t=0,v=s.length,w=m;n>=w;w+=60*c.step){var y=w,z=q(y,c.timeFormat);if(c.useSelect){var A=a("<option />",{value:z});A.text(z)}else{var A=a("<li />");A.data("time",86400>=y?y:y%86400),A.text(z)}if((null!==c.minTime||null!==c.durationTime)&&c.showDuration){var B=p(w-k);if(c.useSelect)A.text(A.text()+" ("+B+")");else{var C=a("<span />",{"class":"ui-timepicker-duration"});C.text(" ("+B+")"),A.append(C)}}v>t&&(y>=s[t][1]&&(t+=1),s[t]&&y>=s[t][0]&&y<s[t][1]&&(c.useSelect?A.prop("disabled",!0):A.addClass("ui-timepicker-disabled"))),e.append(A)}if(f.data("timepicker-input",b),b.data("timepicker-list",f),c.useSelect)e.val(d(b.val(),c)),e.on("focus",function(){a(this).data("timepicker-input").trigger("showTimepicker")}),e.on("blur",function(){a(this).data("timepicker-input").trigger("hideTimepicker")}),e.on("change",function(){l(b,a(this).val(),"select")}),b.hide().after(e);else{var D=c.appendTo;"string"==typeof D?D=a(D):"function"==typeof D&&(D=D(b)),D.append(f),i(b,e),e.on("click","li",function(){b.off("focus.timepicker"),b.on("focus.timepicker-ie-hack",function(){b.off("focus.timepicker-ie-hack"),b.on("focus.timepicker",x.show)}),g(b)||b[0].focus(),e.find("li").removeClass("ui-timepicker-selected"),a(this).addClass("ui-timepicker-selected"),o(b)&&(b.trigger("hideTimepicker"),f.hide())})}}function d(b,c){if(a.isNumeric(b)||(b=r(b)),null===b)return null;var d=60*c.step;return q(Math.round(b/d)*d,c.timeFormat)}function e(){return new Date(1970,1,1,0,0,0)}function f(b){var c=a(b.target),d=c.closest(".ui-timepicker-input");0===d.length&&0===c.closest(".ui-timepicker-wrapper").length&&(x.hide(),a(document).unbind(".ui-timepicker"))}function g(a){var b=a.data("timepicker-settings");return(window.navigator.msMaxTouchPoints||"ontouchstart"in document)&&b.disableTouchKeyboard}function h(b,c,d){if(!d&&0!==d)return!1;var e=b.data("timepicker-settings"),f=!1,g=30*e.step;return c.find("li").each(function(b,c){var e=a(c),h=e.data("time")-d;return Math.abs(h)<g||h==g?(f=e,!1):void 0}),f}function i(a,b){b.find("li").removeClass("ui-timepicker-selected");var c=r(k(a));if(null!==c){var d=h(a,b,c);if(d){var e=d.offset().top-b.offset().top;(e+d.outerHeight()>b.outerHeight()||0>e)&&b.scrollTop(b.scrollTop()+d.position().top-d.outerHeight()),d.addClass("ui-timepicker-selected")}}}function j(){if(""!==this.value){var b=a(this),c=b.data("timepicker-list");if(!c||!c.is(":visible")){var d=r(this.value);if(null===d)return b.trigger("timeFormatError"),void 0;var e=b.data("timepicker-settings"),f=!1;if(null!==e.minTime&&d<e.minTime?f=!0:null!==e.maxTime&&d>e.maxTime&&(f=!0),a.each(e.disableTimeRanges,function(){return d>=this[0]&&d<this[1]?(f=!0,!1):void 0}),e.forceRoundTime){var g=d%(60*e.step);g>=30*e.step?d+=60*e.step-g:d-=g}var h=q(d,e.timeFormat);f?l(b,h,"error")&&b.trigger("timeRangeError"):l(b,h)}}}function k(a){return a.is("input")?a.val():a.data("ui-timepicker-value")}function l(a,b,c){if(a.is("input")){a.val(b);var e=a.data("timepicker-settings");e.useSelect&&a.data("timepicker-list").val(d(b,e))}return a.data("ui-timepicker-value")!=b?(a.data("ui-timepicker-value",b),"select"==c?a.trigger("selectTime").trigger("changeTime").trigger("change"):"error"!=c&&a.trigger("changeTime"),!0):(a.trigger("selectTime"),!1)}function m(b){var c=a(this),d=c.data("timepicker-list");if(!d||!d.is(":visible")){if(40!=b.keyCode)return!0;g(c)||c.focus()}switch(b.keyCode){case 13:return o(c)&&x.hide.apply(this),b.preventDefault(),!1;case 38:var e=d.find(".ui-timepicker-selected");return e.length?e.is(":first-child")||(e.removeClass("ui-timepicker-selected"),e.prev().addClass("ui-timepicker-selected"),e.prev().position().top<e.outerHeight()&&d.scrollTop(d.scrollTop()-e.outerHeight())):(d.find("li").each(function(b,c){return a(c).position().top>0?(e=a(c),!1):void 0}),e.addClass("ui-timepicker-selected")),!1;case 40:return e=d.find(".ui-timepicker-selected"),0===e.length?(d.find("li").each(function(b,c){return a(c).position().top>0?(e=a(c),!1):void 0}),e.addClass("ui-timepicker-selected")):e.is(":last-child")||(e.removeClass("ui-timepicker-selected"),e.next().addClass("ui-timepicker-selected"),e.next().position().top+2*e.outerHeight()>d.outerHeight()&&d.scrollTop(d.scrollTop()+e.outerHeight())),!1;case 27:d.find("li").removeClass("ui-timepicker-selected"),x.hide();break;case 9:x.hide();break;default:return!0}}function n(b){var c=a(this),d=c.data("timepicker-list");if(!d||!d.is(":visible"))return!0;if(!c.data("timepicker-settings").typeaheadHighlight)return d.find("li").removeClass("ui-timepicker-selected"),!0;switch(b.keyCode){case 96:case 97:case 98:case 99:case 100:case 101:case 102:case 103:case 104:case 105:case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 65:case 77:case 80:case 186:case 8:case 46:i(c,d);break;default:return}}function o(a){var b=a.data("timepicker-settings"),c=a.data("timepicker-list"),d=null,e=c.find(".ui-timepicker-selected");if(e.hasClass("ui-timepicker-disabled"))return!1;if(e.length?d=e.data("time"):k(a)&&(d=r(k(a)),i(a,c)),null!==d){var f=q(d,b.timeFormat);l(a,f,"select")}return!0}function p(a){var b,c=Math.round(a/60);if(Math.abs(c)<60)b=[c,w.mins];else if(60==c)b=["1",w.hr];else{var d=(c/60).toFixed(1);"."!=w.decimal&&(d=d.replace(".",w.decimal)),b=[d,w.hrs]}return b.join(" ")}function q(a,b){if(null!==a){var c=new Date(t.valueOf()+1e3*a);if(!isNaN(c.getTime())){for(var d,e,f="",g=0;g<b.length;g++)switch(e=b.charAt(g)){case"a":f+=c.getHours()>11?"pm":"am";break;case"A":f+=c.getHours()>11?"PM":"AM";break;case"g":d=c.getHours()%12,f+=0===d?"12":d;break;case"G":f+=c.getHours();break;case"h":d=c.getHours()%12,0!==d&&10>d&&(d="0"+d),f+=0===d?"12":d;break;case"H":d=c.getHours(),a===u&&(d=24),f+=d>9?d:"0"+d;break;case"i":var h=c.getMinutes();f+=h>9?h:"0"+h;break;case"s":a=c.getSeconds(),f+=a>9?a:"0"+a;break;default:f+=e}return f}}}function r(a){if(""===a)return null;if(!a||a+0==a)return a;"object"==typeof a&&(a=a.getHours()+":"+s(a.getMinutes())+":"+s(a.getSeconds())),a=a.toLowerCase(),new Date(0);var b;if(-1===a.indexOf(":")?(b=a.match(/^([0-9]):?([0-5][0-9])?:?([0-5][0-9])?\s*([pa]?)m?$/),b||(b=a.match(/^([0-2][0-9]):?([0-5][0-9])?:?([0-5][0-9])?\s*([pa]?)m?$/))):b=a.match(/^(\d{1,2})(?::([0-5][0-9]))?(?::([0-5][0-9]))?\s*([pa]?)m?$/),!b)return null;var c,d=parseInt(1*b[1],10);c=b[4]?12==d?"p"==b[4]?12:0:d+("p"==b[4]?12:0):d;var e=1*b[2]||0,f=1*b[3]||0;return 3600*c+60*e+f}function s(a){return("0"+a).slice(-2)}var t=e(),u=86400,v={className:null,minTime:null,maxTime:null,durationTime:null,step:30,showDuration:!1,timeFormat:"g:ia",scrollDefaultNow:!1,scrollDefaultTime:!1,selectOnBlur:!1,disableTouchKeyboard:!1,forceRoundTime:!1,appendTo:"body",disableTimeRanges:[],closeOnWindowScroll:!1,typeaheadHighlight:!0,noneOption:!1},w={decimal:".",mins:"mins",hr:"hr",hrs:"hrs"},x={init:function(d){return this.each(function(){var e=a(this),f=[];for(key in v)e.data(key)&&(f[key]=e.data(key));var g=a.extend({},v,f,d);g.lang&&(w=a.extend(w,g.lang)),g=b(g),e.data("timepicker-settings",g),e.addClass("ui-timepicker-input"),g.useSelect?c(e):(e.prop("autocomplete","off"),e.on("click.timepicker focus.timepicker",x.show),e.on("change.timepicker",j),e.on("keydown.timepicker",m),e.on("keyup.timepicker",n),j.call(e.get(0)))})},show:function(b){b&&b.preventDefault();var d=a(this),e=d.data("timepicker-settings");if(e.useSelect)return d.data("timepicker-list").focus(),void 0;g(d)&&d.blur();var i=d.data("timepicker-list");if(!d.prop("readonly")&&(i&&0!==i.length&&"function"!=typeof e.durationTime||(c(d),i=d.data("timepicker-list")),!i.is(":visible"))){x.hide(),i.show(),d.offset().top+d.outerHeight(!0)+i.outerHeight()>a(window).height()+a(window).scrollTop()?i.offset({left:d.offset().left+parseInt(i.css("marginLeft").replace("px",""),10),top:d.offset().top-i.outerHeight()+parseInt(i.css("marginTop").replace("px",""),10)}):i.offset({left:d.offset().left+parseInt(i.css("marginLeft").replace("px",""),10),top:d.offset().top+d.outerHeight()+parseInt(i.css("marginTop").replace("px",""),10)});var j=i.find(".ui-timepicker-selected");if(j.length||(k(d)?j=h(d,i,r(k(d))):e.scrollDefaultNow?j=h(d,i,r(new Date)):e.scrollDefaultTime!==!1&&(j=h(d,i,r(e.scrollDefaultTime)))),j&&j.length){var l=i.scrollTop()+j.position().top-j.outerHeight();i.scrollTop(l)}else i.scrollTop(0);return a(document).on("touchstart.ui-timepicker mousedown.ui-timepicker",f),e.closeOnWindowScroll&&a(document).on("scroll.ui-timepicker",f),d.trigger("showTimepicker"),this}},hide:function(){var b=a(this),c=b.data("timepicker-settings");return c&&c.useSelect&&b.blur(),a(".ui-timepicker-wrapper:visible").each(function(){var b=a(this),c=b.data("timepicker-input"),d=c.data("timepicker-settings");d&&d.selectOnBlur&&o(c),b.hide(),c.trigger("hideTimepicker")}),this},option:function(d,e){var f=this,g=f.data("timepicker-settings"),h=f.data("timepicker-list");if("object"==typeof d)g=a.extend(g,d);else if("string"==typeof d&&"undefined"!=typeof e)g[d]=e;else if("string"==typeof d)return g[d];return g=b(g),f.data("timepicker-settings",g),h&&(h.remove(),f.data("timepicker-list",!1)),g.useSelect&&c(f),this},getSecondsFromMidnight:function(){return r(k(this))},getTime:function(a){var b=this,c=k(b);return c?(a||(a=new Date),a.setHours(0,0,0,0),new Date(a.valueOf()+1e3*r(c))):null},setTime:function(a){var b=this,c=q(r(a),b.data("timepicker-settings").timeFormat);return l(b,c),b.data("timepicker-list")&&i(b,b.data("timepicker-list")),this},remove:function(){var a=this;if(a.hasClass("ui-timepicker-input"))return a.removeAttr("autocomplete","off"),a.removeClass("ui-timepicker-input"),a.removeData("timepicker-settings"),a.off(".timepicker"),a.data("timepicker-list")&&a.data("timepicker-list").remove(),a.removeData("timepicker-list"),this}};a.fn.timepicker=function(b){return this.length?x[b]?this.hasClass("ui-timepicker-input")?x[b].apply(this,Array.prototype.slice.call(arguments,1)):this:"object"!=typeof b&&b?(a.error("Method "+b+" does not exist on jQuery.timepicker"),void 0):x.init.apply(this,arguments):this}});