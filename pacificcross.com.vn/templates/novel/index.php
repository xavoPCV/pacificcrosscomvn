<?php defined('_JEXEC') or die('Restricted access');
define('YOURBASEPATH',dirname(__FILE__));
JHtml::_('behavior.framework',true);
JHtml::_('bootstrap.framework');
$doc=JFactory::getDocument();
$doc->setMetaData('cleartype','on',true);
JHtml::_('bootstrap.loadCss',true,$this->direction);
$doc->addStyleSheet('templates/'.$this->template.'/css/text.css',$type='text/css',$media='screen,projection');
$doc->addStyleSheet('templates/'.$this->template.'/css/layout.css',$type='text/css',$media='screen,projection');
$doc->addStyleSheet('templates/'.$this->template.'/css/nav.css', $type='text/css',$media='screen,projection');
$doc->addStyleSheet('templates/'.$this->template.'/css/typography.css',$type='text/css',$media='screen,projection');
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css', $type='text/css',$media='screen,projection');
$doc->addStyleSheet('templates/'.$this->template.'/css/responsive-template.css',$type='text/css',$media='screen,projection');
$doc->addStyleSheet('templates/'.$this->template.'/css/print.css',$type='text/css',$media='print');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language;?>" lang="<?php echo $this->language;?>" dir="<?php echo $this->direction;?>">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<jdoc:include type="head"/>
<script type="text/javascript">
WebFontConfig={google:{families:['Open+Sans:300italic,400italic,700italic,800italic,400,300,700,800:latin']}};
(function(){var wf=document.createElement('script');wf.src=('https:'==document.location.protocol?'https':'http')+'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';wf.type='text/javascript';wf.async='true';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(wf,s);})();
</script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/js/selectnav.min.js"></script>
<?php if($this->params->get('usetheme')==true):?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl.'/templates/'.$this->template.'/css/presets/'.$this->params->get('choosetheme').'.css';?>"/>
<?php else:?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl.'/templates/'.$this->template.'/css/template-colors.css';?>"/>
<style>@import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');</style>	
<?php endif;?>
<?php if($this->params->get("usedropdown")):?> 
<script type="text/javascript" src="<?php echo $this->baseurl.'/templates/'.$this->template.'/js/superfish.js' ?>"></script>
<script type="text/javascript" src="<?php echo $this->baseurl.'/templates/'.$this->template.'/js/supersubs.js' ?>"></script>
<script type="text/javascript">
jQuery(document).ready(function(){jQuery("ul.menu-nav").supersubs({minWidth:<?php echo $this->params->get("dropdownhandler1");?>,extraWidth:1}).superfish({delay:500,animation:{opacity:'<?php if($this->params->get("dropopacity")):?>show<?php else:?>hide<?php endif;?>',height:'<?php if($this->params->get("dropheight")):?>show<?php else:?>hide<?php endif;?>',width:'<?php if($this->params->get("dropwidth")):?>show<?php else:?>hide<?php endif;?>'},speed:'<?php echo $this->params->get("dropspeed");?>',autoArrows:true,dropShadows:false});});
</script>
<?php endif;?>
<?php if($this->countModules('position-1')):?>
<script type="text/javascript">
jQuery(document).ready(function(){jQuery('ul.menu-nav').prepend('<span class="closemenu" >X</span>');jQuery('#menupanel').on('click',function(){jQuery('ul.menu-nav').animate({'width': 'show'},300,function(){jQuery('ul.menu-nav').fadeIn(200);});});jQuery('span.closemenu').on('click', function(){jQuery('ul.menu-nav').fadeOut(200,function(){jQuery('ul.menu-nav').animate({'width': 'hide'},300);});});});
</script>
<?php endif;?>
<?php echo $this->params->get("headcode");?>
<script type="text/javascript" src="<?php echo $this->baseurl.'/templates/'.$this->template;?>/js/hide.js"></script> 
<?php if($this->countModules('builtin-slideshow')):?>
<?php if($this->params->get("cam_turnOn")):?>
<link rel="stylesheet" id="camera-css" href="<?php echo $this->baseurl.'/templates/'.$this->template;?>/css/camera.css" type="text/css" media="all"/>
<script type="text/javascript"src="<?php echo $this->baseurl.'/templates/'.$this->template;?>/js/jquery.mobile.customized.min.js"></script>
<script type="text/javascript"src="<?php echo $this->baseurl.'/templates/'.$this->template;?>/js/jquery.easing.1.3.js"></script> 
<script type="text/javascript"src="<?php echo $this->baseurl.'/templates/'.$this->template;?>/js/camera.min.js"></script>
<script>
jQuery(function(){
jQuery('#ph-camera-slideshow').camera({
alignment:'topCenter',
autoAdvance:<?php if($this->params->get("cam_autoAdvance")):?>true<?php else:?>false<?php endif;?>,
mobileAutoAdvance:<?php if($this->params->get("cam_mobileAutoAdvance")):?>true<?php else:?>false<?php endif;?>, 
slideOn:'<?php if($this->params->get("cam_slideOn")): echo $this->params->get("cam_slideOn"); else:?>random<?php endif;?>',	
thumbnails:<?php if($this->params->get("cam_thumbnails")):?>true<?php else:?>false<?php endif;?>,
time:<?php if($this->params->get("cam_time")): echo $this->params->get("cam_time"); else:?>7000<?php endif;?>,
transPeriod:<?php if($this->params->get("cam_transPeriod")): echo $this->params->get("cam_transPeriod"); else:?>1500<?php endif;?>,
cols:<?php if($this->params->get("cam_cols")): echo $this->params->get("cam_cols"); else:?>10<?php endif;?>,
rows:<?php if($this->params->get("cam_rows")): echo $this->params->get("cam_rows"); else:?>10<?php endif;?>,
slicedCols:<?php if($this->params->get("cam_slicedCols")): echo $this->params->get("cam_slicedCols"); else:?>10<?php endif;?>,	
slicedRows:<?php if($this->params->get("cam_slicedRows")): echo $this->params->get("cam_slicedRows"); else:?>10<?php endif;?>,
fx:'<?php if($this->params->get("cam_fx_multiple_on")): echo $this->params->get("cam_fx_multi");else:echo $this->params->get("cam_fx");endif;?>',
gridDifference:<?php if($this->params->get("cam_gridDifference")): echo $this->params->get("cam_gridDifference"); else:?>250<?php endif;?>,
height:'<?php if($this->params->get("cam_height")): echo $this->params->get("cam_height"); else:?>50%<?php endif;?>',
minHeight:'<?php echo $this->params->get("cam_minHeight");?>',
imagePath:'<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/images/',	
hover:<?php if($this->params->get("cam_hover")):?>true<?php else:?>false<?php endif;?>,
loader:'<?php if($this->params->get("cam_loader")): echo $this->params->get("cam_loader"); else:?>pie<?php endif;?>',
barDirection:'<?php if($this->params->get("cam_barDirection")): echo $this->params->get("cam_barDirection"); else:?>leftToRight<?php endif;?>',
barPosition:'<?php if($this->params->get("cam_barPosition")): echo $this->params->get("cam_barPosition"); else:?>bottom<?php endif;?>',	
pieDiameter:<?php if($this->params->get("cam_pieDiameter")): echo $this->params->get("cam_pieDiameter"); else:?>38<?php endif;?>,
piePosition:'<?php if($this->params->get("cam_piePosition")): echo $this->params->get("cam_piePosition"); else:?>rightTop<?php endif;?>',
loaderColor:'<?php if($this->params->get("cam_loaderColor")): echo $this->params->get("cam_loaderColor"); else:?>#eeeeee<?php endif;?>', 
loaderBgColor:'<?php if($this->params->get("cam_loaderBgColor")): echo $this->params->get("cam_loaderBgColor"); else:?>#222222<?php endif;?>', 
loaderOpacity:<?php if($this->params->get("cam_loaderOpacity")): echo $this->params->get("cam_loaderOpacity"); else:?>8<?php endif;?>,
loaderPadding:<?php if($this->params->get("cam_loaderPadding")): echo $this->params->get("cam_loaderPadding"); else:?>2<?php endif;?>,
loaderStroke:<?php if($this->params->get("cam_loaderStroke")): echo $this->params->get("cam_loaderStroke"); else:?>7<?php endif;?>,
navigation:<?php if($this->params->get("cam_navigation")):?>true<?php else:?>false<?php endif;?>,
playPause:<?php if($this->params->get("cam_playPause")):?>true<?php else:?>false<?php endif;?>,
navigationHover:<?php if($this->params->get("cam_navigationHover")):?>true<?php else:?>false<?php endif;?>,
mobileNavHover:<?php if($this->params->get("cam_mobileNavHover")):?>true<?php else:?>false<?php endif;?>,
opacityOnGrid:<?php if($this->params->get("cam_opacityOnGrid")):?>true<?php else:?>false<?php endif;?>,
pagination:<?php if($this->params->get("cam_pagination")):?>true<?php else:?>false<?php endif;?>,
pauseOnClick:<?php if($this->params->get("cam_pauseOnClick")):?>true<?php else:?>false<?php endif;?>,
portrait:<?php if($this->params->get("cam_portrait")):?>true<?php else:?>false<?php endif;?>
});
});
</script>
<?php endif;?>
<?php endif;?>
<style type="text/css">
body {font-size: <?php echo $this->params->get('contentfontsize');?>;}
#sn-position{height:<?php echo $this->params->get('topheight');?>px;}
.sn-underline{margin-top:<?php echo $this->params->get('topheight')-20;?>px;}
#sn-position #h1{<?php if($this->params->get('H1TitlePositionX')<>"center"):?>left:<?php echo $this->params->get('H1TitlePositionX');?>px;<?php endif;?>top:<?php echo $this->params->get('H1TitlePositionY');?>px;color:<?php echo $this->params->get('sitenamecolor');?>;font-size:<?php echo $this->params->get('sitenamefontsize');?>;}
#sn-position #h1 a{color:<?php echo $this->params->get('sitenamecolor');?>;}
#sn-position #h2{<?php if($this->params->get('H1TitlePositionX') <> "center" ):?>left:<?php echo $this->params->get('H2TitlePositionX');?>px;<?php endif;?>top:<?php echo $this->params->get('H2TitlePositionY');?>px;color:<?php echo $this->params->get('slogancolor');?>;font-size:<?php echo $this->params->get('sloganfontsize');?>;}
<?php if($this->params->get('H1TitlePositionX')=="center"):?>#sn-position #h1{text-align:center;width:100%;left:0px;}<?php endif;?>
<?php if($this->params->get('H2TitlePositionX')=="center"):?>#sn-position #h2{text-align:center;width:100%;left:0px;}<?php endif;?>
ul.columns-2{width:<?php echo $this->params->get('dropdownhandler2');?>px!important;}
ul.columns-3{width:<?php echo $this->params->get('dropdownhandler3');?>px!important;}
ul.columns-4{width:<?php echo $this->params->get('dropdownhandler4');?>px!important;}
ul.columns-5{width:<?php echo $this->params->get('dropdownhandler5');?>px!important;}
<?php if($this->countModules('builtin-slideshow')): 
if($this->params->get("cam_turnOn")):?>
.camera_caption{top:<?php echo $this->params->get("cam_caption_y_position");?>;}
.camera_caption>div>div{width: <?php echo $this->params->get("cam_caption_width");?>;}
.camera_pie{
width:<?php if($this->params->get("cam_pieDiameter")):echo $this->params->get("cam_pieDiameter"); else:?>38<?php endif;?>px;
height:<?php if($this->params->get("cam_pieDiameter")):echo $this->params->get("cam_pieDiameter"); else:?>38<?php endif;?>px;
}
#slideshow-handler,.camera_fakehover{min-height:<?php echo $this->params->get("cam_minHeight");?>;}
<?php endif;endif;?>
<?php							
function hex2rgb($hex){
$hex=str_replace("#","",$hex);
if(strlen($hex)==3){
$r=hexdec(substr($hex,0,1).substr($hex,0,1));
$g=hexdec(substr($hex,1,1).substr($hex,1,1));
$b=hexdec(substr($hex,2,1).substr($hex,2,1));
}else{
$r=hexdec(substr($hex,0,2));
$g=hexdec(substr($hex,2,2));
$b=hexdec(substr($hex,4,2));
}
$rgb=array($r,$g,$b);
return implode(",",$rgb);
}
if($this->params->get('usetheme')==false):
$fc=fopen(YOURBASEPATH.DS.'css/template-colors.css','w');
fwrite($fc,'body,dt.tabs.open{
background-color:'.$this->params->get("bodybackground").';
color:'.$this->params->get("bodytextcolor").';
}
.custom-color-1{color:'.$this->params->get("customcolor1").';}
.custom-color-2{color:'.$this->params->get("customcolor2").';}
.custom-color-3{color:'.$this->params->get("customcolor3").';}
.custom-background-1{background-color:'.$this->params->get("customcolor1").';}
.custom-background-2{background-color:'.$this->params->get("customcolor2").';}
.custom-background-3{background-color:'.$this->params->get("customcolor3").';}
a,a:hover,.moduletable_menu ul.menu li ul li a:hover{color:'.$this->params->get("linkscolor").';}
.button,button,a.button,dt.tabs.closed:hover,dt.tabs.closed:hover h3 a,.closemenu,.highlight-button,#login-form .btn-group>.dropdown-menu,#login-form .btn-group>.dropdown-menu a,.btn, .btn-primary{
color:'.$this->params->get("buttonLabel").' !important;
background-color:'.$this->params->get("buttonbg").' !important;
}
.button:hover,button:hover,a.button:hover,.closemenu:hover,.highlight-button:hover,.btn:hover,.btn-primary:hover{ 
color:'.$this->params->get("buttonHLabel").' !important;
background-color:'.$this->params->get("buttonHbg").' !important;
}
#login-form .btn-group>.dropdown-menu a:hover{
background:'.$this->params->get("buttonHbg").' !important;
}
#login-form .caret{
border-top-color:'.$this->params->get("buttonHLabel").' !important;
}
#top-handler{
background-color: '.$this->params->get("top_handler_bg").';
border-bottom:3px solid '.$this->params->get("top_handler_border").';
}
#quick-menu li a{
color:'.$this->params->get("quick_menu_li_a").';
}
#quick-menu li a:hover, .show-both{
background-color:'.$this->params->get("quick_menu_li_a_bg_hover").';
color:'.$this->params->get("quick_menu_li_a_hover").';
}
#search-handler .search{
background-color:'.$this->params->get("searchbg").';
border:1px solid '.$this->params->get("searchborder").';
}
#search-handler .search .inputbox, #search-handler .search .button{
color:'.$this->params->get("searchcolor").';
}
#search-handler .search .button:hover{
background-color:'.$this->params->get("search_button_hover").' !important;
}
a#menupanel{
background-color:'.$this->params->get("responsive_menu_button").';
}
a#menupanel:hover{
background-color:'.$this->params->get("responsive_menu_button_hover").' !important;
}
.rm-line{ 
background-color:'.$this->params->get("responsive_menu_button_icon").';
}
div.panel2, fieldset.phrases, fieldset.word, fieldset.only, .search .form-limit, .item-page,.categories-list,.blog,.blog-featured,.category-list,.archive {
background-color:'.$this->params->get("bodybackground").';
color:'.$this->params->get("bodytextcolor").';
}
#slideshow-handler-bg{
background-color:'.$this->params->get("slideshow_handler_bg").';
border-bottom:3px solid '.$this->params->get("slideshow_handler_bg_border").';
}
.slide_cover{
background-color:rgba('.hex2rgb($this->params->get("slideshow_handler_bg")).',0.76);
}
.camera_prev>span,.camera_next>span{
background-color:'.$this->params->get("camera_arrows").' !important;
}
.camera_prev>span:hover,.camera_next>span:hover{
background-color:'.$this->params->get("camera_arrows_hover").' !important;
}
.camera_wrap .camera_pag .camera_pag_ul li{
background:'.$this->params->get("camera_pagination_bg").';
}
.camera_commands>.camera_play,.camera_commands>.camera_stop,.camera_prevThumbs div,.camera_nextThumbs div,.owl-pagination .owl-page.active,.owl-pagination .owl-page:hover{
background-color:'.$this->params->get("camera_commands").' !important;
}
.camera_wrap .camera_pag .camera_pag_ul li.cameracurrent>span,.camera_wrap .camera_pag .camera_pag_ul li:hover>span,.owl-pagination .owl-page{
background-color:'.$this->params->get("camera_pagination_bg_hover").';
}
.owl-pagination .owl-page{
border-color:'.$this->params->get("camera_pagination_bg_hover").';
}
.camera_thumbs_cont ul li>img{
border:1px solid '.$this->params->get("camera_thumbs_cont").' !important;
}
.camera_caption{
color:'.$this->params->get("camera_caption").';
}
#nav-handler{
background-color:'.$this->params->get("nav_handler").';
}
#menu .menu-nav li a,#menu .menu-nav ul a,#menu .menu-nav ul ul a,ul.menu-nav li a small,.panel1, .panel1 a{
color:'.$this->params->get("menu_nav_li_a").';
}
.menu-nav>li.active>a,.menu-nav>li.active>a:hover,.menu-nav>li>a:hover,.menu-nav>li.sfHover>a{
color:'.$this->params->get("menu_li_active_a").' !important;
}
#menu .menu-nav ul a:hover,.menu-nav ul li.sfHover>a,.menu-nav ul li a:hover,.menupanel ul.selectnav li a:hover,a#menupanel:hover,.dropdown-menu>li>a:hover{
background-color:'.$this->params->get("menu_ul_a_hover_bg").' !important;
color:'.$this->params->get("menu_ul_a_hover_color").'  !important;
}
#menu .menu-nav ul a:hover small{
color:'.$this->params->get("menu_ul_a_hover_color").';
}
#menu .menu-nav>li>a:hover .sf-sub-indicator,#menu .menu-nav>li.sfHover>a .sf-sub-indicator{
border-top-color:'.$this->params->get("menu_ul_a_arrow").' !important;
}
#menu .menu-nav ul li a:hover .sf-sub-indicator,#menu .menu-nav ul li.sfHover > a .sf-sub-indicator{
border-left-color:'.$this->params->get("menu_ul_a_arrow_hover").' !important;
}
#menu .menu-nav li ul,#menu .menu-nav li ul li ul,#nav ol,#nav ul,#nav ol ol,#nav ul ul,.panel1{
background-color:'.$this->params->get("menu_ul_dropdown_bg").' !important;
border:1px solid '.$this->params->get("menu_ul_dropdown_border").';
}
#menu .menu-nav>li>a:hover .tc-border,#menu .menu-nav>li.sfHover>a:hover .tc-border,#menu .menu-nav>li.sfHover>a .tc-border{
background: '.$this->params->get("menu_ul_dropdown_item_active_bg").';
}
#menu .menu-nav>li>a:hover .tc-border .tc-arrow,#menu .menu-nav>li.sfHover>a:hover .tc-border .tc-arrow,#menu .menu-nav>li.sfHover>a .tc-border .tc-arrow{
border-bottom: 9px solid '.$this->params->get("menu_ul_dropdown_item_active_bg").';
}
thead th,table th,tbody th,tbody td{
border-top:1px solid '.$this->params->get("default_borders").';
}
tbody th,tbody td,.search-results dt.result-title,#brcr{
border-bottom:1px solid '.$this->params->get("default_borders").';
}
.blog-featured article,.k2itemsl .spacer{
border-top:3px solid '.$this->params->get("blog_featured_active").' !important;
border:1px solid '.$this->params->get("blog_featured_border").';
}
p.readmore{
background-color:'.$this->params->get("blog_featured_border").';
}
p.readmore a,p.readmore a:hover{
color:'.$this->params->get("blog_featured_active").' !important;
}
.moduletable a,div.panel2 a,.category_description a{
color:'.$this->params->get("module_link").';
}
.moduletable_menu ul.menu li{
border-bottom:1px solid '.$this->params->get("module_menu_item_sep").';
}
.moduletable_menu ul.menu li a,.latestnews_menu li a{
color:'.$this->params->get("module_menu_item_color").';
}
.moduletable_menu ul.menu li a:hover,ul.latestnews_menu li a:hover{
background-color:'.$this->params->get("module_menu_item_active_bg").';
color:'.$this->params->get("module_menu_item_active_color").';
}
.moduletable_style1{
background-color:'.$this->params->get("module_style1_bg").';
color:'.$this->params->get("module_style1_color").';
border-top:3px solid '.$this->params->get("module_style1_border").';
}
.moduletable_style1 a{
color:'.$this->params->get("module_style1_link").' !important;
}
.moduletable_style2{
background-color:'.$this->params->get("module_style2_bg").';
color:'.$this->params->get("module_style2_color").';
}
.moduletable_style2 a{
color:'.$this->params->get("module_style2_link").' !important;
}
.moduletable_style3{
background-color:'.$this->params->get("module_style3_bg").';
color:'.$this->params->get("module_style3_color").' !important;
}
.moduletable_style3 a{
color:'.$this->params->get("module_style3_link").';
}
.moduletable_style3:hover{
background-color:'.$this->params->get("module_style3_bg_hover").';
}
.moduletable_style4:before{
background-color:'.$this->params->get("module_style4_bg_hover").';
}
.moduletable_style4:hover .custom_style4>p>img,.moduletable_style4:hover .custom_style4>div>img,.moduletable_style4:hover .custom_style4>img{
background-color:'.$this->params->get("module_style4_textcolor_hover").';
}
.moduletable_style4:hover,.moduletable_style4:hover *{
color:'.$this->params->get("module_style4_textcolor_hover").' !important;
}
#bot-modules{
background-color:'.$this->params->get("bottom_modules_area_bg").';
color:'.$this->params->get("bottom_modules_area_text_color").';
}
#bot-modules a{ 
color: '.$this->params->get("bottom_modules_area_link_color").';
}
#bot-modules-2, #footer{
background-color:'.$this->params->get("bottom_modules2_area_bg").';
color:'.$this->params->get("bottom_modules2_area_text_color").';
}
#bot-modules-2 a{ 
color:'.$this->params->get("bottom_modules2_area_link").';
}
#footer{
color:'.$this->params->get("footer_text_color").';
}
#footer a{
color:'.$this->params->get("footer_link_color").';
}
#footer a:hover{
color:'.$this->params->get("footer_link_hover_color").';
}');
fclose($fc);?>
<?php endif;?>	
</style>
<?php include('conf-modules.php');?>
<script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="administrator/components/com_enewsletter/css/jquery-ui.min.css">
<script>
jQuery(document).ready(function(){
<?php if($this->language=='vi-vn'){?>    
jQuery('.tabouter').prepend('<div class="newfeedmodule" style="background:#fff!important;color:#0d4fa4!important;padding:7px 5px;margin-bottom:2px;width:95%;font-weight:bold;text-transform:uppercase;">Tin Tức PCV</div>');
jQuery('.pagination-start .pagenav').text('Trang Đầu');
jQuery('.pagination-end .pagenav').text('Trang Cuối');
jQuery('#dropifiTextContent').text('Liên Hệ');
jQuery('#widgetField_name').attr('placeholder','Họ và Tên');
jQuery('#widgetField_email').attr('placeholder','Email của bạn');
jQuery('#widgetField_message').attr('placeholder','Chúng tôi có thể giúp gì cho bạn');
jQuery('#sendMail').val('Gửi Thư');
jQuery('#dropifi_widget_v1_imageText').attr('src','<?php echo juri::base();?>images/cccontact.png');
jQuery('.tintucvn .eb-brand-name').text('Thông Tin Sức Khoẻ');
jQuery('.tintucvn .eb-brand-bio').text('Pacific Cross Việt Nam là một Nhà quản lý bảo hiểm y tế chuyên về bảo hiểm sức khỏe và bảo hiểm du lịch tại châu Á. Chúng tôi thuộc tập đoàn đa công ty Pacific Cross với hơn 60 năm kinh nghiệm về quản lý và thiết kế bảo hiểm y tế và bảo hiểm du lịch.');
jQuery('.baiphobien .mod-cell a').text('Xem thêm');	
jQuery('.subscribevn [for="eb-subscribe-fullname"]').text('Tên của bạn');
jQuery('.subscribevn [for="eb-subscribe-email"]').text('Email của bạn');
jQuery('.subscribevn .btn-primary').text('Đăng Ký Ngay');
jQuery("#module_172").append('<a class="companyblog" href="http://pacificcross.com.vn/vi/tintuc/categories/s-c-kho-du-l-ch.html">Tất cả tin tức</a>');
jQuery("#module_161").append('<a class="companyblog" href="http://pacificcross.com.vn/vi/tintuc/categories/cong-ty.html">Tất cả tin tức</a>');
var html=jQuery(".mod-easyblogsearch").html();
jQuery('<div class="blogsearch">'+html+'</div>').insertAfter('.eb-brand');
jQuery('.eb-category-profile').remove();
<?php }else{ ?>
var html=jQuery(".mod-easyblogsearch").html();
jQuery('<div class="blogsearch">'+html+'</div>').insertAfter('.eb-brand');
jQuery('.eb-category-profile').remove();	 
jQuery("#module_125").append('<a class="companyblog" href="http://pacificcross.com.vn/en/eblog/categories/health.html">View all blogs</a>');
jQuery("#module_161").append('<a class="companyblog" href="http://pacificcross.com.vn/en/eblog/categories/company.html">View all blogs</a>');
jQuery('.tabouter').prepend('<div class="newfeedmodule" style="background:#fff!important;color:#0d4fa4!important;padding:7px 5px;margin-bottom:2px;width:95%;font-weight:bold;text-transform:uppercase;">PCV Newsfeed</div>');
<?php } ?>
setTimeout(function(){jQuery('.mapside').css('width',"44%");},1000);
jQuery('.blocksearch').hide();
jQuery('.item-274,.item-373').click(function(){
if(jQuery(this).hasClass('open')){
jQuery(this).removeClass('open');
jQuery('.blocksearch').hide();
}else{
jQuery('.blocksearch').fadeIn('slow');
jQuery(this).addClass('open');
}
});
if(jQuery('body').hasClass('medical_providers_xxx')){
<?php if($this->language=='vi-vn'){$drapbar="Kéo thay đổi hiển thị";}else{$drapbar="Drag To Expand View";}?>
jQuery('<div class="dsada" style="width:55%;text-align:right;cursor:pointer;margin-bottom:0px;margin-right:-14px;"><div class="detalsss" style="margin-right:-75px;opacity:0.5;margin-bottom:-8px;"><?php echo $drapbar;?></div><a href="#" style=""><img src="<?php echo juri::base();?>images/slider-arrows4.png"/></a></div>').insertAfter(".barsearch");
jQuery(".dsada").draggable({ axis:"x"},{start: function(){jQuery('.detalsss').hide();},drag:function(){},stop:function(){
var wd=jQuery('.dsada').width();
var wdd=jQuery(window).width();
var lef=jQuery(".dsada").css('left');
lef=lef.replace("px","");
var wd=parseInt(wd)+parseInt(lef);
var aa=wdd-wd-20;
jQuery(".infoside").css('width',wd+'px');
jQuery(".dsada").css('width',wd+'px');
jQuery(".dsada").css('left','0px');
jQuery(".mapside").css('width',aa+'px');
jQuery('.detalsss').show();
});}
var english=/^[A-Za-z0-9]*$/;
jQuery('.mod-items-compact .tag-cloud').each(function(){
if(!english.test(jQuery(this).text())){
<?php if($this->language=='vi-vn'){ ?>
jQuery(this).parents().eq(1).show();
<?php }else{ ?>
jQuery(this).parents().eq(1).hide();
<?php } ?>
}else{
<?php if($this->language=='vi-vn'){ ?>
jQuery(this).parents().eq(1).hide();
<?php }else{ ?>
jQuery(this).parents().eq(1).show();
<?php } ?>
}});});
</script>
<style>
.dsada a:hover{text-decoration:none!important;opacity:1;}
.dsada a{font-weight:bold;margin-right:-53px;padding:9px;color:#fff!important;padding-top:10px;padding-bottom:10px;opacity:0.3;}
<?php if($this->language=='vi-vn'){echo '#nav-handler{padding:9px 0px;} #menu .menu-nav>li{padding:3px 15px;} .medical_providers_xxx #nav-handler{padding-bottom:17px;}';} ?>
</style>
</head>
<?php
$menu=JSite::getMenu();
$curMenu=$menu->getActive();
?>
<body class="<?php if(JRequest::getVar('flexi_callview')!='item' && $curMenu) echo $curMenu->params->get('pageclass_sfx');?>">
<header id="top-handler">
<?php if(JFactory::getApplication()->getMessageQueue()):?>
<div class="navbar-fixed-top container spaced">
<div class="info info-block fade in">
<button type="button"class="close"data-dismiss="alert">×</button>
<jdoc:include type="message"/>
</div>
</div>
<?php endif;?>
<div id="top">
<?php if($this->countModules('header-1 or header-2 or header-3 or header-4 or header-5 or header-6')):?>
<section id="section-logo" class="container">
<div class="row-fluid">
<?php if($this->countModules('header-1')):?><div class="<?php echo $lg1class;?>"style="<?php if($logo1==5){echo "width:".$lg1class5w;}?>"><jdoc:include type="modules"name="header-1"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('header-2')):?><div class="<?php echo $lg1class;?>"style="<?php if($logo1==5){echo "width:".$lg1class5w;}?>"><jdoc:include type="modules"name="header-2"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('header-3')):?><div class="<?php echo $lg1class;?>"style="<?php if($logo1==5){echo "width:".$lg1class5w;}?>"><jdoc:include type="modules"name="header-3"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('header-4')):?><div class="<?php echo $lg1class;?>"style="<?php if($logo1==5){echo "width:".$lg1class5w;}?>"><jdoc:include type="modules"name="header-4"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('header-5')):?><div class="<?php echo $lg1class;?>"style="<?php if($logo1==5){echo "width:".$lg1class5w;}?>"><jdoc:include type="modules"name="header-5"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('header-6')):?><div class="<?php echo $lg1class;?>"style="<?php if($logo1==5){echo "width:".$lg1class5w;}?>"><jdoc:include type="modules"name="header-6"style="LTdefault"/></div><?php endif;?>
</div>
</section>
<?php endif;?>
<div class="container">
<div class="row-fluid" style="position:relative;">
<div style="position:absolute;top:-25px;z-index:1000;right:0px;"><?php if($this->countModules('language')):?><jdoc:include type="modules"name="language"/><?php endif;?></div>
<div id="site-name-handler" class="span5">
<div id="sn-position">
<?php if($this->params->get('logoLinked')):?>
<?php if($this->params->get('H1TitleImgText')==true):?>
<div id="h1"><a href="<?php echo $this->baseurl;?>"><img alt="<?php echo strip_tags($this->params->get("H1Title"));?>" src="<?php echo $this->params->get("H1Titleimage");?>"/></a></div>
<?php else:?>
<div id="h1"><a href="<?php echo $this->baseurl;?>"><?php echo $this->params->get("H1Title");?></a></div>
<?php endif;?>
<?php else:?>
<?php if($this->params->get('H1TitleImgText')==true):?>
<div id="h1"><img alt="<?php echo strip_tags($this->params->get("H1Title"));?>"src="<?php echo $this->params->get("H1Titleimage");?>"/></div>
<?php else:?>
<div id="h1"><?php echo $this->params->get("H1Title");?></div>
<?php endif;?>
<?php endif;?>
<?php if($this->params->get('H2TitleImgText')==true):?>
<div id="h2"><img alt="<?php echo strip_tags($this->params->get("H2Title"));?>"src="<?php echo $this->params->get("H2Titleimage");?>"/></div>
<?php else:?>
<div id="h2"><?php echo $this->params->get("H2Title");?></div>
<?php endif;?>
</div>
</div>
<div id="top-nav-handler" class="span6">
<div id="top-quick-nav">
<?php if($this->countModules('loginform')):?>
<ul id="log-panel">
<?php jimport('joomla.application.module.helper');$module_login=JModuleHelper::getModules('loginform');?>
<li><a data-toggle="modal"href="#LoginForm"class="open-register-form"><?php echo $module_login[0]->title;?></a></li>
<?php if(!JFactory::getUser()->id):$usersConfig=JComponentHelper::getParams('com_users');if($usersConfig->get('allowUserRegistration')):?>
<li><a id="v_register"href="<?php echo JRoute::_('index.php?option=com_users&view=registration');?>"><?php echo JText::_('JREGISTER');?></a></li>
<?php endif;endif;?>
</ul>
<?php endif;?>
<?php if($this->countModules('position-4')):?>
<jdoc:include type="modules"name="position-4"/>
<?php endif;?>
</div>
<div class="clear"></div>
<?php if($this->params->get('twitterON')||$this->params->get('linkedinON')||$this->params->get('RSSON')||$this->params->get('facebookON')||$this->params->get('ytON')||$this->params->get('pinON')||$this->params->get('vimeoON')||$this->params->get('stumbleuponON')||$this->params->get('diggON')):?>
<nav id="social">
<ul id="social-links">
<?php if($this->params->get('twitterON')==true):?><li><a href="<?php echo $this->params->get('twitter');?>"title="Twitter"id="twitter"target="_blank"><span>Follow Us</span></a></li><?php endif;?>
<?php if($this->params->get('gplusON')==true):?><li><a href="<?php echo $this->params->get('gplus');?>"title="Google Plus"id="gplus"target="_blank"><span>Google Plus</span></a></li><?php endif;?>
<?php if($this->params->get('facebookON')==true):?><li><a href="<?php echo $this->params->get('facebook');?>"title="Facebook"id="facebook"target="_blank"><span>Facebook</span></a></li><?php endif;?>
<?php if($this->params->get('RSSON')==true):?><li><a href="<?php echo $this->params->get('RSS');?>"title="RSS"id="rss"target="_blank"><span>RSS</span></a></li><?php endif;?>
<?php if($this->params->get('linkedinON')==true):?><li><a href="<?php echo $this->params->get('linkedin');?>"title="Linkedin"id="linkedin"target="_blank"><span>Linkedin</span></a></li><?php endif;?>
<?php if($this->params->get('pinON')==true):?><li><a href="<?php echo $this->params->get('pin');?>"title="pinterest"id="pin"target="_blank"><span>pinterest</span></a></li><?php endif;?>
<?php if($this->params->get('vimeoON')==true):?><li><a href="<?php echo $this->params->get('vimeo');?>"title="vimeo"id="vimeo"target="_blank"><span>vimeo</span></a></li><?php endif;?>
<?php if($this->params->get('stumbleuponON')==true):?><li><a href="<?php echo $this->params->get('stumbleupon');?>"title="stumbleupon"id="stumbleupon"target="_blank"><span>stumbleupon</span></a></li><?php endif;?>
<?php if($this->params->get('diggON')==true):?><li><a href="<?php echo $this->params->get('digg');?>"title="digg"id="digg"target="_blank"><span>digg</span></a></li><?php endif;?>
<?php if($this->params->get('ytON')==true):?><li><a href="<?php echo $this->params->get('yt');?>"title="YouTube"id="yt"target="_blank"><span>YouTube</span></a></li><?php endif;?>
</ul>
</nav>
<?php endif;?>
</div>
</div>
</div>
</div>
<div class="clear"></div>
</header>
<div id="nav-handler">
<div class="container">
<div class="row-fluid">
<?php if($this->countModules('position-1')):?>
<div id="menu-handler"class="span12">
<nav id="menu">
<a id="menupanel"href="javascript:void(0);"><span class="s1 rm-line"></span><span class="s2 rm-line"></span><span class="s3 rm-line"></span></a>
<?php if($this->countModules('position-1')):?><jdoc:include type="modules"name="position-1"/><?php endif;?>
</nav>
</div>
<?php endif;?>
<?php if($this->countModules('position-0')):?>
<div id="search-handler"class="span3"><jdoc:include type="modules"name="position-0"/></div>
<?php endif;?>
</div>
</div>
</div>
<?php if($this->countModules('loginform')):?>
<div id="LoginForm"class="modal hide fade"tabindex="-1"role="dialog"aria-labelledby="myModalLabel"aria-hidden="true"style="display: none;">
<div class="modal-header"><span id="myModalLabel"><?php echo $module_login[0]->title;?></span></div>
<div class="modal-body"><jdoc:include type="modules"name="loginform"/></div>
<div class="modal-footer"><a class="button"data-dismiss="modal">Close</a></div>
</div>
<?php endif;?>
<?php if($this->countModules('builtin-slideshow or slideshow')):?>
<div id="slideshow-handler-bg">
<div id="slideshow-handler">
<?php if($this->countModules('builtin-slideshow')):?>
<?php
$count_slides=JDocumentHTML::countModules('builtin-slideshow');
$module=JModuleHelper::getModules('builtin-slideshow');
$moduleParams=new JRegistry();
echo "<div class=\"camera_wrap\" id=\"ph-camera-slideshow\">";
for($sld_a=0;$sld_a<$count_slides;$sld_a++){
$moduleParams->loadString($module[$sld_a]->params);
$bgimage[$sld_a]=$moduleParams->get('backgroundimage','defaultValue');
$caption_effect[$sld_a]=$moduleParams->get('moduleclass_sfx','defaultValue');
?>
<div data-thumb="<?php if($bgimage[$sld_a]=="defaultValue"):echo $this->baseurl."/templates/".$this->template."/images/slideshow/no-image.png";else:echo $this->baseurl."/".$bgimage[$sld_a];endif;?>" data-src="<?php if($bgimage[$sld_a]=="defaultValue"):echo $this->baseurl."/templates/".$this->template."/images/slideshow/no-image.png";else:echo $this->baseurl."/".$bgimage[$sld_a];endif;?>">
<div class="slide_cover camera_caption fadeIn"style="<?php if(empty($module[$sld_a]->content)):?>display:none!important;visibility:hidden!important;opacity:0!important;<?php endif;?>"></div>
<div class="camera_caption <?php if(($caption_effect[$sld_a]=="defaultValue")):?>fadeIn<?php else:echo $caption_effect[$sld_a];endif;?>"style="<?php if(empty($module[$sld_a]->content)):?>display:none!important;visibility:hidden!important;opacity:0!important;<?php endif;?>">
<div><?php echo $module[$sld_a]->content;?></div>
</div>
</div>
<?php } echo "</div>";?> 
<?php elseif($this->countModules('slideshow')):?>
<div class="sl-3rd-parties">
<jdoc:include type="modules"name="slideshow"/>
</div>
<?php endif;?>
</div>
</div>
<?php endif;?>
<div id="main-handler">
<div id="content-handler" class="container">
<?php if($this->countModules('position-2')):?>
<div id="nav-line">
<div class="row-fluid">
<div class="span12"><div id="brcr"><jdoc:include type="modules"name="position-2"/></div></div>
</div>
</div>
<?php endif;?>
<?php if($this->countModules('top-1 or top-2 or top-3 or top-4 or top-5 or top-6')):?>
<section id="tab-modules">
<div id="tab-modules-handler"class="row-fluid">
<?php if($this->countModules('top-1')):?><div class="<?php echo $tm1class;?>"style="<?php if($topmodules1==5){echo "width:".$tm1class5w;}?>"><jdoc:include type="modules"name="top-1"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-2')):?><div class="<?php echo $tm1class;?>"style="<?php if($topmodules1==5){echo "width:".$tm1class5w;}?>"><jdoc:include type="modules"name="top-2"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-3')):?><div class="<?php echo $tm1class;?>"style="<?php if($topmodules1==5){echo "width:".$tm1class5w;}?>"><jdoc:include type="modules"name="top-3"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-4')):?><div class="<?php echo $tm1class;?>"style="<?php if($topmodules1==5){echo "width:".$tm1class5w;}?>"><jdoc:include type="modules"name="top-4"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-5')):?><div class="<?php echo $tm1class;?>"style="<?php if($topmodules1==5){echo "width:".$tm1class5w;}?>"><jdoc:include type="modules"name="top-5"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-6')):?><div class="<?php echo $tm1class;?>"style="<?php if($topmodules1==5){echo "width:".$tm1class5w;}?>"><jdoc:include type="modules"name="top-6"style="LTdefault"/></div><?php endif;?>
</div>
</section>
<?php endif;?>
<?php if($this->countModules('top-7 or top-8 or top-9 or top-10 or top-11 or top-12')):?>
<section id="top-modules">
<div class="row-fluid">
<?php if($this->countModules('top-7')):?><div class="<?php echo $tm2class;?>"style="<?php if($topmodules2==5){echo "width:".$tm2class5w;}?>"><jdoc:include type="modules"name="top-7"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-8')):?><div class="<?php echo $tm2class;?>"style="<?php if($topmodules2==5){echo "width:".$tm2class5w;}?>"><jdoc:include type="modules"name="top-8"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-9')):?><div class="<?php echo $tm2class;?>"style="<?php if($topmodules2==5){echo "width:".$tm2class5w;}?>"><jdoc:include type="modules"name="top-9"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-10')):?><div class="<?php echo $tm2class;?>"style="<?php if($topmodules2==5){echo "width:".$tm2class5w;}?>"><jdoc:include type="modules"name="top-10"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-11')):?><div class="<?php echo $tm2class;?>"style="<?php if($topmodules2==5){echo "width:".$tm2class5w;}?>"><jdoc:include type="modules"name="top-11"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-12')):?><div class="<?php echo $tm2class;?>"style="<?php if($topmodules2==5){echo "width:".$tm2class5w;}?>"><jdoc:include type="modules"name="top-12"style="LTdefault"/></div><?php endif;?>
</div>
</section>
<?php endif;?>
<section id="tmp-container">
<?php if($this->countModules('position-3')):?>
<div class="row-fluid">
<div class="span12"><div id="newsflash-position"><jdoc:include type="modules"name="position-3"style="LTdefault"/></div></div>
</div>
<?php endif;?>
<div id="main-content-handler">
<div class="row-fluid">
<?php if($this->countModules('top-left-1 or top-left-2 or position-7 or left or bottom-left-1 or bottom-left-2')):?>
<div class="span3">
<aside>
<jdoc:include type="modules"name="top-left-1"style="LTdefault"/>
<jdoc:include type="modules"name="top-left-2"style="LTdefault"/>
<jdoc:include type="modules"name="left"style="LTdefault"/>
<jdoc:include type="modules"name="position-7"style="LTdefault"/>
<jdoc:include type="modules"name="bottom-left-1"style="LTdefault"/>
<jdoc:include type="modules"name="bottom-left-2"style="LTdefault"/>
</aside>
</div>
<?php endif;?>
<div class="<?php echo $mcols;?>">
<?php if($this->countModules('top-long')):?>
<jdoc:include type="modules"name="top-long"style="LTdefault"/>
<div class="clear-sep"></div>
<?php endif;?>
<?php if($this->countModules('top-a or top-b or top-c or top-d')):?>
<div id="top-content-modules">
<div class="row-fluid">
<?php if($this->countModules('top-a')):?><div class="<?php echo $tpaclass;?>"><jdoc:include type="modules"name="top-a"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-b')):?><div class="<?php echo $tpaclass;?>"><jdoc:include type="modules"name="top-b"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-c')):?><div class="<?php echo $tpaclass;?>"><jdoc:include type="modules"name="top-c"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('top-d')):?><div class="<?php echo $tpaclass;?>"><jdoc:include type="modules"name="top-d"style="LTdefault"/></div><?php endif;?>
</div>
</div>
<?php endif;?>
<div class="tmp-content-area">
<jdoc:include type="component"/>
</div>
<?php if($this->countModules('bottom-a or bottom-b or bottom-c or bottom-d')):?>
<div id="bottom-content-modules">
<div class="row-fluid">
<?php if($this->countModules('bottom-a')):?><div class="<?php echo $bmaclass;?>"><jdoc:include type="modules"name="bottom-a"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-b')):?><div class="<?php echo $bmaclass;?>"><jdoc:include type="modules"name="bottom-b"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-c')):?><div class="<?php echo $bmaclass;?>"><jdoc:include type="modules"name="bottom-c"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-d')):?><div class="<?php echo $bmaclass;?>"><jdoc:include type="modules"name="bottom-d"style="LTdefault"/></div><?php endif;?>
</div>	
</div>
<?php endif;?>
</div>
<?php if($this->countModules('top-right-1 or top-right-2 or position-6 or right or bottom-right-1 or bottom-right-2')):?>
<div class="span3">
<aside>
<jdoc:include type="modules"name="top-right-1"style="LTdefault"/>
<jdoc:include type="modules"name="top-right-2"style="LTdefault"/>
<jdoc:include type="modules"name="right"style="LTdefault"/>
<jdoc:include type="modules"name="position-6"style="LTdefault"/>
<jdoc:include type="modules"name="bottom-right-1"style="LTdefault"/>
<jdoc:include type="modules"name="bottom-right-2"style="LTdefault"/>
</aside>
</div>
<?php endif;?>
</div>
<?php if($this->countModules('bottom-e or bottom-f or bottom-g or bottom-h or bottom-i or bottom-j')):?>
<div class="row-fluid">
<?php if($this->countModules('bottom-e')):?><div class="<?php echo $cpclass;?>"style="<?php if($bottoms==5){echo "width:".$cpclass5w;}?>"><jdoc:include type="modules"name="bottom-e"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-f')):?><div class="<?php echo $cpclass;?>"style="<?php if($bottoms==5){echo "width:".$cpclass5w;}?>"><jdoc:include type="modules"name="bottom-f"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-g')):?><div class="<?php echo $cpclass;?>"style="<?php if($bottoms==5){echo "width:".$cpclass5w;}?>"><jdoc:include type="modules"name="bottom-g"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-h')):?><div class="<?php echo $cpclass;?>"style="<?php if($bottoms==5){echo "width:".$cpclass5w;}?>"><jdoc:include type="modules"name="bottom-h"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-i')):?><div class="<?php echo $cpclass;?>"style="<?php if($bottoms==5){echo "width:".$cpclass5w;}?>"><jdoc:include type="modules"name="bottom-i"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-j')):?><div class="<?php echo $cpclass;?>"style="<?php if($bottoms==5){echo "width:".$cpclass5w;}?>"><jdoc:include type="modules"name="bottom-j"style="LTdefault"/></div><?php endif;?>
</div>
<?php endif;?>
</div>
</section>
</div>
</div>
<?php if($this->countModules('bottom-long')):
$testislitems=$this->params->get('testisl_items');
$testislitemsDesktop=$this->params->get('testisl_itemsDesktop');
$testislitemsDesktopSmall=$this->params->get('testisl_itemsDesktopSmall');
$testislitemsTablet=$this->params->get('testisl_itemsTablet');
$testislitemsTabletSmall=$this->params->get('testisl_itemsTabletSmall');
$testislitemsMobile=$this->params->get('testisl_itemsMobile');
if($this->params->get('testisl_pagination')):$testislpag="true";else:$testislpag="false";endif;
if($this->params->get('testisl_stopOnHover')):$testislstopOnHover="true";else:$testislstopOnHover="false";endif;
if($this->params->get('testisl_navigation')):$testislnavigation="true";else:$testislnavigation="false";endif;
if($this->params->get('testisl_scrollPerPage')):$testislscrollPerPage="true";else:$testislscrollPerPage="false";endif;
if($this->params->get('testisl_paginationNumbers')):$testislpaginationNumbers="true";else:$testislpaginationNumbers="false";endif;
if($this->params->get('testisl_responsive')):$testislresponsive="true";else:$testislresponsive="false";endif;
if($this->params->get('testisl_dragBeforeAnimFinish')):$testisldragBeforeAnimFinish="true";else:$testisldragBeforeAnimFinish="false";endif;
if($this->params->get('testisl_mouseDrag')):$testislmouseDrag="true";else:$testislmouseDrag="false";endif;
if($this->params->get('testisl_touchDrag')):$testisltouchDrag="true";else:$testisltouchDrag="false";endif;
$temp_path=$this->baseurl."/templates/".$this->template;
$doc->addScript($temp_path.'/js/owl.carousel.min.js');
$doc->addCustomTag('
<script type="text/javascript">
jQuery(document).ready(function(){
var owl=jQuery("#owl-id-testimonial");
owl.owlCarousel({
pagination:'.$testislpag.',
items:'.$testislitems.',
itemsDesktop:[1600,'.$testislitemsDesktop.'],
itemsDesktopSmall:[1260,'.$testislitemsDesktopSmall.'],
itemsTablet:[1000,'.$testislitemsTablet.'],
itemsTabletSmall:[768,'.$testislitemsTabletSmall.'],
itemsMobile:[480,'.$testislitemsMobile.'],
slideSpeed:'.$this->params->get('testisl_slideSpeed').',
paginationSpeed:'.$this->params->get('testisl_paginationSpeed').',
rewindSpeed:'.$this->params->get('testisl_rewindSpeed').',
autoPlay:'.$this->params->get('testisl_autoPlay').',
stopOnHover:'.$testislstopOnHover.',
navigation: false,
scrollPerPage:'.$testislscrollPerPage.',
paginationNumbers:'.$testislpaginationNumbers.',
responsive:'.$testislresponsive.',
responsiveRefreshRate:'.$this->params->get('testisl_responsiveRefreshRate').',
dragBeforeAnimFinish:'.$testisldragBeforeAnimFinish.',
mouseDrag:'.$testislmouseDrag.',
touchDrag:'.$testisltouchDrag.'
});
});
</script>
');
?>
<section id="bottom-long">
<div class="container centered">
<h3 class="testi-title"><img src="<?php echo $this->baseurl."/templates/".$this->template."/images/quotes.png"?>"><br><?php echo $this->params->get('testi_title');?></h3>
<div id="customers-box">
<div class="row-fluid">
<div class="span12">
<div class="customers-box-handler">
<div class="owl-carousel owl-theme" id="owl-id-testimonial">
<jdoc:include type="modules"name="bottom-long"/>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<?php endif;?>
<section id="bottom-bg">
<?php if($this->countModules('bottom-1 or bottom-2 or bottom-3 or bottom-4 or bottom-5 or bottom-6')):?>
<div id="bot-modules">
<div class="container">
<div class="row-fluid">
<?php if($this->countModules('bottom-1')):?><div class="<?php echo $bmclass;?>"style="<?php if($botmodules==5){echo "width:".$bmclass5w;}?>"><jdoc:include type="modules"name="bottom-1"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-2')):?><div class="<?php echo $bmclass;?>"style="<?php if($botmodules==5){echo "width:".$bmclass5w;}?>"><jdoc:include type="modules"name="bottom-2"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-3')):?><div class="<?php echo $bmclass;?>"style="<?php if($botmodules==5){echo "width:".$bmclass5w;}?>"><jdoc:include type="modules"name="bottom-3"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-4')):?><div class="<?php echo $bmclass;?>"style="<?php if($botmodules==5){echo "width:".$bmclass5w;}?>"><jdoc:include type="modules"name="bottom-4"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-5')):?><div class="<?php echo $bmclass;?>"style="<?php if($botmodules==5){echo "width:".$bmclass5w;}?>"><jdoc:include type="modules"name="bottom-5"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-6')):?><div class="<?php echo $bmclass;?>"style="<?php if($botmodules==5){echo "width:".$bmclass5w;}?>"><jdoc:include type="modules"name="bottom-6"style="LTdefault"/></div><?php endif;?>
</div>
</div>
</div>
<div class="clear"> </div>
<?php endif;?>
<?php if($this->countModules('bottom-7 or bottom-8 or bottom-9 or bottom-10 or bottom-11 or bottom-12')):?>
<div id="bot-modules-2">
<div class="container">
<div class="row-fluid">
<?php if($this->countModules('bottom-7')):?><div class="<?php echo $bm2class;?>"style="<?php if($botmodules2==5){echo "width:".$bm2class5w;}?>"><jdoc:include type="modules"name="bottom-7"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-8')):?><div class="<?php echo $bm2class;?>"style="<?php if($botmodules2==5){echo "width:".$bm2class5w;}?>"><jdoc:include type="modules"name="bottom-8"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-9')):?><div class="<?php echo $bm2class;?>"style="<?php if($botmodules2==5){echo "width:".$bm2class5w;}?>"><jdoc:include type="modules"name="bottom-9"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-10')):?><div class="<?php echo $bm2class;?>"style="<?php if($botmodules2==5){echo "width:".$bm2class5w;}?>"><jdoc:include type="modules"name="bottom-10"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-11')):?><div class="<?php echo $bm2class;?>"style="<?php if($botmodules2==5){echo "width:".$bm2class5w;}?>"><jdoc:include type="modules"name="bottom-11"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('bottom-12')):?><div class="<?php echo $bm2class;?>"style="<?php if($botmodules2==5){echo "width:".$bm2class5w;}?>"><jdoc:include type="modules"name="bottom-12"style="LTdefault"/></div><?php endif;?>
</div>
</div>
</div>
<div class="clear"></div>
<?php endif;?>
</section>
<?php if($this->countModules('footer or footer-left or footer-right or footer-1 or footer-2 or footer-3 or footer-4 or footer-5 or footer-6')):?>
<footer id="footer" >
<div class="container">
<?php if($this->countModules('footer-1 or footer-2 or footer-3 or footer-4 or footer-5 or footer-6')):?>
<div class="row-fluid">
<?php if($this->countModules('footer-1')):?><div class="<?php echo $fclass;?>"style="<?php if($footers==5){echo "width:".$fclass5w;}?>"><jdoc:include type="modules"name="footer-1"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('footer-2')):?><div class="<?php echo $fclass;?>"style="<?php if($footers==5){echo "width:".$fclass5w;}?>"><jdoc:include type="modules"name="footer-2"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('footer-3')):?><div class="<?php echo $fclass;?>"style="<?php if($footers==5){echo "width:".$fclass5w;}?>"><jdoc:include type="modules"name="footer-3"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('footer-4')):?><div class="<?php echo $fclass;?>"style="<?php if($footers==5){echo "width:".$fclass5w;}?>"><jdoc:include type="modules"name="footer-4"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('footer-5')):?><div class="<?php echo $fclass;?>"style="<?php if($footers==5){echo "width:".$fclass5w;}?>"><jdoc:include type="modules"name="footer-5"style="LTdefault"/></div><?php endif;?>
<?php if($this->countModules('footer-6')):?><div class="<?php echo $fclass;?>"style="<?php if($footers==5){echo "width:".$fclass5w;}?>"><jdoc:include type="modules"name="footer-6"style="LTdefault"/></div><?php endif;?>
</div>
<?php endif;?>
</div>
</footer>
<div class="container">	
<div id="footer-line" class="row-fluid">
<?php if($this->countModules('footer')):?><div class="span12"><jdoc:include type="modules"name="footer"/></div><?php endif;?>
<?php if($this->countModules('footer-left or footer-right')):?>
<div id="foo-left-right">
<?php if($this->countModules('footer-left')):?><div class="<?php if($this->countModules('footer-left and footer-right')):?>span6<?php else:?>span12<?php endif;?>"><jdoc:include type="modules"name="footer-left"/></div><?php endif;?>
<?php if($this->countModules('footer-right')):?><div class="<?php if($this->countModules('footer-left and footer-right')):?>span6<?php else:?>span12<?php endif;?>"><jdoc:include type="modules"name="footer-right"/></div><?php endif;?>
<div class="clear"></div>
</div>
<?php endif;?>
</div>
</div>
<?php endif;?>
<?php if($this->params->get("bodybackgroundimage")):?>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/js/jquery.backstretch.min.js"></script>
<script type="text/javascript">
jQuery.backstretch("<?php echo $this->baseurl ?>/<?php echo $this->params->get("bodybackgroundimage");?>");
</script>
<?php endif;?>
<jdoc:include type="modules"name="debug"/>
<?php echo $this->params->get("footercode");?>
<style>	
<?php if($this->language=='vi-vn'&&0==1){?>
body{font-family:Times New Roman,'Open Sans', Arial,Helvetica,sans-serif!important;}
h1,h2,h3,h4,h5,h6,.userfields_info,.result-title,span.title,.productdetails-view h1{font-family:Times New Roman,'Open Sans',Arial,Helvetica,sans-serif!important;}
#menu .menu-nav>li>a span{font-family:Times New Roman,'Open Sans',Arial,Helvetica,sans-serif!important;}	
#menu .menu-navul>li a{font-family:Times New Roman,'Open Sans',Arial,Helvetica,sans-serif!important;}
input.inputbox,textarea,.textarea,select.inputbox,input.validate-email,select.inputbox,.quantity-input,table.user-details input,#com-form-login-username input,select,#company_field,#title,#first_name_field,#middle_name_field,#last_name_field,#address_1_field,#address_2_field,#zip_field,#city_field,#virtuemart_country_id,#phone_1_field,#phone_2_field,#fax_field,#agreed_field,.contentpane #name,.contentpane #email,.contentpane #counter,.contact-input-box input,.form-validate input,.login-fields #username,.login-fields #password,.coupon,input.vm-default{font-family:Times New Roman,'Open Sans',Arial,Helvetica,sans-serif!important;}
input,button,select,textarea{font-family:Times New Roman,'Open Sans',Arial,Helvetica,sans-serif!important;}
<?php }?>
</style>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create','UA-36524404-2','auto');
ga('send','pageview');
</script>
<script>
(function(h,o,t,j,a,r){
h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
h._hjSettings={hjid:424233,hjsv:5};
a=o.getElementsByTagName('head')[0];
r=o.createElement('script');r.async=1;
r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
a.appendChild(r);
})(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</body>
</html>