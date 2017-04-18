<?php
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
	
$params = JFactory::getApplication()->getTemplate(true)->params;

if ($this->language == 'vi-vn'){
	include __DIR__.'/errorvn.php';return;

}




?>



<!-- saved from url=(0034)//pacificcross.com.vn/ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" slick-uniqueid="3" class="wf-opensans-i3-active wf-opensans-i4-active wf-opensans-i7-active wf-opensans-i8-active wf-opensans-n4-active wf-opensans-n3-active wf-opensans-n7-active wf-opensans-n8-active wf-active"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--<base href="//pacificcross.com.vn/">--><base href=".">
  
  <meta http-equiv="cleartype" content="on">
  <meta name="generator" content="Joomla! - Open Source Content Management">
  <title>Home</title>
  <link href="//pacificcross.com.vn/?format=feed&amp;type=rss" rel="alternate" type="application/rss+xml" title="RSS 2.0">
  <link href="//pacificcross.com.vn/?format=feed&amp;type=atom" rel="alternate" type="application/atom+xml" title="Atom 1.0">
  <link href="//pacificcross.com.vn/templates/novel/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
  <link href="//pacificcross.com.vn/component/search/?Itemid=265&amp;format=opensearch" rel="search" title="Search Pacific Cross VietNam" type="application/opensearchdescription+xml">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/bootstrap-responsive.min.css" type="text/css">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/bootstrap-extended.css" type="text/css">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/text.css" type="text/css" media="screen,projection">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/layout.css" type="text/css" media="screen,projection">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/nav.css" type="text/css" media="screen,projection">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/typography.css" type="text/css" media="screen,projection">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/template.css" type="text/css" media="screen,projection">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/responsive-template.css" type="text/css" media="screen,projection">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/print.css" type="text/css" media="print">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/style-modules.min.css" type="text/css">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/jquery-ui-1.9.2.css" type="text/css">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/flexi_filters.css" type="text/css">
  <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/template(1).css" type="text/css">
  <script src="//pacificcross.com.vn/Home_files/webfont.js.download" type="text/javascript" async=""></script><script src="//pacificcross.com.vn/Home_files/jquery.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery-noconflict.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery-migrate.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/caption.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/mootools-core.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/core.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/mootools-more.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/bootstrap.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/bootloader.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery.ui.core.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery.ui.sortable.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery.ui.dialog.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery.ui.menu.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery.ui.autocomplete.min.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/tmpl-common.js.download" type="text/javascript"></script>
  <script src="//pacificcross.com.vn/Home_files/jquery-easing.js.download" type="text/javascript"></script>
  <script type="text/javascript">
jQuery(window).on('load',  function() {
				new JCaption('img.caption');
			});
					var _FC_GET = {"layout":null,"cid":"","option":"com_content","view":"featured","clayout":"blog","limitstart":0};
				
