!function($,t,e){function i(){var t=this;t.id=null,t.busy=!1,t.start=function(e,i){t.busy||(t.stop(),t.id=setTimeout(function(){e(),t.id=null,t.busy=!1},i),t.busy=!0)},t.stop=function(){null!==t.id&&(clearTimeout(t.id),t.id=null,t.busy=!1)}}function o(e,o,n){var a=this;a.id=n,a.table=e,a.options=o,a.breakpoints=[],a.breakpointNames="",a.columns={},a.plugins=t.footable.plugins.load(a);var r=a.options,s=r.classes,l=r.events,d=r.triggers,u=0;return a.timers={resize:new i,register:function(t){return a.timers[t]=new i,a.timers[t]}},a.init=function(){var e=$(t),i=$(a.table);if(t.footable.plugins.init(a),i.hasClass(s.loaded))return void a.raise(l.alreadyInitialized);a.raise(l.initializing),i.addClass(s.loading),i.find(r.columnDataSelector).each(function(){var t=a.getColumnData(this);a.columns[t.index]=t});for(var o in r.breakpoints)a.breakpoints.push({name:o,width:r.breakpoints[o]}),a.breakpointNames+=o+" ";a.breakpoints.sort(function(t,e){return t.width-e.width}),i.unbind(d.initialize).bind(d.initialize,function(){i.removeData("footable_info"),i.data("breakpoint",""),i.trigger(d.resize),i.removeClass(s.loading),i.addClass(s.loaded).addClass(s.main),a.raise(l.initialized)}).unbind(d.redraw).bind(d.redraw,function(){a.redraw()}).unbind(d.resize).bind(d.resize,function(){a.resize()}).unbind(d.expandFirstRow).bind(d.expandFirstRow,function(){i.find(r.toggleSelector).first().not("."+s.detailShow).trigger(d.toggleRow)}).unbind(d.expandAll).bind(d.expandAll,function(){i.find(r.toggleSelector).not("."+s.detailShow).trigger(d.toggleRow)}).unbind(d.collapseAll).bind(d.collapseAll,function(){i.find("."+s.detailShow).trigger(d.toggleRow)}),i.trigger(d.initialize),e.bind("resize.footable",function(){a.timers.resize.stop(),a.timers.resize.start(function(){a.raise(d.resize)},r.delay)})},a.addRowToggle=function(){if(r.addRowToggle){var t=$(a.table),e=!1;t.find("span."+s.toggle).remove();for(var i in a.columns){var o=a.columns[i];if(o.toggle){e=!0;var n="> tbody > tr:not(."+s.detail+",."+s.disabled+") > td:nth-child("+(parseInt(o.index,10)+1)+"),> tbody > tr:not(."+s.detail+",."+s.disabled+") > th:nth-child("+(parseInt(o.index,10)+1)+")";return void t.find(n).not("."+s.detailCell).prepend($(r.toggleHTMLElement).addClass(s.toggle))}}e||t.find("> tbody > tr:not(."+s.detail+",."+s.disabled+") > td:first-child").add("> tbody > tr:not(."+s.detail+",."+s.disabled+") > th:first-child").not("."+s.detailCell).prepend($(r.toggleHTMLElement).addClass(s.toggle))}},a.setColumnClasses=function(){var t=$(a.table);for(var e in a.columns){var i=a.columns[e];if(null!==i.className){var o="",n=!0;$.each(i.matches,function(t,e){n||(o+=", "),o+="> tbody > tr:not(."+s.detail+") > td:nth-child("+(parseInt(e,10)+1)+")",n=!1}),t.find(o).not("."+s.detailCell).addClass(i.className)}}},a.bindToggleSelectors=function(){var t=$(a.table);a.hasAnyBreakpointColumn()&&(t.find(r.toggleSelector).unbind(d.toggleRow).bind(d.toggleRow,function(t){var e=$(this).is("tr")?$(this):$(this).parents("tr:first");a.toggleDetail(e)}),t.find(r.toggleSelector).unbind("click.footable").bind("click.footable",function(e){t.is(".breakpoint")&&$(e.target).is("td,th,."+s.toggle)&&$(this).trigger(d.toggleRow)}))},a.parse=function(t,e){var i=r.parsers[e.type]||r.parsers.alpha;return i(t)},a.getColumnData=function(t){var e=$(t),i=e.data("hide"),o=e.index();i=i||"",i=jQuery.map(i.split(","),function(t){return jQuery.trim(t)});var n={index:o,hide:{},type:e.data("type")||"alpha",name:e.data("name")||$.trim(e.text()),ignore:e.data("ignore")||!1,toggle:e.data("toggle")||!1,className:e.data("class")||null,matches:[],names:{},group:e.data("group")||null,groupName:null,isEditable:e.data("editable")};if(null!==n.group){var s=$(a.table).find('> thead > tr.footable-group-row > th[data-group="'+n.group+'"], > thead > tr.footable-group-row > td[data-group="'+n.group+'"]').first();n.groupName=a.parse(s,{type:"alpha"})}var d=parseInt(e.prev().attr("colspan")||0,10);u+=d>1?d-1:0;var c=parseInt(e.attr("colspan")||0,10),h=n.index+u;if(c>1){var p=e.data("names");p=p||"",p=p.split(",");for(var f=0;c>f;f++)n.matches.push(f+h),f<p.length&&(n.names[f+h]=p[f])}else n.matches.push(h);n.hide["default"]="all"===e.data("hide")||$.inArray("default",i)>=0;var g=!1;for(var b in r.breakpoints)n.hide[b]="all"===e.data("hide")||$.inArray(b,i)>=0,g=g||n.hide[b];n.hasBreakpoint=g;var w=a.raise(l.columnData,{column:{data:n,th:t}});return w.column.data},a.getViewportWidth=function(){return window.innerWidth||(document.body?document.body.offsetWidth:0)},a.calculateWidth=function(t,e){return jQuery.isFunction(r.calculateWidthOverride)?r.calculateWidthOverride(t,e):(e.viewportWidth<e.width&&(e.width=e.viewportWidth),e.parentWidth<e.width&&(e.width=e.parentWidth),e)},a.hasBreakpointColumn=function(t){for(var e in a.columns)if(a.columns[e].hide[t]){if(a.columns[e].ignore)continue;return!0}return!1},a.hasAnyBreakpointColumn=function(){for(var t in a.columns)if(a.columns[t].hasBreakpoint)return!0;return!1},a.resize=function(){var t=$(a.table);if(t.is(":visible")){if(!a.hasAnyBreakpointColumn())return void t.trigger(d.redraw);var e={width:t.width(),viewportWidth:a.getViewportWidth(),parentWidth:t.parent().width()};e=a.calculateWidth(t,e);var i=t.data("footable_info");if(t.data("footable_info",e),a.raise(l.resizing,{old:i,info:e}),!i||i&&i.width&&i.width!==e.width){for(var o=null,n,r=0;r<a.breakpoints.length;r++)if(n=a.breakpoints[r],n&&n.width&&e.width<=n.width){o=n;break}var s=null===o?"default":o.name,u=a.hasBreakpointColumn(s),c=t.data("breakpoint");t.data("breakpoint",s).removeClass("default breakpoint").removeClass(a.breakpointNames).addClass(s+(u?" breakpoint":"")),s!==c&&(t.trigger(d.redraw),a.raise(l.breakpoint,{breakpoint:s,info:e}))}a.raise(l.resized,{old:i,info:e})}},a.redraw=function(){a.addRowToggle(),a.bindToggleSelectors(),a.setColumnClasses();var t=$(a.table),e=t.data("breakpoint"),i=a.hasBreakpointColumn(e);t.find("> tbody > tr:not(."+s.detail+")").data("detail_created",!1).end().find("> thead > tr:last-child > th").each(function(){var i=a.columns[$(this).index()],o="",n=!0;$.each(i.matches,function(t,e){n||(o+=", ");var i=e+1;o+="> tbody > tr:not(."+s.detail+") > td:nth-child("+i+")",o+=", > tfoot > tr:not(."+s.detail+") > td:nth-child("+i+")",o+=", > colgroup > col:nth-child("+i+")",n=!1}),o+=', > thead > tr[data-group-row="true"] > th[data-group="'+i.group+'"]';var r=t.find(o).add(this);if(""!==e&&(i.hide[e]===!1?r.addClass("footable-visible").show():r.removeClass("footable-visible").hide()),1===t.find("> thead > tr.footable-group-row").length){var l=t.find('> thead > tr:last-child > th[data-group="'+i.group+'"]:visible, > thead > tr:last-child > th[data-group="'+i.group+'"]:visible'),d=t.find('> thead > tr.footable-group-row > th[data-group="'+i.group+'"], > thead > tr.footable-group-row > td[data-group="'+i.group+'"]'),u=0;$.each(l,function(){u+=parseInt($(this).attr("colspan")||1,10)}),u>0?d.attr("colspan",u).show():d.hide()}}).end().find("> tbody > tr."+s.detailShow).each(function(){a.createOrUpdateDetailRow(this)}),t.find("[data-bind-name]").each(function(){a.toggleInput(this)}),t.find("> tbody > tr."+s.detailShow+":visible").each(function(){var t=$(this).next();t.hasClass(s.detail)&&(i?t.show():t.hide())}),t.find("> thead > tr > th.footable-last-column, > tbody > tr > td.footable-last-column").removeClass("footable-last-column"),t.find("> thead > tr > th.footable-first-column, > tbody > tr > td.footable-first-column").removeClass("footable-first-column"),t.find("> thead > tr, > tbody > tr").find("> th.footable-visible:last, > td.footable-visible:last").addClass("footable-last-column").end().find("> th.footable-visible:first, > td.footable-visible:first").addClass("footable-first-column"),a.raise(l.redrawn)},a.toggleDetail=function(t){var e=t.jquery?t:$(t),i=e.next();e.hasClass(s.detailShow)?(e.removeClass(s.detailShow),i.hasClass(s.detail)&&i.hide(),a.raise(l.rowCollapsed,{row:e[0]})):(a.createOrUpdateDetailRow(e[0]),e.addClass(s.detailShow).next().show(),a.raise(l.rowExpanded,{row:e[0]}))},a.removeRow=function(t){var e=t.jquery?t:$(t);e.hasClass(s.detail)&&(e=e.prev());var i=e.next();e.data("detail_created")===!0&&i.remove(),e.remove(),a.raise(l.rowRemoved)},a.appendRow=function(t){var e=t.jquery?t:$(t);$(a.table).find("tbody").append(e),a.redraw()},a.getColumnFromTdIndex=function(t){var e=null;for(var i in a.columns)if($.inArray(t,a.columns[i].matches)>=0){e=a.columns[i];break}return e},a.createOrUpdateDetailRow=function(t){var e=$(t),i=e.next(),o,n=[];if(e.data("detail_created")===!0)return!0;if(e.is(":hidden"))return!1;if(a.raise(l.rowDetailUpdating,{row:e,detail:i}),e.find("> td:hidden").each(function(){var t=$(this).index(),e=a.getColumnFromTdIndex(t),i=e.name;if(e.ignore===!0)return!0;t in e.names&&(i=e.names[t]);var o=$(this).attr("data-bind-name");if(null!=o&&$(this).is(":empty")){var r=$("."+s.detailInnerValue+'[data-bind-value="'+o+'"]');$(this).html($(r).contents().detach())}var l;return e.isEditable!==!1&&(e.isEditable||$(this).find(":input").length>0)&&(null==o&&(o="bind-"+$.now()+"-"+t,$(this).attr("data-bind-name",o)),l=$(this).contents().detach()),l||(l=$(this).contents().clone(!0,!0)),n.push({name:i,value:a.parse(this,e),display:l,group:e.group,groupName:e.groupName,bindName:o}),!0}),0===n.length)return!1;var d=e.find("> td:visible").length,u=i.hasClass(s.detail);return u||(i=$('<tr class="'+s.detail+'"><td class="'+s.detailCell+'"><div class="'+s.detailInner+'"></div></td></tr>'),e.after(i)),i.find("> td:first").attr("colspan",d),o=i.find("."+s.detailInner).empty(),r.createDetail(o,n,r.createGroupedDetail,r.detailSeparator,s),e.data("detail_created",!0),a.raise(l.rowDetailUpdated,{row:e,detail:i}),!u},a.raise=function(t,e){a.options.debug===!0&&$.isFunction(a.options.log)&&a.options.log(t,"event"),e=e||{};var i={ft:a};$.extend(!0,i,e);var o=$.Event(t,i);return o.ft||$.extend(!0,o,i),$(a.table).trigger(o),o},a.reset=function(){var t=$(a.table);t.removeData("footable_info").data("breakpoint","").removeClass(s.loading).removeClass(s.loaded),t.find(r.toggleSelector).unbind(d.toggleRow).unbind("click.footable"),t.find("> tbody > tr").removeClass(s.detailShow),t.find("> tbody > tr."+s.detail).remove(),a.raise(l.reset)},a.toggleInput=function(t){var e=$(t).attr("data-bind-name");if(null!=e){var i=$("."+s.detailInnerValue+'[data-bind-value="'+e+'"]');null!=i&&($(t).is(":visible")?$(i).is(":empty")||$(t).html($(i).contents().detach()):$(t).is(":empty")||$(i).html($(t).contents().detach()))}},a.init(),a}t.footable={options:{delay:100,breakpoints:{phone:700,tablet:1024},parsers:{alpha:function(t){return $(t).data("value")||$.trim($(t).text())},numeric:function(t){var e=$(t).data("value")||$(t).text().replace(/[^0-9.\-]/g,"");return e=parseFloat(e),isNaN(e)&&(e=0),e}},addRowToggle:!0,calculateWidthOverride:null,toggleSelector:" > tbody > tr:not(.footable-row-detail)",columnDataSelector:"> thead > tr:last-child > th, > thead > tr:last-child > td",detailSeparator:":",toggleHTMLElement:"<span />",createGroupedDetail:function(t){for(var e={_none:{name:null,data:[]}},i=0;i<t.length;i++){var o=t[i].group;null!==o?(o in e||(e[o]={name:t[i].groupName||t[i].group,data:[]}),e[o].data.push(t[i])):e._none.data.push(t[i])}return e},createDetail:function(t,e,i,o,n){var a=i(e);for(var r in a)if(0!==a[r].data.length){"_none"!==r&&t.append('<div class="'+n.detailInnerGroup+'">'+a[r].name+"</div>");for(var s=0;s<a[r].data.length;s++){var l=a[r].data[s].name?o:"";t.append($("<div></div>").addClass(n.detailInnerRow).append($("<div></div>").addClass(n.detailInnerName).append(a[r].data[s].name+l)).append($("<div></div>").addClass(n.detailInnerValue).attr("data-bind-value",a[r].data[s].bindName).append(a[r].data[s].display)))}}},classes:{main:"footable",loading:"footable-loading",loaded:"footable-loaded",toggle:"footable-toggle",disabled:"footable-disabled",detail:"footable-row-detail",detailCell:"footable-row-detail-cell",detailInner:"footable-row-detail-inner",detailInnerRow:"footable-row-detail-row",detailInnerGroup:"footable-row-detail-group",detailInnerName:"footable-row-detail-name",detailInnerValue:"footable-row-detail-value",detailShow:"footable-detail-show"},triggers:{initialize:"footable_initialize",resize:"footable_resize",redraw:"footable_redraw",toggleRow:"footable_toggle_row",expandFirstRow:"footable_expand_first_row",expandAll:"footable_expand_all",collapseAll:"footable_collapse_all"},events:{alreadyInitialized:"footable_already_initialized",initializing:"footable_initializing",initialized:"footable_initialized",resizing:"footable_resizing",resized:"footable_resized",redrawn:"footable_redrawn",breakpoint:"footable_breakpoint",columnData:"footable_column_data",rowDetailUpdating:"footable_row_detail_updating",rowDetailUpdated:"footable_row_detail_updated",rowCollapsed:"footable_row_collapsed",rowExpanded:"footable_row_expanded",rowRemoved:"footable_row_removed",reset:"footable_reset"},debug:!1,log:null},version:{major:0,minor:5,toString:function(){return t.footable.version.major+"."+t.footable.version.minor},parse:function(t){var e=/(\d+)\.?(\d+)?\.?(\d+)?/.exec(t);return{major:parseInt(e[1],10)||0,minor:parseInt(e[2],10)||0,patch:parseInt(e[3],10)||0}}},plugins:{_validate:function(e){if(!$.isFunction(e))return t.footable.options.debug===!0&&console.error('Validation failed, expected type "function", received type "{0}".',typeof e),!1;var i=new e;return"string"!=typeof i.name?(t.footable.options.debug===!0&&console.error('Validation failed, plugin does not implement a string property called "name".',i),!1):$.isFunction(i.init)?(t.footable.options.debug===!0&&console.log('Validation succeeded for plugin "'+i.name+'".',i),!0):(t.footable.options.debug===!0&&console.error('Validation failed, plugin "'+i.name+'" does not implement a function called "init".',i),!1)},registered:[],register:function(e,i){t.footable.plugins._validate(e)&&(t.footable.plugins.registered.push(e),"object"==typeof i&&$.extend(!0,t.footable.options,i))},load:function(e){var i=[],o,n;for(n=0;n<t.footable.plugins.registered.length;n++)try{o=t.footable.plugins.registered[n],i.push(new o(e))}catch(a){t.footable.options.debug===!0&&console.error(a)}return i},init:function(e){for(var i=0;i<e.plugins.length;i++)try{e.plugins[i].init(e)}catch(o){t.footable.options.debug===!0&&console.error(o)}}}};var n=0;$.fn.footable=function(e){e=e||{};var i=$.extend(!0,{},t.footable.options,e);return this.each(function(){n++;var t=new o(this,i,n);$(this).data("footable",t)})}}(jQuery,window),function($,t,e){function i(){var t=this;t.name="Footable Striping",t.init=function(e){t.footable=e,$(e.table).unbind("striping").bind({"footable_initialized.striping footable_row_removed.striping footable_redrawn.striping footable_sorted.striping footable_filtered.striping":function(){$(this).data("striping")!==!1&&t.setupStriping(e)}})},t.setupStriping=function(t){var e=0;$(t.table).find("> tbody > tr:not(.footable-row-detail)").each(function(){var i=$(this);i.removeClass(t.options.classes.striping.even).removeClass(t.options.classes.striping.odd),e%2===0?i.addClass(t.options.classes.striping.even):i.addClass(t.options.classes.striping.odd),e++})}}if(t.footable===e||null===t.foobox)throw new Error("Please check and make sure footable.js is included in the page and is loaded prior to this script.");var o={striping:{enabled:!0},classes:{striping:{odd:"footable-odd",even:"footable-even"}}};t.footable.plugins.register(i,o)}(jQuery,window),function($){$.slidebars=function(t){function e(){!l.disableOver||"number"==typeof l.disableOver&&l.disableOver>=C?(y=!0,$("html").addClass("sb-init"),l.hideControlClasses&&x.removeClass("sb-hide"),i()):"number"==typeof l.disableOver&&l.disableOver<C&&(y=!1,$("html").removeClass("sb-init"),l.hideControlClasses&&x.addClass("sb-hide"),g.css("minHeight",""),(w||v)&&a())}function i(){g.css("minHeight","");var t=parseInt(g.css("height"),10),e=parseInt($("html").css("height"),10);e>t&&g.css("minHeight",$("html").css("height")),b&&b.hasClass("sb-width-custom")&&b.css("width",b.attr("data-sb-width")),m&&m.hasClass("sb-width-custom")&&m.css("width",m.attr("data-sb-width")),b&&(b.hasClass("sb-style-push")||b.hasClass("sb-style-overlay"))&&b.css("marginLeft","-"+b.css("width")),m&&(m.hasClass("sb-style-push")||m.hasClass("sb-style-overlay"))&&m.css("marginRight","-"+m.css("width")),l.scrollLock&&$("html").addClass("sb-scroll-lock")}function o(t,e,o){function n(){a.removeAttr("style"),i()}var a;if(a=t.hasClass("sb-style-push")?g.add(t).add(k):t.hasClass("sb-style-overlay")?t:g.add(k),"translate"===S)"0px"===e?n():a.css("transform","translate( "+e+" )");else if("side"===S)"0px"===e?n():("-"===e[0]&&(e=e.substr(1)),a.css(o,"0px"),setTimeout(function(){a.css(o,e)},1));else if("jQuery"===S){"-"===e[0]&&(e=e.substr(1));var r={};r[o]=e,a.stop().animate(r,400)}}function n(t){function e(){y&&"left"===t&&b?($("html").addClass("sb-active sb-active-left"),b.addClass("sb-active"),o(b,b.css("width"),"left"),setTimeout(function(){w=!0},400)):y&&"right"===t&&m&&($("html").addClass("sb-active sb-active-right"),m.addClass("sb-active"),o(m,"-"+m.css("width"),"right"),setTimeout(function(){v=!0},400))}"left"===t&&b&&v||"right"===t&&m&&w?(a(),setTimeout(e,400)):e()}function a(t,e){(w||v)&&(w&&(o(b,"0px","left"),w=!1),v&&(o(m,"0px","right"),v=!1),setTimeout(function(){$("html").removeClass("sb-active sb-active-left sb-active-right"),b&&b.removeClass("sb-active"),m&&m.removeClass("sb-active"),"undefined"!=typeof t&&(void 0===typeof e&&(e="_self"),window.open(t,e))},400))}function r(t){"left"===t&&b&&(w?a():n("left")),"right"===t&&m&&(v?a():n("right"))}function s(t,e){t.stopPropagation(),t.preventDefault(),"touchend"===t.type&&e.off("click")}var l=$.extend({siteClose:!0,scrollLock:!1,disableOver:!1,hideControlClasses:!1},t),d=document.createElement("div").style,u=!1,c=!1;(""===d.MozTransition||""===d.WebkitTransition||""===d.OTransition||""===d.transition)&&(u=!0),(""===d.MozTransform||""===d.WebkitTransform||""===d.OTransform||""===d.transform)&&(c=!0);var h=navigator.userAgent,p=!1,f=!1;/Android/.test(h)?p=h.substr(h.indexOf("Android")+8,3):/(iPhone|iPod|iPad)/.test(h)&&(f=h.substr(h.indexOf("OS ")+3,3).replace("_",".")),(p&&3>p||f&&5>f)&&$("html").addClass("sb-static");var g=$("#sb-site, .sb-site-container");if($(".sb-left").length)var b=$(".sb-left"),w=!1;if($(".sb-right").length)var m=$(".sb-right"),v=!1;var y=!1,C=$(window).width(),x=$(".sb-toggle-left, .sb-toggle-right, .sb-open-left, .sb-open-right, .sb-close"),k=$(".sb-slide");e(),$(window).resize(function(){var t=$(window).width();C!==t&&(C=t,e(),w&&n("left"),v&&n("right"))});var S;u&&c?(S="translate",p&&4.4>p&&(S="side")):S="jQuery",this.slidebars={open:n,close:a,toggle:r,init:function(){return y},active:function(t){return"left"===t&&b?w:"right"===t&&m?v:void 0},destroy:function(t){"left"===t&&b&&(w&&a(),setTimeout(function(){b.remove(),b=!1},400)),"right"===t&&m&&(v&&a(),setTimeout(function(){m.remove(),m=!1},400))}},$(".sb-toggle-left").on("touchend click",function(t){s(t,$(this)),r("left")}),$(".sb-toggle-right").on("touchend click",function(t){s(t,$(this)),r("right")}),$(".sb-open-left").on("touchend click",function(t){s(t,$(this)),n("left")}),$(".sb-open-right").on("touchend click",function(t){s(t,$(this)),n("right")}),$(".sb-close").on("touchend click",function(t){if($(this).is("a")||$(this).children().is("a")){if("click"===t.type){t.stopPropagation(),t.preventDefault();var e=$(this).is("a")?$(this):$(this).find("a"),i=e.attr("href"),o=e.attr("target")?e.attr("target"):"_self";a(i,o)}}else s(t,$(this)),a()}),g.on("touchend click",function(t){l.siteClose&&(w||v)&&(s(t,$(this)),a())})}}(jQuery),function($,t,e,i){$.fn.doubleTapToGo=function(i){return"ontouchstart"in t||navigator.msMaxTouchPoints||navigator.userAgent.toLowerCase().match(/windows phone os 7/i)?(this.each(function(){var t=!1;$(this).on("click",function(e){var i=$(this);i[0]!=t[0]&&(e.preventDefault(),t=i)}),$(e).on("click touchstart MSPointerDown",function(e){for(var i=!0,o=$(e.target).parents(),n=0;n<o.length;n++)o[n]==t[0]&&(i=!1);i&&(t=!1)})}),this):!1}}(jQuery,window,document),function(){"use strict";function t(o){if(!o)throw new Error("No options passed to Waypoint constructor");if(!o.element)throw new Error("No element option passed to Waypoint constructor");if(!o.handler)throw new Error("No handler option passed to Waypoint constructor");this.key="waypoint-"+e,this.options=t.Adapter.extend({},t.defaults,o),this.element=this.options.element,this.adapter=new t.Adapter(this.element),this.callback=o.handler,this.axis=this.options.horizontal?"horizontal":"vertical",this.enabled=this.options.enabled,this.triggerPoint=null,this.group=t.Group.findOrCreate({name:this.options.group,axis:this.axis}),this.context=t.Context.findOrCreateByElement(this.options.context),t.offsetAliases[this.options.offset]&&(this.options.offset=t.offsetAliases[this.options.offset]),this.group.add(this),this.context.add(this),i[this.key]=this,e+=1}var e=0,i={};t.prototype.queueTrigger=function(t){this.group.queueTrigger(this,t)},t.prototype.trigger=function(t){this.enabled&&this.callback&&this.callback.apply(this,t)},t.prototype.destroy=function(){this.context.remove(this),this.group.remove(this),delete i[this.key]},t.prototype.disable=function(){return this.enabled=!1,this},t.prototype.enable=function(){return this.context.refresh(),this.enabled=!0,this},t.prototype.next=function(){return this.group.next(this)},t.prototype.previous=function(){return this.group.previous(this)},t.invokeAll=function(t){var e=[];for(var o in i)e.push(i[o]);for(var n=0,a=e.length;a>n;n++)e[n][t]()},t.destroyAll=function(){t.invokeAll("destroy")},t.disableAll=function(){t.invokeAll("disable")},t.enableAll=function(){t.invokeAll("enable")},t.refreshAll=function(){t.Context.refreshAll()},t.viewportHeight=function(){return window.innerHeight||document.documentElement.clientHeight},t.viewportWidth=function(){return document.documentElement.clientWidth},t.adapters=[],t.defaults={context:window,continuous:!0,enabled:!0,group:"default",horizontal:!1,offset:0},t.offsetAliases={"bottom-in-view":function(){return this.context.innerHeight()-this.adapter.outerHeight()},"right-in-view":function(){return this.context.innerWidth()-this.adapter.outerWidth()}},window.Waypoint=t}(),function(){"use strict";function t(t){window.setTimeout(t,1e3/60)}function e(t){this.element=t,this.Adapter=n.Adapter,this.adapter=new this.Adapter(t),this.key="waypoint-context-"+i,this.didScroll=!1,this.didResize=!1,this.oldScroll={x:this.adapter.scrollLeft(),y:this.adapter.scrollTop()},this.waypoints={vertical:{},horizontal:{}},t.waypointContextKey=this.key,o[t.waypointContextKey]=this,i+=1,this.createThrottledScrollHandler(),this.createThrottledResizeHandler()}var i=0,o={},n=window.Waypoint,a=window.onload;e.prototype.add=function(t){var e=t.options.horizontal?"horizontal":"vertical";this.waypoints[e][t.key]=t,this.refresh()},e.prototype.checkEmpty=function(){var t=this.Adapter.isEmptyObject(this.waypoints.horizontal),e=this.Adapter.isEmptyObject(this.waypoints.vertical);t&&e&&(this.adapter.off(".waypoints"),delete o[this.key])},e.prototype.createThrottledResizeHandler=function(){function t(){e.handleResize(),e.didResize=!1}var e=this;this.adapter.on("resize.waypoints",function(){e.didResize||(e.didResize=!0,n.requestAnimationFrame(t))})},e.prototype.createThrottledScrollHandler=function(){function t(){e.handleScroll(),e.didScroll=!1}var e=this;this.adapter.on("scroll.waypoints",function(){(!e.didScroll||n.isTouch)&&(e.didScroll=!0,n.requestAnimationFrame(t))})},e.prototype.handleResize=function(){n.Context.refreshAll()},e.prototype.handleScroll=function(){var t={},e={horizontal:{newScroll:this.adapter.scrollLeft(),oldScroll:this.oldScroll.x,forward:"right",backward:"left"},vertical:{newScroll:this.adapter.scrollTop(),oldScroll:this.oldScroll.y,forward:"down",backward:"up"}};for(var i in e){var o=e[i],n=o.newScroll>o.oldScroll,a=n?o.forward:o.backward;for(var r in this.waypoints[i]){var s=this.waypoints[i][r],l=o.oldScroll<s.triggerPoint,d=o.newScroll>=s.triggerPoint,u=l&&d,c=!l&&!d;(u||c)&&(s.queueTrigger(a),t[s.group.id]=s.group)}}for(var h in t)t[h].flushTriggers();this.oldScroll={x:e.horizontal.newScroll,y:e.vertical.newScroll}},e.prototype.innerHeight=function(){return this.element==this.element.window?n.viewportHeight():this.adapter.innerHeight()},e.prototype.remove=function(t){delete this.waypoints[t.axis][t.key],this.checkEmpty()},e.prototype.innerWidth=function(){return this.element==this.element.window?n.viewportWidth():this.adapter.innerWidth()},e.prototype.destroy=function(){var t=[];for(var e in this.waypoints)for(var i in this.waypoints[e])t.push(this.waypoints[e][i]);for(var o=0,n=t.length;n>o;o++)t[o].destroy()},e.prototype.refresh=function(){var t=this.element==this.element.window,e=this.adapter.offset(),i={},o;this.handleScroll(),o={horizontal:{contextOffset:t?0:e.left,contextScroll:t?0:this.oldScroll.x,contextDimension:this.innerWidth(),oldScroll:this.oldScroll.x,forward:"right",backward:"left",offsetProp:"left"},vertical:{contextOffset:t?0:e.top,contextScroll:t?0:this.oldScroll.y,contextDimension:this.innerHeight(),oldScroll:this.oldScroll.y,forward:"down",backward:"up",offsetProp:"top"}};for(var n in o){var a=o[n];for(var r in this.waypoints[n]){var s=this.waypoints[n][r],l=s.options.offset,d=s.triggerPoint,u=0,c=null==d,h,p,f,g,b;s.element!==s.element.window&&(u=s.adapter.offset()[a.offsetProp]),"function"==typeof l?l=l.apply(s):"string"==typeof l&&(l=parseFloat(l),s.options.offset.indexOf("%")>-1&&(l=Math.ceil(a.contextDimension*l/100))),h=a.contextScroll-a.contextOffset,s.triggerPoint=u+h-l,p=d<a.oldScroll,f=s.triggerPoint>=a.oldScroll,g=p&&f,b=!p&&!f,!c&&g?(s.queueTrigger(a.backward),i[s.group.id]=s.group):!c&&b?(s.queueTrigger(a.forward),i[s.group.id]=s.group):c&&a.oldScroll>=s.triggerPoint&&(s.queueTrigger(a.forward),i[s.group.id]=s.group)}}for(var w in i)i[w].flushTriggers();return this},e.findOrCreateByElement=function(t){return e.findByElement(t)||new e(t)},e.refreshAll=function(){for(var t in o)o[t].refresh()},e.findByElement=function(t){return o[t.waypointContextKey]},window.onload=function(){a&&a(),e.refreshAll()},n.requestAnimationFrame=function(e){var i=window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||t;i.call(window,e)},n.Context=e}(),function(){"use strict";function t(t,e){return t.triggerPoint-e.triggerPoint}function e(t,e){return e.triggerPoint-t.triggerPoint}function i(t){this.name=t.name,this.axis=t.axis,this.id=this.name+"-"+this.axis,this.waypoints=[],this.clearTriggerQueues(),o[this.axis][this.name]=this}var o={vertical:{},horizontal:{}},n=window.Waypoint;i.prototype.add=function(t){this.waypoints.push(t)},i.prototype.clearTriggerQueues=function(){this.triggerQueues={up:[],down:[],left:[],right:[]}},i.prototype.flushTriggers=function(){for(var i in this.triggerQueues){var o=this.triggerQueues[i],n="up"===i||"left"===i;o.sort(n?e:t);for(var a=0,r=o.length;r>a;a+=1){var s=o[a];(s.options.continuous||a===o.length-1)&&s.trigger([i])}}this.clearTriggerQueues()},i.prototype.next=function(e){this.waypoints.sort(t);var i=n.Adapter.inArray(e,this.waypoints),o=i===this.waypoints.length-1;return o?null:this.waypoints[i+1]},i.prototype.previous=function(e){this.waypoints.sort(t);var i=n.Adapter.inArray(e,this.waypoints);return i?this.waypoints[i-1]:null},i.prototype.queueTrigger=function(t,e){this.triggerQueues[e].push(t)},i.prototype.remove=function(t){var e=n.Adapter.inArray(t,this.waypoints);e>-1&&this.waypoints.splice(e,1)},i.prototype.first=function(){return this.waypoints[0]},i.prototype.last=function(){return this.waypoints[this.waypoints.length-1]},i.findOrCreate=function(t){return o[t.axis][t.name]||new i(t)},n.Group=i}(),function(){"use strict";function t(t){this.$element=$(t)}var $=window.jQuery,e=window.Waypoint;$.each(["innerHeight","innerWidth","off","offset","on","outerHeight","outerWidth","scrollLeft","scrollTop"],function(e,i){t.prototype[i]=function(){var t=Array.prototype.slice.call(arguments);return this.$element[i].apply(this.$element,t)}}),$.each(["extend","inArray","isEmptyObject"],function(e,i){t[i]=$[i]}),e.adapters.push({name:"jquery",Adapter:t}),e.Adapter=t}(),function(){"use strict";function t(t){return function(){var i=[],o=arguments[0];return t.isFunction(arguments[0])&&(o=t.extend({},arguments[1]),o.handler=arguments[0]),this.each(function(){var n=t.extend({},o,{element:this});"string"==typeof n.context&&(n.context=t(this).closest(n.context)[0]),i.push(new e(n))}),i}}var e=window.Waypoint;window.jQuery&&(window.jQuery.fn.waypoint=t(window.jQuery)),window.Zepto&&(window.Zepto.fn.waypoint=t(window.Zepto))}(),function(){"use strict";function t(i){this.options=$.extend({},e.defaults,t.defaults,i),this.element=this.options.element,this.$element=$(this.element),this.createWrapper(),this.createWaypoint()}var $=window.jQuery,e=window.Waypoint;t.prototype.createWaypoint=function(){var t=this.options.handler;this.waypoint=new e($.extend({},this.options,{element:this.wrapper,handler:$.proxy(function(e){var i=this.options.direction.indexOf(e)>-1,o=i?this.$element.outerHeight(!0):"";this.$wrapper.height(o),this.$element.toggleClass(this.options.stuckClass,i),t&&t.call(this,e)},this)}))},t.prototype.createWrapper=function(){this.$element.wrap(this.options.wrapper),this.$wrapper=this.$element.parent(),this.wrapper=this.$wrapper[0]},t.prototype.destroy=function(){this.$element.parent()[0]===this.wrapper&&(this.waypoint.destroy(),this.$element.removeClass(this.options.stuckClass).unwrap())},t.defaults={wrapper:'<div class="sticky-wrapper" />',stuckClass:"stuck",direction:"down right"},e.Sticky=t}(),jQuery.noConflict(),function($){$(document).ready(function(){function t(){var t=$(".floating-labels .cd-label").next();t.each(function(){var t=$(this);e(t),t.on("change keyup",function(){e(t)})})}function e(t){""==t.val()?t.prev(".cd-label").removeClass("float"):t.prev(".cd-label").addClass("float")}if(navigator.userAgent.match(/IEMobile\/10\.0/)){var i=document.createElement("style");i.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}")),document.getElementsByTagName("head")[0].appendChild(i)}$("#nav li:has(ul)").doubleTapToGo(),$(".footable").footable({breakpoints:{phone:704,tablet:1024}}),$("#search-button").click(function(){return $("#search-bar").slideToggle(),$("#search-field").is(":focus")?$("#search-field").blur():$("#search-field").focus(),!1}),$(".ten-reasons-btn").click(function(t){$(this).next().slideToggle("slow"),$(this).parent().toggleClass("box-active"),t.preventDefault()});var o=new Waypoint.Sticky({element:$(".main-nav-wrapper")[0]});$(".free-quote-button").waypoint(function(){$(this.element).addClass("visible")}),$.slidebars(),$("#sb-slidebar-nav .sb-toggle-submenu").off("click").on("click",function(){return $submenu=$(this).parent().children(".sb-submenu"),$(this).add($submenu).toggleClass("sb-submenu-active"),$submenu.hasClass("sb-submenu-active")?($("#sb-slidebar-nav .sb-toggle-submenu i").toggleClass("ion-ios-arrow-up ion-ios-arrow-down"),$submenu.slideDown(200)):($("#sb-slidebar-nav .sb-toggle-submenu i").toggleClass("ion-ios-arrow-up ion-ios-arrow-down"),$submenu.slideUp(200)),!1}),$("#sb-slidebar-nav .sb-toggle-submenu").append(' <i class="ion ion-ios-arrow-down"></i>'),$(".faq-item-title").click(function(t){$(this).next().slideToggle("slow"),$(this).parent().toggleClass("faq-item-active"),t.preventDefault()}),$("#filter").keyup(function(){var t=$(this).val(),e=0;$(".glossary-list li").each(function(){$(this).text().search(new RegExp(t,"i"))<0?$(this).fadeOut():($(this).show(),e++)});var i=e;$("#filter-count").text(e+" item(s) found")}),$("#glossary-search :input").on("keypress",function(t){return 13!=t.keyCode}),$(".floating-labels").length>0&&t(),$("#currency-usd").change(function(){$("#currency-warning").toggle(this.checked)})})}(jQuery);
//# sourceMappingURL=./script-min.js.map