﻿/* inview */
(function(d){var p={},e,a,h=document,i=window,f=h.documentElement,j=d.expando;d.event.special.inview={add:function(a){p[a.guid+"-"+this[j]]={data:a,$element:d(this)}},remove:function(a){try{delete p[a.guid+"-"+this[j]]}catch(d){}}};d(i).bind("scroll resize",function(){e=a=null});!f.addEventListener&&f.attachEvent&&f.attachEvent("onfocusin",function(){a=null});setInterval(function(){var k=d(),j,n=0;d.each(p,function(a,b){var c=b.data.selector,d=b.$element;k=k.add(c?d.find(c):d)});if(j=k.length){var b;
if(!(b=e)){var g={height:i.innerHeight,width:i.innerWidth};if(!g.height&&((b=h.compatMode)||!d.support.boxModel))b="CSS1Compat"===b?f:h.body,g={height:b.clientHeight,width:b.clientWidth};b=g}e=b;for(a=a||{top:i.pageYOffset||f.scrollTop||h.body.scrollTop,left:i.pageXOffset||f.scrollLeft||h.body.scrollLeft};n<j;n++)if(d.contains(f,k[n])){b=d(k[n]);var l=b.height(),m=b.width(),c=b.offset(),g=b.data("inview");if(!a||!e)break;c.top+l>a.top&&c.top<a.top+e.height&&c.left+m>a.left&&c.left<a.left+e.width?
(m=a.left>c.left?"right":a.left+e.width<c.left+m?"left":"both",l=a.top>c.top?"bottom":a.top+e.height<c.top+l?"top":"both",c=m+"-"+l,(!g||g!==c)&&b.data("inview",c).trigger("inview",[!0,m,l])):g&&b.data("inview",!1).trigger("inview",[!1])}}},250)})(jQuery);
/* tags */
jQuery.fn.tag=function(h){h=jQuery.extend({seperator:",",unique:!0,addOnEnter:!0,style:{list:"taglist",item:"tag",input:"input",remove:"delete"}},h),jQuery(this).each(function(){""!=(seperator=jQuery(this).attr("data-seperator"))&&(h.seperator=seperator);function r(e){var t=e.replace(/^\s+|\s+$/g,"");if(""!=t){var r=jQuery("<li/>").addClass(h.style.item),s=jQuery("<span/>"),i=jQuery("<span/>").html("[X]"),a=jQuery("<a/>",{tabindex:"-1"}).addClass(h.style.remove).append(i).click(function(){jQuery(this).closest("li").remove(),l()});if(!(h.unique&&-1<jQuery.inArray(t,u)))return u.push(t),s.html(t),r.append(s).append(" ").append(a),r}}function s(e){var t;""!=jQuery(e).val()&&((t=r(jQuery(e).val().replace(/<[^>]*>?/gm,"")))?(jQuery(e).closest("li").before(t),jQuery(e).val(jQuery(e).val().replace(h.seperator,"")),jQuery(e).width(8).val("").focus()):(jQuery(e).val(""),jQuery(e).width(8)),l(),n.html(""))}var l=function(){var e=[];jQuery("li."+h.style.item+" > span",i).each(function(){e.push(jQuery(this).html().replace(/<[^>]*>?/gm,""))}),u=e,jQuery(t).val(e.join(h.seperator))},t=jQuery(this);if(t.is(":input")){t.hide();var i=jQuery("<ul/>").addClass(h.style.list).click(function(){jQuery(this).find("input").focus()}),e=jQuery("<input/>",{type:"text"}),a=t.val().replace(/<[^>]*>?/gm,"").split(h.seperator),u=[];for(index in a){var y=r(a[index]);i.append(y)}l(),t.after(i);var d=jQuery("<li/>").addClass(h.style.input),n=jQuery("<span/>");n.hide(),d.append(e),e.after(n),i.append(d);function o(e){n.html(jQuery(e).val().replace(/<[^>]*>?/gm,"").replace(/\s/g,"&nbsp;"));var t=""==jQuery(e).val()?8:10;jQuery(e).width(n.width()+t)}e.bind("keyup",function(){o(this)}).bind("keydown",function(e){o(this);var t=e.keyCode||e.which;if(""==jQuery(this).val()&&(8==t||46==t)){switch(jQuery(this).width(""!=jQuery(this).val()?n.width()+5:8),t){case 8:jQuery(this).closest("li").prev().is(".ready-to-delete")?(jQuery(".ready-to-delete").removeClass("ready-to-delete"),jQuery(this).closest("li").prev().remove()):(jQuery(".ready-to-delete").removeClass("ready-to-delete"),jQuery(this).closest("li").prev().addClass("ready-to-delete"));break;case 46:jQuery(this).closest("li").next().is(".ready-to-delete")?(jQuery(".ready-to-delete").removeClass("ready-to-delete"),jQuery(this).closest("li").next().remove()):(jQuery(".ready-to-delete").removeClass("ready-to-delete"),jQuery(this).closest("li").next().addClass("ready-to-delete"))}return l(),e.preventDefault(),!1}jQuery(".ready-to-delete").removeClass("ready-to-delete"),""==jQuery(this).val()&&(37!=t&&38!=t||(jQuery(this).width(""!=jQuery(this).val()?n.width()+5:8),jQuery(this).closest("li").prev().before(jQuery(this).closest("li")),jQuery(this).focus()),39!=t&&40!=t||(jQuery(this).width(""!=jQuery(this).val()?n.width()+5:8),jQuery(this).closest("li").next().after(jQuery(this).closest("li")),jQuery(this).focus()))}).bind("keypress",function(e){o(this);var t=e.keyCode||e.which;return h.seperator==String.fromCharCode(t)||h.seperator==t||h.addOnEnter&&13==t?(s(this),e.preventDefault(),!1):void 0}).bind("blur",function(){s(this),jQuery(this).closest("ul").append(jQuery(this).closest("li"))})}})};