jQuery(function($) {
			 $('.hasTip').each(function() {
				var title = $(this).attr('title');
				if (title) {
					var parts = title.split('::', 2);
					$(this).data('tip:title', parts[0]);
					$(this).data('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($('.hasTip').get(), {"maxTitleChars": 50,"fixed": false});
		});
		jQuery(document).ready(function() {
			jQuery("#moduleFCform_117 input:not(.fc_autosubmit_exclude), #moduleFCform_117 select:not(.fc_autosubmit_exclude)").on("change", function() {
				var form=document.getElementById("moduleFCform_117");
				adminFormPrepare(form, 1);
			});
			jQuery("#moduleFCform_117").attr("data-fc-autosubmit", "1");
		});
	
		jQuery(document).ready(function() {
			jQuery("#moduleFCform_117 .fc_button.button_reset").on("click", function() {
				jQuery("#moduleFCform_117_filter_box .use_select2_lib").select2("val", "");
			});
		});
	
  </script>
  <script type="text/javascript">
    (function() {
      Joomla.JText.load({"FLEXI_APPLYING_FILTERING":"Applying Filtering","FLEXI_TYPE_TO_LIST":"Search Medical Providers","FLEXI_TYPE_TO_FILTER":" ... type to filter"});
    })();
  </script>
  <meta name="FD50:EasyBlog" content="compressed,5.0.30,//pacificcross.com.vn/?option=com_easyblog&amp;Itemid=265,,5a027f81038c12ea5081c54072dbebbe,//pacificcross.com.vn/?option=com_easyblog&amp;Itemid=265">
  <meta name="FD50" content="static,compressed,//pacificcross.com.vn/media/foundry/5.0,,.min.js,,//pacificcross.com.vn,//pacificcross.com.vn,//pacificcross.com.vn/index.php,site,3.4.0,,,Pacific Cross VietNam,en-GB">
  <script defer="" async="" src="//pacificcross.com.vn/Home_files/module-5.0.30.static.min.js.download"></script>

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="/bluecross/templates/novel/js/selectivizr-min.js"></script>
<script src="/bluecross/templates/novel/js/modernizr.js"></script>
<link rel="stylesheet" type="text/css" href="/bluecross/templates/novel/css/ie.css" media="screen" />
<![endif]-->
<script type="text/javascript">
WebFontConfig = {
google: { families: [ 'Open+Sans:300italic,400italic,700italic,800italic,400,300,700,800:latin' ] }
};
(function() {
var wf = document.createElement('script');
wf.src = ('' == document.location.protocol ? 'https' : 'http') +
'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
wf.type = 'text/javascript';
wf.async = 'true';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(wf, s);
})(); </script>
<script src="//pacificcross.com.vn/Home_files/selectnav.min.js.download"></script>
<!--[if IE 6]> <link rel="stylesheet" type="text/css" href="/bluecross/templates/novel/css/ie6.css" media="screen" /> <![endif]-->
<!--[if IE 7]> <link rel="stylesheet" type="text/css" href="/bluecross/templates/novel/css/ie.css" media="screen" /> <![endif]-->
<link rel="stylesheet" type="text/css" href="//pacificcross.com.vn/Home_files/template-colors.css">
<style>@import url('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');</style>	
 
<script type="text/javascript" src="//pacificcross.com.vn/Home_files/superfish.js.download"></script>
<script type="text/javascript" src="//pacificcross.com.vn/Home_files/supersubs.js.download"></script>
<script type="text/javascript">
jQuery(document).ready(function(){ 
jQuery("ul.menu-nav").supersubs({ 
minWidth: 10,
extraWidth:  1
}).superfish({ 
delay:500,
animation:{opacity:'show',height:'show',width:'show'},
speed:'normal',
autoArrows:true,
dropShadows:false 
});
}); 
</script>
<script type="text/javascript">
jQuery(document).ready(function() {
    
    jQuery('ul.menu-nav').prepend('<span class="closemenu" >X</span>')
    
jQuery('#menupanel').on('click', function() {
jQuery('ul.menu-nav').animate({
'width': 'show'
}, 300, function() {
jQuery('ul.menu-nav').fadeIn(200);
});
});
jQuery('span.closemenu').on('click', function() {
jQuery('ul.menu-nav').fadeOut(200, function() {
jQuery('ul.menu-nav').animate({
'width': 'hide'
}, 300);
});
});
});
</script>
<style>

</style>
<script type="text/javascript" src="//pacificcross.com.vn/Home_files/hide.js.download"></script> 
	
<style type="text/css">
	body {font-size: 16px;}
	#sn-position{height:60px; }
	.sn-underline {margin-top:40px; }
	#sn-position #h1{top:-8px;color:#2a2a2a;font-size:30px;}
	#sn-position #h1 a {color:#2a2a2a;}
	#sn-position #h2 {top:44px;color:#2a2a2a;font-size:10px;}
	#sn-position #h1 {text-align: center;width: 100%;left:0px;}	#sn-position #h2 {text-align: center;width: 100%;left:0px;}	ul.columns-2 {width: 300px !important;}
	ul.columns-3 {width: 500px !important;}
	ul.columns-4 {width: 660px !important;}
	ul.columns-5 {width: 800px !important;}
	
	
</style>

<script src="//pacificcross.com.vn/Home_files/jquery-ui.js.download"></script>
 <link rel="stylesheet" href="//pacificcross.com.vn/Home_files/jquery-ui.css">
<script>

jQuery(document).ready(function() { 
    
           
	   
	 var html = jQuery(".mod-easyblogsearch").html();
	 
	 jQuery('<div class="blogsearch" >'+html+'</div>').insertAfter('.eb-brand');
	 jQuery('.eb-category-profile').remove();
	 
jQuery("#module_125").append('<a class="companyblog" href="//pacificcross.com.vn/eblog/categories/health.html">View all blogs</a> ');
jQuery("#module_161").append('<a class="companyblog" href="//pacificcross.com.vn/eblog/categories/company.html">View all blogs</a> ');

              jQuery('.tabouter').prepend('<div class="newfeedmodule" style="background: #fff !important;    color: #0d4fa4 !important;    padding: 7px 5px;    margin-bottom: 2px;    width: 95%;    font-weight: bold;    text-transform: uppercase;" >PCV Newsfeed</div>');
    
                      
  
     setTimeout(function(){ jQuery('.mapside').css('width',"44%"); },1000);

 jQuery('.blocksearch').hide();
 jQuery('.item-274 , .item-373 ').click( function() {
     if (jQuery(this).hasClass('open')){
         jQuery(this).removeClass('open');
         jQuery('.blocksearch').hide();
     }else {
         jQuery('.blocksearch').fadeIn('slow');
         jQuery(this).addClass('open');
     }
     
 });
 
if(jQuery('body').hasClass('medical_providers_xxx')){
              jQuery('<div class="dsada"  style="     width: 55%;    text-align: right;        cursor: pointer;      margin-bottom:0px;     margin-right: -14px;"> <div class="detalsss" style="    margin-right: -75px;    opacity: 0.5;    margin-bottom: -8px;"  >Drag To Expand View</div>  <a href="#"  style="   "  ><img src="//pacificcross.com.vn/images/slider-arrows4.png" /> </a> </div>').insertAfter(".barsearch");
jQuery( ".dsada" ).draggable(
      { axis: "x" },
      
      {
      start: function() {
          jQuery('.detalsss').hide();
      },
      drag: function() {
         
      },
      stop: function() {
       var wd =  jQuery('.dsada').width();
         var wdd = jQuery( window ).width();
         var lef =  jQuery(".dsada").css('left');
         lef =  lef.replace("px", "");
          
         var wd = parseInt(wd) + parseInt(lef);
         var aa = wdd - wd - 20;
        
        // var bb = wdd - wd;
         jQuery(".infoside").css('width',wd+'px');
          jQuery(".dsada").css('width',wd+'px');
             jQuery(".dsada").css('left','0px');
        
         jQuery(".mapside").css('width',aa+'px');
          jQuery('.detalsss').show();
              //  google.maps.event.trigger(window, 'resize');
            //  google.maps.event.addDomListener(window, 'load', initialize());
      }
    });


}

                       var english = /^[A-Za-z0-9]*$/;
                       jQuery('.mod-items-compact .tag-cloud').each(function(){
                           if (!english.test(jQuery(this).text())){
                                                                      jQuery(this).parents().eq(1).hide();
                                                            }else{
                                                                       jQuery(this).parents().eq(1).show();
                                                            }
                          
                       });
                       
                       

});


</script>
     <style>
         .dsada a:hover {
             text-decoration: none!important;
              opacity: 1;
         }
         .dsada a {
               font-weight: bold;
      margin-right: -53px;
    
    padding: 9px;
   
    color: #fff!important;
    padding-top: 10px;
    padding-bottom: 10px;
        opacity: 0.3;
         }
         
         
              </style><link rel="stylesheet" href="//pacificcross.com.vn/Home_files/css" media="all">
         

<style type="text/css" title="" media="all"></style></head>
<body class=""><span id="fc_filter_form_blocker"><span class="fc_blocker_opacity"></span><span class="fc_blocker_content">Applying Filtering<div class="fc_blocker_bar"><div></div></div></span></span>
   
<header id="top-handler">
	
	<div id="top">
				<div class="container">
                    <div class="row-fluid" style="position: relative;">
                         <div style="    position: absolute;    top: -25px;    z-index: 1000;    right: 0px; ">  <div class="mod-languages switcher">

	<ul class="lang-inline">
						<li class="" dir="ltr">
			<a href="//pacificcross.com.vn/vi/">
							<img src="//pacificcross.com.vn/Home_files/vi.gif" alt="Tiếng Việt (VN)" title="Tiếng Việt (VN)">						</a>
			</li>
								<li class="lang-active" dir="ltr">
			<a href="//pacificcross.com.vn/#">
							<img src="//pacificcross.com.vn/Home_files/en.gif" alt="English (UK)" title="English (UK)">						</a>
			</li>
				</ul>

</div>
<script>
	jQuery(document).ready(function(){
	
	jQuery(".lang-active a").attr("href",'#');

	});	

</script> </div>
				<div id="site-name-handler" class="span5">
					<div id="sn-position">
																		<div id="h1"> <a href="//pacificcross.com.vn"><img alt="" src="//pacificcross.com.vn/Home_files/logo.png"></a></div>
																								<div id="h2">  </div>
											</div>
				</div>
				<div id="top-nav-handler" class="span6">
					<div id="top-quick-nav">
																		

<div class="custom contact_top">
	<div id="contact-right">
<div class="callus" style="width: 100%; text-align: right;">
&nbsp;Call Us Free: <b>1800.577.770 </b></div>

<div class="shsuj" style="text-align: right;">
<div class="tab1">
Get a Quote
<div class="taba">
<ul>
<li>
<a href="//pacificcross.com.vn/get-quotation.html">Health</a></li>
<li>
<a href="//pacificcross.com.vn/travel-insurance-quotation.html">Travel</a></li>
<li>
<a href="//pacificcross.com.vn/products/group.html">Group</a></li>
</ul>
</div>
</div>
&nbsp;<span class="sercal">or&nbsp;</span>

<div class="tab2">
Apply
<div class="tabb">
<ul>
<li>
<a href="//pacificcross.com.vn/form-application.html">Health</a></li>
<li>
<a href="//pacificcross.com.vn/travel-insurance-application.html">Travel</a></li>
<li>
<a href="//pacificcross.com.vn/products/group.html">Group</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>

											</div>
					<div class="clear"></div>
									</div>
			</div>
		</div>
	</div>
	<div class="clear"> </div>
</header>
<div id="nav-handler">
	<div class="container">
		<div class="row-fluid">
						<div id="menu-handler" class="span12">
				<nav id="menu">
					    <a id="menupanel" href="javascript:void(0);"><span class="s1 rm-line"></span><span class="s2 rm-line"></span><span class="s3 rm-line"></span></a>
					
<ul class="menu-nav sf-js-enabled"><span class="closemenu">X</span>
<li class="item-101"><a href="//pacificcross.com.vn/"><span class="link-no-image">Home</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-103"><a href="//pacificcross.com.vn/about-us.html"><span class="link-no-image">About Us</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-414 deeper parent"><a href="//pacificcross.com.vn/products.html" class="sf-with-ul"><span class="link-no-image">Our Products</span><span class="tc-border"><span class="tc-arrow"></span></span><span class="sf-sub-indicator"> »</span></a><ul style="float: none; width: 10em; display: none; visibility: hidden;"><li class="item-104" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/products/medical-plans.html" style="float: none; width: auto;"><span class="link-no-image">Health</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-220" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/products/travel.html" style="float: none; width: auto;"><span class="link-no-image">Travel</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-221" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/products/group.html" style="float: none; width: auto;"><span class="link-no-image">Group</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="clear" style="white-space: normal; float: left; width: 100%;"> </li></ul></li><li class="item-105 deeper parent"><a href="//pacificcross.com.vn/customer-center.html" class="sf-with-ul"><span class="link-no-image">Customer center </span><span class="tc-border"><span class="tc-arrow"></span></span><span class="sf-sub-indicator"> »</span></a><ul style="float: none; width: 11.125em; display: none; visibility: hidden;"><li class="item-284" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/medical-providers-res.html?filter_261=Vietnam&amp;layoutv=1" style="float: none; width: auto;"><span class="link-no-image">Medical Providers</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-415 deeper parent" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/#" class="sf-with-ul" style="float: none; width: auto;"><span class="link-no-image">Resources</span><span class="tc-border"><span class="tc-arrow"></span></span><span class="sf-sub-indicator"> »</span></a><ul style="left: 11.125em; float: none; width: 25em; display: none; visibility: hidden;"><li class="item-420" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/resources/forms.html" style="float: none; width: auto;"><span class="link-no-image">Forms</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-421" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/resources/emergency-assistance-evacuation-information.html" style="float: none; width: auto;"><span class="link-no-image">Emergency Assistance &amp; Evacuation Information</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-422" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/resources/guarantees-of-payment-information.html" style="float: none; width: auto;"><span class="link-no-image">Guarantees Of Payment Information</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-423" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/resources/claim-procedure-information.html" style="float: none; width: auto;"><span class="link-no-image">Claim Procedure Information</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-424" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/resources/health-wellness-information.html" style="float: none; width: auto;"><span class="link-no-image">Health &amp; Wellness Information</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="clear" style="white-space: normal; float: left; width: 100%;"> </li></ul></li><li class="item-416" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/glossary.html" style="float: none; width: auto;"><span class="link-no-image">Glossary</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-417" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/customer-center/faq.html" style="float: none; width: auto;"><span class="link-no-image">FAQ</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="clear" style="white-space: normal; float: left; width: 100%;"> </li></ul></li><li class="item-106"><a href="//pacificcross.com.vn/agent-broken-resources.html"><span class="link-no-image">Agent/Broker Resources</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-108 deeper parent"><a href="//pacificcross.com.vn/contact-us.html" class="sf-with-ul"><span class="link-no-image">Contact</span><span class="tc-border"><span class="tc-arrow"></span></span><span class="sf-sub-indicator"> »</span></a><ul style="float: none; width: 10em; display: none; visibility: hidden;"><li class="item-418 deeper parent" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/#" class="sf-with-ul" style="float: none; width: auto;"><span class="link-no-image">Inquiries</span><span class="tc-border"><span class="tc-arrow"></span></span><span class="sf-sub-indicator"> »</span></a><ul style="left: 10em; float: none; width: 18.875em; display: none; visibility: hidden;"><li class="item-425" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/contact-us/inquiries/claims-customer-service-inquiries.html" style="float: none; width: auto;"><span class="link-no-image">Claims &amp; Customer Service Inquiries</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-426" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/contact-us/inquiries/sales.html" style="float: none; width: auto;"><span class="link-no-image">Sales</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="clear" style="white-space: normal; float: left; width: 100%;"> </li></ul></li><li class="item-419" style="white-space: normal; float: left; width: 100%;"><a href="//pacificcross.com.vn/contact-us/careers.html" style="float: none; width: auto;"><span class="link-no-image">Careers</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="clear" style="white-space: normal; float: left; width: 100%;"> </li></ul></li><li class="item-274"><span class="separator"><img src="//pacificcross.com.vn/Home_files/toolbar_find.png" alt="Search"><span class="image-title">Search</span> </span>
</li></ul>

<form action="//pacificcross.com.vn/" method="post">
	<div class="search blocksearch" style="display: none;">
				
		<input name="searchword" id="mod-search-searchword" maxlength="200" class="inputbox blocksearch" type="text" size="20" value=" " onblur="if (this.value==&#39;&#39;) this.value=&#39; &#39;;" onfocus="if (this.value==&#39; &#39;) this.value=&#39;&#39;;" style="display: none;"><input type="submit" value="Search" class="button blocksearch" onclick="this.form.searchword.focus();" style="display: none;">		
		
	<input type="hidden" name="task" value="search">
	<input type="hidden" name="option" value="com_search">
	<input type="hidden" name="Itemid" value="265">
	</div>
</form>
				</nav>
			</div>
								</div>
	</div>
</div>

	<div id="slideshow-handler-bg">
		<div id="slideshow-handler"> 
						<div class="sl-3rd-parties">
				

<div class="custom">
	<p><img src="//pacificcross.com.vn/Home_files/slide2.jpg" alt="" style="width: 100%;"></p></div>

			</div>
					</div>
	</div>

		<div id="main-handler-error" style="text-align: center;">
			<div class="clearfix text-formatted field field--name-field-paragraph-body field--type-text-long field--label-hidden field__item"><p class="text-align-center">We're sorry, the page or document you are looking for cannot be found.</p>

<p class="light text-align-center"><strong>Let us guide you in the right direction.</strong></p></div>

		</div>


    <footer id="footer">
	<div class="container">	
				<div class="row-fluid">
			<div class="span4" style="">		<div class="moduletable style_syan">
								<div class="module-content">

<div class="custom style_syan">
	<p style="text-align: center;"><img src="//pacificcross.com.vn/Home_files/logo-footer.png" alt=""></p>
<p>&nbsp;</p>
<div>
<p>4th Floor, Continental Tower, 81-83-85 Ham Nghi St., District 1, HCMC, Vietnam</p>
<p>Tel: (+84 8) 3821 9908</p>
<p>Fax: (+84 8) 3821 9847</p>
<p>Email: <a href="mailto:inquiry@pacificcross.com.vn">inquiry@pacificcross.com.vn</a></p>
</div></div>
</div>
		</div>
	</div>			<div class="span4" style="">		<div class="moduletable">
									<h3><span>ABOUT</span> US </h3>
					
								<div class="module-content">

<div class="custom">
	<p>Pacific Cross&nbsp;Vietnam is a Medical Insurance Administrator specializing in Health and Travel insurance in Asia . We are part of the Pacific Cross Group of companies with over 60 years' experience managing and designing travel and medical insurance.</p>
<p><a href="//pacificcross.com.vn/about-us.html">Find out more...</a></p></div>
</div>
		</div>
	</div>			<div class="span4" style="">		<div class="moduletable">
									<h3><span>Follow</span> Us</h3>
					
								<div class="module-content">

<div class="custom">
	<ul class="cus_socialmedia" data-lead-id="social-bar" id="cus_socialmedia">
<li class="facebook">
<a href="//www.facebook.com/pacificcrossvietnam/" target="_blank"><img src="//pacificcross.com.vn/Home_files/fb-icon.png"></a></li>
<li class="twitter">
<a href="//twitter.com/pacificcrossvn" target="_blank"><img src="//pacificcross.com.vn/Home_files/twitter-icon.png" target="_blank"></a></li>
<li class="google">
<a href="//plus.google.com/u/0/110234759110601144850" target="_blank"><img src="//pacificcross.com.vn/Home_files/gg-icon.png"></a></li>
<li class="youtube">
<a href="//www.youtube.com/channel/UCqbF6qDlEl8x47p1460FGdQ" target="_blank"><img src="//pacificcross.com.vn/Home_files/youtube-icon.png"></a></li>
<li class="linkedin">
<a href="//www.linkedin.com/company/blue-cross-vietnam" target="_blank"><img src="//pacificcross.com.vn/Home_files/linkedin-icon.png"></a></li>
<li class="instagram">
<a href="//www.instagram.com/pacificcrossvietnam/" target="_blank"><img src="//pacificcross.com.vn/Home_files/instagram.png"></a></li>
</ul>
</div>
</div>
		</div>
	</div>											</div>
				
	</div>
</footer>
    <div class="container">	
    <div id="footer-line" class="row-fluid">
									<div id="foo-left-right">
				<div class="span6">
<ul class="menu">
<li class="item-109"><a href="//pacificcross.com.vn/sitemap.html"><span class="link-no-image">SITEMAP</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-110"><a href="//pacificcross.com.vn/faq.html"><span class="link-no-image">FAQ</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-111"><a href="//pacificcross.com.vn/forms.html"><span class="link-no-image">FORMS</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li><li class="item-112"><a href="//pacificcross.com.vn/contact-us.html"><span class="link-no-image">CONTACT US</span><span class="tc-border"><span class="tc-arrow"></span></span></a></li></ul>

</div>				<div class="span6">

<div class="custom">
	<p><span style="font-size: 12.16px; line-height: 1.3em;">COPYRIGHT © 2016 PACIFIC CROSS VIETNAM (BLUE CROSS VIETNAM)</span></p></div>


  <script src="//pacificcross.com.vn/Home_files/jquery-ui.js.download"></script>
<style>
    .a12{
        border-radius: 3px 0px 0px 3px; clear: none; clip: auto; counter-increment: none; counter-reset: none; direction: inherit; float: none; font-family: inherit; font-style: inherit; font-variant: normal; font-weight: inherit; height: auto; letter-spacing: normal; line-height: inherit; list-style: inherit inside none; max-height: none; max-width: none; opacity: 1; padding: 0px; table-layout: auto; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: inherit; white-space: normal; width: 30px; word-spacing: normal; display: inline-block; margin: 0px; cursor: pointer; box-sizing: content-box; color: rgb(255, 255, 255); overflow: hidden; position: fixed; z-index: 999999999; font-size: 14px; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid; border-color: rgb(255, 255, 255); box-shadow: rgb(148, 136, 136) 0px 0px 3px 0px, white 0px 0px 3px 0px; min-height: 100px; min-width: 10px; right: 0px; top: 199.15px; background: none 0px 0px repeat scroll rgb(222, 20, 37);
    }
    .a13{
        clear: none; clip: auto; color: inherit; counter-increment: none; counter-reset: none; cursor: auto; direction: inherit; display: inline; float: none; font-family: inherit; font-size: inherit; font-style: inherit; font-variant: normal; font-weight: inherit; letter-spacing: normal; line-height: inherit; max-height: none; max-width: none; opacity: 1; table-layout: auto; text-align: inherit; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: visible; white-space: normal; word-spacing: normal; border-collapse: collapse; border-spacing: 0px; height: auto;  margin: 0px; list-style: none outside none; outline: none medium; overflow: hidden; position: fixed; width: 300px; z-index: 2147483645; border: 3px solid rgb(255, 255, 255); border-radius: 5px; box-shadow: rgb(148, 136, 136) 0px 0px 6px 0px, white 0px 0px 10px 0px; padding: 0px; box-sizing: content-box; background-image: none; background-attachment: scroll; background-color: white; background-position: 0px 0px; background-repeat: repeat;bottom: 10px;right: 0px;
    }
    .a14{
        border: medium none black; border-radius: 0px; clear: none; clip: auto; counter-increment: none; counter-reset: none; direction: inherit; float: none; font-family: inherit; font-style: inherit; font-variant: normal; font-weight: inherit; height: auto; letter-spacing: normal; line-height: inherit; list-style: inherit inside none; margin: 0px; max-height: none; max-width: none; opacity: 1; overflow: visible; position: static; table-layout: auto; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: inherit; white-space: normal; width: auto; word-spacing: normal; z-index: auto; color: rgb(255, 255, 255); padding: 5px 10px; box-shadow: rgb(148, 136, 136) 0px 0px 2px 0px, rgb(26, 135, 199) 0px 0px 2px 0px; text-align: justify; font-size: 14px; display: block; cursor: move; background: rgb(222, 20, 37);
    }
    #captchaResult {
    display: inline-block;
    width: 50px;
}
	
	.label{border:1px solid #000}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}.table-bordered th,.table-bordered td{border:1px solid #ddd!important}}@font-face{font-family:'Glyphicons Halflings';src:url(../fonts/glyphicons-halflings-regular.eot);src:url(../fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'),url(../fonts/glyphicons-halflings-regular.woff) format('woff'),url(../fonts/glyphicons-halflings-regular.ttf) format('truetype'),url(../fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format('svg')}.glyphicon{position:relative;top:1px;display:inline-block;font-family:'Glyphicons Halflings';font-style:normal;font-weight:400;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.glyphicon-asterisk:before{content:"\2a"}.glyphicon-plus:before{content:"\2b"}.glyphicon-euro:before,.glyphicon-eur:before{content:"\20ac"}.glyphicon-minus:before{content:"\2212"}.glyphicon-cloud:before{content:"\2601"}.glyphicon-envelope:before{content:"\2709"}.glyphicon-pencil:before{content:"\270f"}.glyphicon-glass:before{content:"\e001"}.glyphicon-music:before{content:"\e002"}.glyphicon-search:before{content:"\e003"}.glyphicon-heart:before{content:"\e005"}.glyphicon-star:before{content:"\e006"}.glyphicon-star-empty:before{content:"\e007"}.glyphicon-user:before{content:"\e008"}.glyphicon-film:before{content:"\e009"}.glyphicon-th-large:before{content:"\e010"}.glyphicon-th:before{content:"\e011"}.glyphicon-th-list:before{content:"\e012"}.glyphicon-ok:before{content:"\e013"}.glyphicon-remove:before{content:"\e014"}.glyphicon-zoom-in:before{content:"\e015"}.glyphicon-zoom-out:before{content:"\e016"}.glyphicon-off:before{content:"\e017"}.glyphicon-signal:before{content:"\e018"}.glyphicon-cog:before{content:"\e019"}.glyphicon-trash:before{content:"\e020"}.glyphicon-home:before{content:"\e021"}.glyphicon-file:before{content:"\e022"}.glyphicon-time:before{content:"\e023"}.glyphicon-road:before{content:"\e024"}.glyphicon-download-alt:before{content:"\e025"}.glyphicon-download:before{content:"\e026"}.glyphicon-upload:before{content:"\e027"}.glyphicon-inbox:before{content:"\e028"}.glyphicon-play-circle:before{content:"\e029"}.glyphicon-repeat:before{content:"\e030"}.glyphicon-refresh:before{content:"\e031"}.glyphicon-list-alt:before{content:"\e032"}.glyphicon-lock:before{content:"\e033"}.glyphicon-flag:before{content:"\e034"}.glyphicon-headphones:before{content:"\e035"}.glyphicon-volume-off:before{content:"\e036"}.glyphicon-volume-down:before{content:"\e037"}.glyphicon-volume-up:before{content:"\e038"}.glyphicon-qrcode:before{content:"\e039"}.glyphicon-barcode:before{content:"\e040"}.glyphicon-tag:before{content:"\e041"}.glyphicon-tags:before{content:"\e042"}.glyphicon-book:before{content:"\e043"}.glyphicon-bookmark:before{content:"\e044"}.glyphicon-print:before{content:"\e045"}.glyphicon-camera:before{content:"\e046"}.glyphicon-font:before{content:"\e047"}.glyphicon-bold:before{content:"\e048"}.glyphicon-italic:before{content:"\e049"}.glyphicon-text-height:before{content:"\e050"}.glyphicon-text-width:before{content:"\e051"}.glyphicon-align-left:before{content:"\e052"}.glyphicon-align-center:before{content:"\e053"}.glyphicon-align-right:before{content:"\e054"}.glyphicon-align-justify:before{content:"\e055"}.glyphicon-list:before{content:"\e056"}.glyphicon-indent-left:before{content:"\e057"}.glyphicon-indent-right:before{content:"\e058"}.glyphicon-facetime-video:before{content:"\e059"}.glyphicon-picture:before{content:"\e060"}.glyphicon-map-marker:before{content:"\e062"}.glyphicon-adjust:before{content:"\e063"}.glyphicon-tint:before{content:"\e064"}.glyphicon-edit:before{content:"\e065"}.glyphicon-share:before{content:"\e066"}.glyphicon-check:before{content:"\e067"}.glyphicon-move:before{content:"\e068"}.glyphicon-step-backward:before{content:"\e069"}.glyphicon-fast-backward:before{content:"\e070"}.glyphicon-backward:before{content:"\e071"}.glyphicon-play:before{content:"\e072"}.glyphicon-pause:before{content:"\e073"}.glyphicon-stop:before{content:"\e074"}.glyphicon-forward:before{content:"\e075"}.glyphicon-fast-forward:before{content:"\e076"}.glyphicon-step-forward:before{content:"\e077"}.glyphicon-eject:before{content:"\e078"}.glyphicon-chevron-left:before{content:"\e079"}.glyphicon-chevron-right:before{content:"\e080"}.glyphicon-plus-sign:before{content:"\e081"}.glyphicon-minus-sign:before{content:"\e082"}.glyphicon-remove-sign:before{content:"\e083"}.glyphicon-ok-sign:before{content:"\e084"}.glyphicon-question-sign:before{content:"\e085"}.glyphicon-info-sign:before{content:"\e086"}.glyphicon-screenshot:before{content:"\e087"}.glyphicon-remove-circle:before{content:"\e088"}.glyphicon-ok-circle:before{content:"\e089"}.glyphicon-ban-circle:before{content:"\e090"}.glyphicon-arrow-left:before{content:"\e091"}.glyphicon-arrow-right:before{content:"\e092"}.glyphicon-arrow-up:before{content:"\e093"}.glyphicon-arrow-down:before{content:"\e094"}.glyphicon-share-alt:before{content:"\e095"}.glyphicon-resize-full:before{content:"\e096"}.glyphicon-resize-small:before{content:"\e097"}.glyphicon-exclamation-sign:before{content:"\e101"}.glyphicon-gift:before{content:"\e102"}.glyphicon-leaf:before{content:"\e103"}.glyphicon-fire:before{content:"\e104"}.glyphicon-eye-open:before{content:"\e105"}.glyphicon-eye-close:before{content:"\e106"}.glyphicon-warning-sign:before{content:"\e107"}.glyphicon-plane:before{content:"\e108"}.glyphicon-calendar:before{content:"\e109"}.glyphicon-random:before{content:"\e110"}.glyphicon-comment:before{content:"\e111"}.glyphicon-magnet:before{content:"\e112"}.glyphicon-chevron-up:before{content:"\e113"}.glyphicon-chevron-down:before{content:"\e114"}.glyphicon-retweet:before{content:"\e115"}.glyphicon-shopping-cart:before{content:"\e116"}.glyphicon-folder-close:before{content:"\e117"}.glyphicon-folder-open:before{content:"\e118"}.glyphicon-resize-vertical:before{content:"\e119"}.glyphicon-resize-horizontal:before{content:"\e120"}.glyphicon-hdd:before{content:"\e121"}.glyphicon-bullhorn:before{content:"\e122"}.glyphicon-bell:before{content:"\e123"}.glyphicon-certificate:before{content:"\e124"}.glyphicon-thumbs-up:before{content:"\e125"}.glyphicon-thumbs-down:before{content:"\e126"}.glyphicon-hand-right:before{content:"\e127"}.glyphicon-hand-left:before{content:"\e128"}.glyphicon-hand-up:before{content:"\e129"}.glyphicon-hand-down:before{content:"\e130"}.glyphicon-circle-arrow-right:before{content:"\e131"}.glyphicon-circle-arrow-left:before{content:"\e132"}.glyphicon-circle-arrow-up:before{content:"\e133"}.glyphicon-circle-arrow-down:before{content:"\e134"}.glyphicon-globe:before{content:"\e135"}.glyphicon-wrench:before{content:"\e136"}.glyphicon-tasks:before{content:"\e137"}.glyphicon-filter:before{content:"\e138"}

.input-group-addon {
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    color: #555;
    text-align: center;
    background-color: #eee;
    border: 1px solid #ccc;
    border-radius: 4px;
}
	
		.input-group-addon, .input-group-btn {
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;
}
	.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: 'Glyphicons Halflings';
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
	.input-group {
    position: relative;
    display: table;
    border-collapse: separate;
}
	
	.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
	.input-group .form-control {
    position: relative;
    z-index: 2;
    float: left;
    width: 100%;
    margin-bottom: 0;
}
	.input-group-addon, .input-group-btn, .input-group .form-control {
    display: table-cell;
}
</style>
<script>
  function showhidew(a){
      if(a==1){
          jQuery('#dropifiContactTab').hide();
          jQuery('#dropifiContactContent').show();
      }else{
           jQuery('#dropifiContactTab').show();
           jQuery('#dropifiContactContent').hide();
      }
  }
//   jQuery(function() {
//    jQuery( "#dropifiContactContent" ).draggable({ handle: "p#dropifiTextContent" });
//    
//    jQuery( "div, p" ).disableSelection();
//  });
  </script>
 








  <div class="a12" id="dropifiContactTab" onclick="showhidew(1);">
     <div id="dropifi2012_version_inner_label" style="width:30px;margin-right:auto;margin-left:auto;padding-left:8px;"><img id="dropifi_widget_v1_imageText" style="padding:0px;display:inherit !important;border:0px;margin-top:5px;margin-bottom:8px;box-shadow: 0 0 0 transparent;cursor:pointer" src="//pacificcross.com.vn/Home_files/contactimg.png"></div></div>
 
 
 
 
 <div class="a13" style="display: none;" id="dropifiContactContent">
     
     
     <div class="a14" style="" id="dropifiContentBar">
         <p style="border: medium none black; border-radius: 0px; clear: none; clip: auto; color: rgb(255, 255, 255); counter-increment: none; counter-reset: none; direction: inherit; float: none; font-family: inherit; font-size: 14px; font-style: inherit; font-variant: normal; font-weight: normal; height: auto; letter-spacing: normal; line-height: inherit; list-style: inherit inside none; margin: 0px; max-height: none; max-width: none; opacity: 1; padding: 0px 5px 0px 0px; position: static; table-layout: auto; text-align: left; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: inherit; white-space: normal; word-spacing: normal; z-index: auto; overflow: hidden; cursor: move; display: inline-block; width: 90%; background-image: none; background-attachment: scroll; background-color: transparent; background-position: 0px 0px; background-repeat: repeat;" id="dropifiTextContent">Contact Us</p>
         
         <p style="background-attachment:scroll;background-color:transparent;background-image:none;background-position:0 0;background-repeat:repeat;border-color:black;border-radius:0;border-style:none;border-width:medium;clear:none;clip:auto;color:inherit;counter-increment:none;counter-reset:none;cursor:auto;direction:inherit;display:inline;float:none;font-family:inherit;font-size:inherit;font-style:inherit;font-variant:normal;font-weight:inherit;height:auto;letter-spacing:normal;line-height:inherit;list-style-image:none;list-style-position:inside;list-style-type:inherit;margin:0;max-height:none;max-width:none;opacity:1;overflow:visible;padding:0;position:static;table-layout:auto;text-align:inherit;text-decoration:inherit;text-transform:none;unicode-bidi:normal;vertical-align:baseline;visibility:inherit;white-space:normal;width:auto;word-spacing:normal;z-index:auto;float:right;cursor:pointer;position:relative;top:0px;margin-right:-5px;display:block;   font-weight: bold!important;" onclick="showhidew(2); "> X
         </p></div>
        <div id="dropifiIframeContainer" style="height: 270px; background-image: url(&#39;/bluecross/&quot;&quot;&#39;);">
            
   <div id="contentInner" style="font-family:SansSerif">
		<form style="font-family:SansSerif" id="fileupload" name="fileupload" action="//pacificcross.com.vn/index.php" method="POST" enctype="multipart/form-data">
			<div id="formContent">
                            <p id="header"> </p>
                            <p class="input-group"><span class="input-group-addon glyphicon glyphicon-envelope"></span>
                                <input id="widgetField_name" type="text" name="cs.name" class="form-control" placeholder="your name" required=""><span style="display:none" id="widgetError_email">your name is required</span> </p>
                            
                            <p class="input-group"><span class="input-group-addon glyphicon glyphicon-envelope"></span>
                                <input id="widgetField_email" type="email" name="cs.email" class="form-control" placeholder="your email" required=""><span style="display:none" id="widgetError_email">your email is required</span> </p>
                           
                            <!-- <p class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-pencil"></span>
                              
                                <select id="widgetField_subject" name="cs.subject" class="form-control" required="">
                                     <option  value="No Type" id="optCustomer Support">--Choice Product--</option>
                                                                             <option value="Customer Support" >Customer Support</option>
                                                                             <option value="Inquiries" >Inquiries</option>
                                                                             <option value="Job and Partnership" >Job and Partnership</option>
                                                                             <option value="Press" >Press</option>
                                                                       
                              -->  
                                  
                                
                                <span style="display:none" id="widgetError_subject">the subject of the message is required</span> 
                            
                            <p></p><p>
                                <textarea style="    height: 70px;" id="widgetField_message" name="cs.message" rows="3" class="form-control" cols="20" placeholder="how can we help you?" required=""></textarea>
                                <span style="display:none" id="widgetError_message">your message is required</span> 
                            </p>
                                                                                  <input id="captchaResult" name="cs.captchaResult" type="hidden" value="0">
                                                      <input type="hidden" id="captchaCode" name="cs.captchaCode" value="MA==">
                           <input type="hidden" id="url" name="mail_resent" value="inquiry@pacificross.com">
                           <input type="hidden" name="option" value="com_contact">
                           <input type="hidden" name="task" value="savewcontact">
                           
                            <input type="hidden" name="5a027f81038c12ea5081c54072dbebbe" value="1">                          <p class="dropifi_submit_input"> <input id="sendMail" style="background-color:#0D0B0B;color:#ffffff;border-color:#0D0B0B;    width: 100%;" class="btn btn-primary" name="sendMail" value="Send Message" type="submit"> </p></div>
			
		</form>
	</div>
    
    
    </div></div>
  
  
   </div>				<div class="clear"> </div>
			</div>
					</div>
    </div>



<style>	
	
	</style>	

<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-1" tabindex="0" style="display: none;"></ul><span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span></body></html>