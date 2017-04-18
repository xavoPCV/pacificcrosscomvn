﻿/*
 Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
(function(){function o(a){this.editor=a;this.registered={};this.instances={};this.selected=[];this.widgetHoldingFocusedEditable=this.focused=null;this._={nextId:0,upcasts:[],upcastCallbacks:[],filters:{}};I(this);J(this);this.on("checkWidgets",K);this.editor.on("contentDomInvalidated",this.checkWidgets,this);L(this);M(this);N(this);O(this);P(this)}function k(a,b,c,d,e){var f=a.editor;CKEDITOR.tools.extend(this,d,{editor:f,id:b,inline:"span"==c.getParent().getName(),element:c,data:CKEDITOR.tools.extend({},
"function"==typeof d.defaults?d.defaults():d.defaults),dataReady:!1,inited:!1,ready:!1,edit:k.prototype.edit,focusedEditable:null,definition:d,repository:a,draggable:!1!==d.draggable,_:{downcastFn:d.downcast&&"string"==typeof d.downcast?d.downcasts[d.downcast]:d.downcast}},!0);a.fire("instanceCreated",this);Q(this,d);this.init&&this.init();this.inited=!0;(a=this.element.data("cke-widget-data"))&&this.setData(JSON.parse(decodeURIComponent(a)));e&&this.setData(e);this.data.classes||this.setData("classes",
this.getClasses());this.dataReady=!0;s(this);this.fire("data",this.data);this.isInited()&&f.editable().contains(this.wrapper)&&(this.ready=!0,this.fire("ready"))}function q(a,b,c){CKEDITOR.dom.element.call(this,b.$);this.editor=a;b=this.filter=c.filter;CKEDITOR.dtd[this.getName()].p?(this.enterMode=b?b.getAllowedEnterMode(a.enterMode):a.enterMode,this.shiftEnterMode=b?b.getAllowedEnterMode(a.shiftEnterMode,!0):a.shiftEnterMode):this.enterMode=this.shiftEnterMode=CKEDITOR.ENTER_BR}function R(a,b){a.addCommand(b.name,
{exec:function(){function c(){a.widgets.finalizeCreation(g)}var d=a.widgets.focused;if(d&&d.name==b.name)d.edit();else if(b.insert)b.insert();else if(b.template){var d="function"==typeof b.defaults?b.defaults():b.defaults,d=CKEDITOR.dom.element.createFromHtml(b.template.output(d)),e,f=a.widgets.wrapElement(d,b.name),g=new CKEDITOR.dom.documentFragment(f.getDocument());g.append(f);(e=a.widgets.initOn(d,b))?(d=e.once("edit",function(b){if(b.data.dialog)e.once("dialog",function(b){var b=b.data,d,f;d=
b.once("ok",c,null,null,20);f=b.once("cancel",function(){a.widgets.destroy(e,!0)});b.once("hide",function(){d.removeListener();f.removeListener()})});else c()},null,null,999),e.edit(),d.removeListener()):c()}},refresh:function(a,b){this.setState(l(a.editable(),b.blockLimit)?CKEDITOR.TRISTATE_DISABLED:CKEDITOR.TRISTATE_OFF)},context:"div",allowedContent:b.allowedContent,requiredContent:b.requiredContent,contentForms:b.contentForms,contentTransformations:b.contentTransformations})}function t(a,b){a.focused=
null;if(b.isInited()){var c=b.editor.checkDirty();a.fire("widgetBlurred",{widget:b});b.setFocused(!1);!c&&b.editor.resetDirty()}}function K(a){a=a.data;if("wysiwyg"==this.editor.mode){var b=this.editor.editable(),c=this.instances,d,e;if(b){for(d in c)b.contains(c[d].wrapper)||this.destroy(c[d],!0);if(a&&a.initOnlyNew)b=this.initOnAll();else{var f=b.find(".cke_widget_wrapper"),b=[];d=0;for(c=f.count();d<c;d++){e=f.getItem(d);var g;if(g=!this.getByElement(e,!0)){a:{g=S;for(var i=e;i=i.getParent();)if(g(i)){g=
!0;break a}g=!1}g=!g}g&&(e.addClass("cke_widget_new"),b.push(this.initOn(e.getFirst(p))))}}a&&(a.focusInited&&1==b.length)&&b[0].focus()}}}function u(a,b,c){if(!c.allowedContent)return null;var d=this._.filters[a];d||(this._.filters[a]=d={});(a=d[b])||(d[b]=a=new CKEDITOR.filter(c.allowedContent));return a}function T(a){var b=[],c=a._.upcasts,d=a._.upcastCallbacks;return{toBeWrapped:b,iterator:function(a){var f,g,i,h,j;if("data-cke-widget-wrapper"in a.attributes)return(a=a.getFirst(v))&&b.push([a]),
!1;if("data-widget"in a.attributes)return b.push([a]),!1;if(j=c.length){if(a.attributes["data-cke-widget-upcasted"])return!1;h=0;for(f=d.length;h<f;++h)if(!1===d[h](a))return;for(h=0;h<j;++h)if(f=c[h],i={},g=f[0](a,i))return g instanceof CKEDITOR.htmlParser.element&&(a=g),a.attributes["data-cke-widget-data"]=encodeURIComponent(JSON.stringify(i)),a.attributes["data-cke-widget-upcasted"]=1,b.push([a,f[1]]),!1}}}}function l(a,b){return!b||b.equals(a)?null:w(b)?b:l(a,b.getParent())}function x(a){return{tabindex:-1,
contenteditable:"false","data-cke-widget-wrapper":1,"data-cke-filter":"off","class":"cke_widget_wrapper cke_widget_new cke_widget_"+(a?"inline":"block")}}function y(a,b,c){if(a.type==CKEDITOR.NODE_ELEMENT){var d=CKEDITOR.dtd[a.name];if(d&&!d[c.name]){var d=a.split(b),e=a.parent,b=d.getIndex();a.children.length||(b-=1,a.remove());d.children.length||d.remove();return y(e,b,c)}}a.add(c,b)}function v(a){return a.type==CKEDITOR.NODE_ELEMENT&&!!a.attributes["data-widget"]}function p(a){return a.type==CKEDITOR.NODE_ELEMENT&&
a.hasAttribute("data-widget")}function z(a,b){return"boolean"==typeof a.inline?a.inline:!!CKEDITOR.dtd.$inline[b]}function r(a){return a.type==CKEDITOR.NODE_ELEMENT&&a.hasAttribute("data-cke-widget-wrapper")}function w(a){return a.type==CKEDITOR.NODE_ELEMENT&&a.hasAttribute("data-cke-widget-editable")}function S(a){return a.hasAttribute("data-cke-temp")}function U(a){return a.type==CKEDITOR.NODE_ELEMENT&&a.hasClass("cke_widget_drag_handler_container")}function A(a,b,c){b.focus();a.fire("saveSnapshot");
a.fire("lockSnapshot",{dontUpdate:!0});c.select();c=b.wrapper.getOuterHtml();b.wrapper.remove();a.widgets.destroy(b,!0);a.execCommand("paste",c);a.fire("unlockSnapshot")}function n(a,b,c,d){var e=a.editor;e.fire("lockSnapshot");c?(d=c.data("cke-widget-editable"),d=b.editables[d],a.widgetHoldingFocusedEditable=b,b.focusedEditable=d,c.addClass("cke_widget_editable_focused"),d.filter&&e.setActiveFilter(d.filter),e.setActiveEnterMode(d.enterMode,d.shiftEnterMode)):(d||b.focusedEditable.removeClass("cke_widget_editable_focused"),
b.focusedEditable=null,a.widgetHoldingFocusedEditable=null,e.setActiveFilter(null),e.setActiveEnterMode(null,null));e.fire("unlockSnapshot")}function V(a){a.contextMenu&&a.contextMenu.addListener(function(b){if(b=a.widgets.getByElement(b,!0))return b.fire("contextMenu",{})})}function W(a,b){return CKEDITOR.tools.trim(b)}function O(a){var b=a.editor,c=CKEDITOR.plugins.lineutils;b.on("contentDom",function(){var d=b.editable(),e=CKEDITOR.env.ie&&9>CKEDITOR.env.version||d.isInline()?d:b.document;d.attachListener(e,
"drop",function(c){var d=c.data.$.dataTransfer.getData("text"),e,h;if(d){try{e=JSON.parse(d)}catch(j){return}if("cke-widget"==e.type&&(c.data.preventDefault(),e.editor==b.name&&(h=a.instances[e.id]))){a:if(e=c.data.$,d=b.createRange(),c.data.testRange)d=c.data.testRange;else if(document.caretRangeFromPoint)c=b.document.$.caretRangeFromPoint(e.clientX,e.clientY),d.setStart(CKEDITOR.dom.node(c.startContainer),c.startOffset),d.collapse(!0);else if(e.rangeParent)d.setStart(CKEDITOR.dom.node(e.rangeParent),
e.rangeOffset),d.collapse(!0);else if(document.body.createTextRange)c=b.document.getBody().$.createTextRange(),c.moveToPoint(e.clientX,e.clientY),e="cke-temp-"+(new Date).getTime(),c.pasteHTML('<span id="'+e+'">​</span>'),c=b.document.getById(e),d.moveToPosition(c,CKEDITOR.POSITION_BEFORE_START),c.remove();else{d=null;break a}d&&(CKEDITOR.env.gecko?setTimeout(A,0,b,h,d):A(b,h,d))}}});CKEDITOR.tools.extend(a,{finder:new c.finder(b,{lookups:{"default":function(a){if(!a.is(CKEDITOR.dtd.$listItem)&&a.is(CKEDITOR.dtd.$block)){for(;a;){if(w(a))return;
a=a.getParent()}return CKEDITOR.LINEUTILS_BEFORE|CKEDITOR.LINEUTILS_AFTER}}}}),locator:new c.locator(b),liner:new c.liner(b,{lineStyle:{cursor:"move !important","border-top-color":"#666"},tipLeftStyle:{"border-left-color":"#666"},tipRightStyle:{"border-right-color":"#666"}})},!0)})}function M(a){var b=a.editor;b.on("contentDom",function(){var c=b.editable(),d=c.isInline()?c:b.document,e,f;c.attachListener(d,"mousedown",function(c){var b=c.data.getTarget();if(!b.type)return!1;e=a.getByElement(b);f=
0;e&&(e.inline&&b.type==CKEDITOR.NODE_ELEMENT&&b.hasAttribute("data-cke-widget-drag-handler")?f=1:l(e.wrapper,b)?e=null:(c.data.preventDefault(),CKEDITOR.env.ie||e.focus()))});c.attachListener(d,"mouseup",function(){e&&f&&(f=0,e.focus())});CKEDITOR.env.ie&&c.attachListener(d,"mouseup",function(){e&&setTimeout(function(){e.focus();e=null})})});b.on("doubleclick",function(c){var b=a.getByElement(c.data.element);if(b&&!l(b.wrapper,c.data.element))return b.fire("doubleclick",{element:c.data.element})},
null,null,1)}function N(a){a.editor.on("key",function(b){var c=a.focused,d=a.widgetHoldingFocusedEditable,e;c?e=c.fire("key",{keyCode:b.data.keyCode}):d&&(c=b.data.keyCode,b=d.focusedEditable,c==CKEDITOR.CTRL+65?(c=b.getBogus(),d=d.editor.createRange(),d.selectNodeContents(b),c&&d.setEndAt(c,CKEDITOR.POSITION_BEFORE_START),d.select(),e=!1):8==c||46==c?(e=d.editor.getSelection().getRanges(),d=e[0],e=!(1==e.length&&d.collapsed&&d.checkBoundaryOfElement(b,CKEDITOR[8==c?"START":"END"]))):e=void 0);return e},
null,null,1)}function P(a){function b(c){a.focused&&B(a.focused,"cut"==c.name)}var c=a.editor;c.on("contentDom",function(){var a=c.editable();a.attachListener(a,"copy",b);a.attachListener(a,"cut",b)})}function L(a){var b=a.editor;b.on("selectionCheck",function(){a.fire("checkSelection")});a.on("checkSelection",a.checkSelection,a);b.on("selectionChange",function(c){var d=(c=l(b.editable(),c.data.selection.getStartElement()))&&a.getByElement(c),e=a.widgetHoldingFocusedEditable;if(e){if(e!==d||!e.focusedEditable.equals(c))n(a,
e,null),d&&c&&n(a,d,c)}else d&&c&&n(a,d,c)});b.on("dataReady",function(){C(a).commit()});b.on("blur",function(){var c;(c=a.focused)&&t(a,c);(c=a.widgetHoldingFocusedEditable)&&n(a,c,null)})}function J(a){var b=a.editor,c={};b.on("toDataFormat",function(b){var e=CKEDITOR.tools.getNextNumber(),f=[];b.data.downcastingSessionId=e;c[e]=f;b.data.dataValue.forEach(function(c){var b=c.attributes,d;if("data-cke-widget-id"in b){if(b=a.instances[b["data-cke-widget-id"]])d=c.getFirst(v),f.push({wrapper:c,element:d,
widget:b,editables:{}}),"1"!=d.attributes["data-cke-widget-keep-attr"]&&delete d.attributes["data-widget"]}else if("data-cke-widget-editable"in b)return f[f.length-1].editables[b["data-cke-widget-editable"]]=c,!1},CKEDITOR.NODE_ELEMENT,!0)},null,null,8);b.on("toDataFormat",function(a){if(a.data.downcastingSessionId)for(var a=c[a.data.downcastingSessionId],b,f,g,i,h,j;b=a.shift();){f=b.widget;g=b.element;i=f._.downcastFn&&f._.downcastFn.call(f,g);for(j in b.editables)h=b.editables[j],delete h.attributes.contenteditable,
h.setHtml(f.editables[j].getData());i||(i=g);b.wrapper.replaceWith(i)}},null,null,13);b.on("contentDomUnload",function(){a.destroyAll(!0)})}function I(a){function b(){c.fire("lockSnapshot");a.checkWidgets({initOnlyNew:!0,focusInited:d});c.fire("unlockSnapshot")}var c=a.editor,d,e;c.on("toHtml",function(c){var b=T(a),e;for(c.data.dataValue.forEach(b.iterator,CKEDITOR.NODE_ELEMENT,!0);e=b.toBeWrapped.pop();){var h=e[0],j=h.parent;j.type==CKEDITOR.NODE_ELEMENT&&j.attributes["data-cke-widget-wrapper"]&&
j.replaceWith(h);a.wrapElement(e[0],e[1])}d=1==c.data.dataValue.children.length&&c.data.dataValue.children[0].type==CKEDITOR.NODE_ELEMENT&&c.data.dataValue.children[0].attributes["data-cke-widget-wrapper"]},null,null,8);c.on("dataReady",function(){if(e)for(var b=a,d=c.editable().find(".cke_widget_wrapper"),i,h,j=0,k=d.count();j<k;++j)i=d.getItem(j),h=i.getFirst(p),h.type==CKEDITOR.NODE_ELEMENT&&h.data("widget")?(h.replace(i),b.wrapElement(h)):i.remove();e=0;a.destroyAll(!0);a.initOnAll()});c.on("loadSnapshot",
function(c){/data-cke-widget/.test(c.data)&&(e=1);a.destroyAll(!0)},null,null,9);c.on("paste",function(a){a.data.dataValue=a.data.dataValue.replace(X,W)});c.on("insertText",b,null,null,999);c.on("insertHtml",b,null,null,999)}function C(a){var b=a.selected,c=[],d=b.slice(0),e=null;return{select:function(a){0>CKEDITOR.tools.indexOf(b,a)&&c.push(a);a=CKEDITOR.tools.indexOf(d,a);0<=a&&d.splice(a,1);return this},focus:function(a){e=a;return this},commit:function(){var f=a.focused!==e,g,i;a.editor.fire("lockSnapshot");
for(f&&(g=a.focused)&&t(a,g);g=d.pop();)b.splice(CKEDITOR.tools.indexOf(b,g),1),g.isInited()&&(i=g.editor.checkDirty(),g.setSelected(!1),!i&&g.editor.resetDirty());f&&e&&(i=a.editor.checkDirty(),a.focused=e,a.fire("widgetFocused",{widget:e}),e.setFocused(!0),!i&&a.editor.resetDirty());for(;g=c.pop();)b.push(g),g.setSelected(!0);a.editor.fire("unlockSnapshot")}}}function D(a,b,c){var d=0,b=E(b),e=a.data.classes||{},f;if(b){for(e=CKEDITOR.tools.clone(e);f=b.pop();)c?e[f]||(d=e[f]=1):e[f]&&(delete e[f],
d=1);d&&a.setData("classes",e)}}function F(a){a.cancel()}function B(a,b){var c=a.editor,d=c.document;if(!d.getById("cke_copybin")){var e=c.blockless||CKEDITOR.env.ie?"span":"div",f=d.createElement(e),g=d.createElement(e),e=CKEDITOR.env.ie&&9>CKEDITOR.env.version;g.setAttributes({id:"cke_copybin","data-cke-temp":"1"});f.setStyles({position:"absolute",width:"1px",height:"1px",overflow:"hidden"});f.setStyle("ltr"==c.config.contentsLangDirection?"left":"right","-5000px");f.setHtml('<span data-cke-copybin-start="1">​</span>'+
a.wrapper.getOuterHtml()+'<span data-cke-copybin-end="1">​</span>');c.fire("saveSnapshot");c.fire("lockSnapshot");g.append(f);c.editable().append(g);var i=c.on("selectionChange",F,null,null,0),h=a.repository.on("checkSelection",F,null,null,0);if(e)var j=d.getDocumentElement().$,k=j.scrollTop;d=c.createRange();d.selectNodeContents(f);d.select();e&&(j.scrollTop=k);setTimeout(function(){b||a.focus();g.remove();i.removeListener();h.removeListener();c.fire("unlockSnapshot");if(b){a.repository.del(a);c.fire("saveSnapshot")}},
100)}}function E(a){return(a=(a=a.getDefinition().attributes)&&a["class"])?a.split(/\s+/):null}function G(){var a=CKEDITOR.document.getActive(),b=this.editor,c=b.editable();(c.isInline()?c:b.document.getWindow().getFrame()).equals(a)&&b.focusManager.focus(c)}function H(){CKEDITOR.env.gecko&&this.editor.unlockSelection();CKEDITOR.env.webkit||(this.editor.forceNextSelectionCheck(),this.editor.selectionChange(1))}function Y(a){var b=null;a.on("data",function(){var a=this.data.classes,d;if(b!=a){for(d in b)(!a||
!a[d])&&this.removeClass(d);for(d in a)this.addClass(d);b=a}})}function Z(a){if(a.draggable){var b=a.editor,c=a.wrapper.getLast(U),d;c?d=c.findOne("img"):(c=new CKEDITOR.dom.element("span",b.document),c.setAttributes({"class":"cke_reset cke_widget_drag_handler_container",style:"background:rgba(220,220,220,0.5);background-image:url("+b.plugins.widget.path+"images/handle.png)"}),d=new CKEDITOR.dom.element("img",b.document),d.setAttributes({"class":"cke_reset cke_widget_drag_handler","data-cke-widget-drag-handler":"1",
src:CKEDITOR.tools.transparentImageData,width:m,title:b.lang.widget.move,height:m}),a.inline&&d.setAttribute("draggable","true"),c.append(d),a.wrapper.append(c));a.wrapper.on("mouseenter",a.updateDragHandlerPosition,a);setTimeout(function(){a.on("data",a.updateDragHandlerPosition,a)},50);if(a.inline)d.on("dragstart",function(c){c.data.$.dataTransfer.setData("text",JSON.stringify({type:"cke-widget",editor:b.name,id:a.id}))});else d.on("mousedown",$,a);a.dragHandlerContainer=c}}function $(){function a(){var a;
for(j.reset();a=g.pop();)a.removeListener();var c=i,b=this.repository.finder;a=this.repository.liner;var d=this.editor,e=this.editor.editable();CKEDITOR.tools.isEmpty(a.visible)||(c=b.getRange(c[0]),this.focus(),d.fire("saveSnapshot"),d.fire("lockSnapshot",{dontUpdate:1}),d.getSelection().reset(),e.insertElementIntoRange(this.wrapper,c),this.focus(),d.fire("unlockSnapshot"),d.fire("saveSnapshot"));e.removeClass("cke_widget_dragging");a.hideVisible()}var b=this.repository.finder,c=this.repository.locator,
d=this.repository.liner,e=this.editor,f=e.editable(),g=[],i=[],h=b.greedySearch(),j=CKEDITOR.tools.eventsBuffer(50,function(){k=c.locate(h);i=c.sort(l,1);i.length&&(d.prepare(h,k),d.placeLine(i[0]),d.cleanup())}),k,l;f.addClass("cke_widget_dragging");g.push(f.on("mousemove",function(a){l=a.data.$.clientY;j.input()}));g.push(e.document.once("mouseup",a,this));g.push(CKEDITOR.document.once("mouseup",a,this))}function aa(a){var b,c,d=a.editables;a.editables={};if(a.editables)for(b in d)c=d[b],a.initEditable(b,
"string"==typeof c?{selector:c}:c)}function ba(a){if(a.mask){var b=a.wrapper.findOne(".cke_widget_mask");b||(b=new CKEDITOR.dom.element("img",a.editor.document),b.setAttributes({src:CKEDITOR.tools.transparentImageData,"class":"cke_reset cke_widget_mask"}),a.wrapper.append(b));a.mask=b}}function ca(a){if(a.parts){var b={},c,d;for(d in a.parts)c=a.wrapper.findOne(a.parts[d]),b[d]=c;a.parts=b}}function Q(a,b){da(a);ca(a);aa(a);ba(a);Z(a);Y(a);if(CKEDITOR.env.ie&&9>CKEDITOR.env.version)a.wrapper.on("dragstart",
function(c){var b=c.data.getTarget();!l(a,b)&&(!a.inline||!(b.type==CKEDITOR.NODE_ELEMENT&&b.hasAttribute("data-cke-widget-drag-handler")))&&c.data.preventDefault()});a.wrapper.removeClass("cke_widget_new");a.element.addClass("cke_widget_element");a.on("key",function(b){b=b.data.keyCode;if(13==b)a.edit();else{if(b==CKEDITOR.CTRL+67||b==CKEDITOR.CTRL+88){B(a,b==CKEDITOR.CTRL+88);return}if(b in ea||CKEDITOR.CTRL&b||CKEDITOR.ALT&b)return}return!1},null,null,999);a.on("doubleclick",function(b){a.edit()&&
b.cancel()});if(b.data)a.on("data",b.data);if(b.edit)a.on("edit",b.edit)}function da(a){(a.wrapper=a.element.getParent()).setAttribute("data-cke-widget-id",a.id)}function s(a){a.element.data("cke-widget-data",encodeURIComponent(JSON.stringify(a.data)))}var m=15;CKEDITOR.plugins.add("widget",{lang:"ar,ca,cs,cy,de,el,en,en-gb,eo,es,fa,fi,fr,gl,he,hr,hu,it,ja,km,ko,nb,nl,no,pl,pt,pt-br,ru,sl,sv,tt,uk,vi,zh,zh-cn",requires:"lineutils,clipboard",onLoad:function(){CKEDITOR.addCss(".cke_widget_wrapper{position:relative;outline:none}.cke_widget_inline{display:inline-block}.cke_widget_wrapper:hover>.cke_widget_element{outline:2px solid yellow;cursor:default}.cke_widget_wrapper:hover .cke_widget_editable{outline:2px solid yellow}.cke_widget_wrapper.cke_widget_focused>.cke_widget_element,.cke_widget_wrapper .cke_widget_editable.cke_widget_editable_focused{outline:2px solid #ace}.cke_widget_editable{cursor:text}.cke_widget_drag_handler_container{position:absolute;width:"+
m+"px;height:0;left:-9999px;opacity:0.75;transition:height 0s 0.2s;line-height:0}.cke_widget_wrapper:hover>.cke_widget_drag_handler_container{height:"+m+"px;transition:none}.cke_widget_drag_handler_container:hover{opacity:1}img.cke_widget_drag_handler{cursor:move;width:"+m+"px;height:"+m+"px;display:inline-block}.cke_widget_mask{position:absolute;top:0;left:0;width:100%;height:100%;display:block}.cke_editable.cke_widget_dragging, .cke_editable.cke_widget_dragging *{cursor:move !important}")},beforeInit:function(a){a.widgets=
new o(a)},afterInit:function(a){var b=a.widgets.registered,c,d,e;for(d in b)c=b[d],(e=c.button)&&a.ui.addButton&&a.ui.addButton(CKEDITOR.tools.capitalize(c.name,!0),{label:e,command:c.name,toolbar:"insert,10"});V(a)}});o.prototype={MIN_SELECTION_CHECK_INTERVAL:500,add:function(a,b){b=CKEDITOR.tools.prototypedCopy(b);b.name=a;b._=b._||{};this.editor.fire("widgetDefinition",b);b.template&&(b.template=new CKEDITOR.template(b.template));R(this.editor,b);var c=b,d=c.upcast;if(d)if("string"==typeof d)for(d=
d.split(",");d.length;)this._.upcasts.push([c.upcasts[d.pop()],c.name]);else this._.upcasts.push([d,c.name]);return this.registered[a]=b},addUpcastCallback:function(a){this._.upcastCallbacks.push(a)},checkSelection:function(){var a=this.editor.getSelection(),b=a.getSelectedElement(),c=C(this),d;if(b&&(d=this.getByElement(b,!0)))return c.focus(d).select(d).commit();a=a.getRanges()[0];if(!a||a.collapsed)return c.commit();a=new CKEDITOR.dom.walker(a);for(a.evaluator=r;b=a.next();)c.select(this.getByElement(b));
c.commit()},checkWidgets:function(a){this.fire("checkWidgets",CKEDITOR.tools.copy(a||{}))},del:function(a){if(this.focused===a){var b=a.editor,c=b.createRange(),d;if(!(d=c.moveToClosestEditablePosition(a.wrapper,!0)))d=c.moveToClosestEditablePosition(a.wrapper,!1);d&&b.getSelection().selectRanges([c])}a.wrapper.remove();this.destroy(a,!0)},destroy:function(a,b){this.widgetHoldingFocusedEditable===a&&n(this,a,null,b);a.destroy(b);delete this.instances[a.id];this.fire("instanceDestroyed",a)},destroyAll:function(a){var b=
this.instances,c,d;for(d in b)c=b[d],this.destroy(c,a)},finalizeCreation:function(a){if((a=a.getFirst())&&r(a))this.editor.insertElement(a),a=this.getByElement(a),a.ready=!0,a.fire("ready"),a.focus()},getByElement:function(){var a={div:1,span:1};return function(b,c){if(!b)return null;var d=b.is(a)&&b.data("cke-widget-id");if(!c&&!d){var e=this.editor.editable();do b=b.getParent();while(b&&!b.equals(e)&&!(d=b.is(a)&&b.data("cke-widget-id")))}return this.instances[d]||null}}(),initOn:function(a,b,c){b?
"string"==typeof b&&(b=this.registered[b]):b=this.registered[a.data("widget")];if(!b)return null;var d=this.wrapElement(a,b.name);return d?d.hasClass("cke_widget_new")?(a=new k(this,this._.nextId++,a,b,c),a.isInited()?this.instances[a.id]=a:null):this.getByElement(a):null},initOnAll:function(a){for(var a=(a||this.editor.editable()).find(".cke_widget_new"),b=[],c,d=a.count();d--;)(c=this.initOn(a.getItem(d).getFirst(p)))&&b.push(c);return b},parseElementClasses:function(a){if(!a)return null;for(var a=
CKEDITOR.tools.trim(a).split(/\s+/),b,c={},d=0;b=a.pop();)-1==b.indexOf("cke_")&&(c[b]=d=1);return d?c:null},wrapElement:function(a,b){var c=null,d,e;if(a instanceof CKEDITOR.dom.element){d=this.registered[b||a.data("widget")];if(!d)return null;if((c=a.getParent())&&c.type==CKEDITOR.NODE_ELEMENT&&c.data("cke-widget-wrapper"))return c;a.hasAttribute("data-cke-widget-keep-attr")||a.data("cke-widget-keep-attr",a.data("widget")?1:0);b&&a.data("widget",b);e=z(d,a.getName());c=new CKEDITOR.dom.element(e?
"span":"div");c.setAttributes(x(e));c.data("cke-display-name",d.pathName?d.pathName:a.getName());a.getParent(!0)&&c.replace(a);a.appendTo(c)}else if(a instanceof CKEDITOR.htmlParser.element){d=this.registered[b||a.attributes["data-widget"]];if(!d)return null;if((c=a.parent)&&c.type==CKEDITOR.NODE_ELEMENT&&c.attributes["data-cke-widget-wrapper"])return c;"data-cke-widget-keep-attr"in a.attributes||(a.attributes["data-cke-widget-keep-attr"]=a.attributes["data-widget"]?1:0);b&&(a.attributes["data-widget"]=
b);e=z(d,a.name);c=new CKEDITOR.htmlParser.element(e?"span":"div",x(e));c.attributes["data-cke-display-name"]=d.pathName?d.pathName:a.name;d=a.parent;var f;d&&(f=a.getIndex(),a.remove());c.add(a);d&&y(d,f,c)}return c},_tests_getNestedEditable:l,_tests_createEditableFilter:u};CKEDITOR.event.implementOn(o.prototype);k.prototype={addClass:function(a){this.element.addClass(a)},applyStyle:function(a){D(this,a,1)},checkStyleActive:function(a){var a=E(a),b;if(!a)return!1;for(;b=a.pop();)if(!this.hasClass(b))return!1;
return!0},destroy:function(a){this.fire("destroy");if(this.editables)for(var b in this.editables)this.destroyEditable(b,a);a||("0"==this.element.data("cke-widget-keep-attr")&&this.element.removeAttribute("data-widget"),this.element.removeAttributes(["data-cke-widget-data","data-cke-widget-keep-attr"]),this.element.removeClass("cke_widget_element"),this.element.replace(this.wrapper));this.wrapper=null},destroyEditable:function(a,b){var c=this.editables[a];c.removeListener("focus",H);c.removeListener("blur",
G);this.editor.focusManager.remove(c);b||(c.removeClass("cke_widget_editable"),c.removeClass("cke_widget_editable_focused"),c.removeAttributes(["contenteditable","data-cke-widget-editable","data-cke-enter-mode"]));delete this.editables[a]},edit:function(){var a={dialog:this.dialog},b=this;if(!1===this.fire("edit",a)||!a.dialog)return!1;this.editor.openDialog(a.dialog,function(a){var d,e;!1!==b.fire("dialog",a)&&(d=a.on("show",function(){a.setupContent(b)}),e=a.on("ok",function(){var d,e=b.on("data",
function(a){d=1;a.cancel()},null,null,0);b.editor.fire("saveSnapshot");a.commitContent(b);e.removeListener();d&&(b.fire("data",b.data),b.editor.fire("saveSnapshot"))}),a.once("hide",function(){d.removeListener();e.removeListener()}))});return!0},getClasses:function(){return this.repository.parseElementClasses(this.element.getAttribute("class"))},hasClass:function(a){return this.element.hasClass(a)},initEditable:function(a,b){var c=this.wrapper.findOne(b.selector);return c&&c.is(CKEDITOR.dtd.$editable)?
(c=new q(this.editor,c,{filter:u.call(this.repository,this.name,a,b)}),this.editables[a]=c,c.setAttributes({contenteditable:"true","data-cke-widget-editable":a,"data-cke-enter-mode":c.enterMode}),c.filter&&c.data("cke-filter",c.filter.id),c.addClass("cke_widget_editable"),c.removeClass("cke_widget_editable_focused"),b.pathName&&c.data("cke-display-name",b.pathName),this.editor.focusManager.add(c),c.on("focus",H,this),CKEDITOR.env.ie&&c.on("blur",G,this),c.setData(c.getHtml()),!0):!1},isInited:function(){return!(!this.wrapper||
!this.inited)},isReady:function(){return this.isInited()&&this.ready},focus:function(){var a=this.editor.getSelection();if(a){var b=this.editor.checkDirty();a.fake(this.wrapper);!b&&this.editor.resetDirty()}this.editor.focus()},removeClass:function(a){this.element.removeClass(a)},removeStyle:function(a){D(this,a,0)},setData:function(a,b){var c=this.data,d=0;if("string"==typeof a)c[a]!==b&&(c[a]=b,d=1);else{var e=a;for(a in e)c[a]!==e[a]&&(d=1,c[a]=e[a])}d&&this.dataReady&&(s(this),this.fire("data",
c));return this},setFocused:function(a){this.wrapper[a?"addClass":"removeClass"]("cke_widget_focused");this.fire(a?"focus":"blur");return this},setSelected:function(a){this.wrapper[a?"addClass":"removeClass"]("cke_widget_selected");this.fire(a?"select":"deselect");return this},updateDragHandlerPosition:function(){var a=this.editor,b=this.element.$,c=this._.dragHandlerOffset,b={x:b.offsetLeft,y:b.offsetTop-m};if(!c||!(b.x==c.x&&b.y==c.y))c=a.checkDirty(),a.fire("lockSnapshot"),this.dragHandlerContainer.setStyles({top:b.y+
"px",left:b.x+"px"}),a.fire("unlockSnapshot"),!c&&a.resetDirty(),this._.dragHandlerOffset=b}};CKEDITOR.event.implementOn(k.prototype);q.prototype=CKEDITOR.tools.extend(CKEDITOR.tools.prototypedCopy(CKEDITOR.dom.element.prototype),{setData:function(a){a=this.editor.dataProcessor.toHtml(a,{context:this.getName(),filter:this.filter,enterMode:this.enterMode});this.setHtml(a);this.editor.widgets.initOnAll(this)},getData:function(){return this.editor.dataProcessor.toDataFormat(this.getHtml(),{context:this.getName(),
filter:this.filter,enterMode:this.enterMode})}});var X=RegExp('^(?:<(?:div|span)(?: data-cke-temp="1")?(?: id="cke_copybin")?(?: data-cke-temp="1")?>)?(?:<(?:div|span)(?: style="[^"]+")?>)?<span [^>]*data-cke-copybin-start="1"[^>]*>.?</span>([\\s\\S]+)<span [^>]*data-cke-copybin-end="1"[^>]*>.?</span>(?:</(?:div|span)>)?(?:</(?:div|span)>)?$'),ea={37:1,38:1,39:1,40:1,8:1,46:1};(function(){function a(){}function b(a,b,e){return!e||!this.checkElement(a,e)?!1:(a=e.widgets.getByElement(a,!0))&&a.checkStyleActive(this)}
CKEDITOR.style.addCustomHandler({type:"widget",setup:function(a){this.widget=a.widget},apply:function(a){a instanceof CKEDITOR.editor&&this.checkApplicable(a.elementPath(),a)&&a.widgets.focused.applyStyle(this)},remove:function(a){a instanceof CKEDITOR.editor&&this.checkApplicable(a.elementPath(),a)&&a.widgets.focused.removeStyle(this)},checkActive:function(a,b){return this.checkElementMatch(a.lastElement,0,b)},checkApplicable:function(a,b){return!(b instanceof CKEDITOR.editor)?!1:this.checkElement(a.lastElement,
b)},checkElementMatch:b,checkElementRemovable:b,checkElement:function(a){return!r(a)?!1:(a=a.getFirst(p))&&a.data("widget")==this.widget},buildPreview:function(a){return a||this._.definition.name},toAllowedContentRules:function(a){if(!a)return null;var a=a.widgets.registered[this.widget],b,e={};if(!a)return null;if(a.styleableElements){b=this.getClassesArray();if(!b)return null;e[a.styleableElements]={classes:b,propertiesOnly:!0};return e}return a.styleToAllowedContentRules?a.styleToAllowedContentRules(this):
null},getClassesArray:function(){var a=this._.definition.attributes&&this._.definition.attributes["class"];return a?CKEDITOR.tools.trim(a).split(/\s+/):null},applyToRange:a,removeFromRange:a,applyToObject:a})})();CKEDITOR.plugins.widget=k;k.repository=o;k.nestedEditable=q})();