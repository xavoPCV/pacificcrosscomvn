<?php
defined('_JEXEC') or die('Restricted access');


$doc = JFactory::getDocument();

$main_menu = array(
    'templates' => array(
        'name' => 'Templates',
        'link' => ADVISORLEAD_URL . '/templates'
    ),
    'pages' => array(
        'name' => 'Domains',
        'link' => ADVISORLEAD_URL . '/pages'
    ),
    'cta' => array(
        'name' => 'Popups',
        'link' => ADVISORLEAD_URL . '/ctas'
    ),
    'advisorlead' => array(
        'name' => 'Dashboard',
        'link' => ADVISORLEAD_URL . '/dashboard'
    ),
    'analytics' => array(
        'name' => 'Analytics',
        'link' => ADVISORLEAD_URL . '/analytics'
    )
	,
    'signout' => array(
        'name' => 'Logout',
        'link' => JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1')
    )
);

ob_start();

switch ($current_view) {
    case "templates":
		 break;

        ?>
        <ul class="nav nav-pills" id="category-select">
            <li class="active category_new all">
                <a data-category="" href="#">
                    <span class="text_block" >All Templates</span>
                </a>
            </li>
            <?php
            if (!empty($this->categories)) {
                foreach ($this->categories as $category) {
                    ?>
                    <li class="category_new <?php echo $category->slug ?>">
                        <a data-category="<?php echo $category->id; ?>" href="#<?php echo $category->slug; ?>">
                            <span class="text_block" ><?php echo $category->name; ?></span>
                        </a>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <?php
        break;

    case "cta":
    case "pages":
        ?>
        <ul id="search-create" class="span12">
            <li><input class="custom_text" type="text" name="template_search" placeholder="Search by name" id="template_search" /></li>
            <li><a class="mits-btn mits-btn-custom mits-btn-black create-page-btn" id="box_search_btn" href="#"><i class="fa fa-search"></i> Search</a></li>
            <li><a class="mits-btn mits-btn-custom mits-btn-black" href="<?php echo ADVISORLEAD_URL . "/templates/" ?>"><i class="fa fa-plus-circle"></i> Create New <?php echo $current_view == 'cta' ? 'CTA' : 'Page' ?></a></li>
        </ul>
        <?php
        break;

    case "advisorlead":
        ?>
        <ul class="nav">
            <li><a href="<?php echo ADVISORLEAD_URL . "/dashboard" ?>"><i class="fa fa-home fa-2x"></i> My Dashboard</a></li>
            <li><a href="<?php echo ADVISORLEAD_URL . "/dashboard/images" ?>"><i class="fa fa-image fa-2x"></i> My Images</a></li>
            <li><a href="<?php echo ADVISORLEAD_URL . "/dashboard/integrations" ?>"><i class="fa fa-cogs fa-2x"></i> Email Services</a></li>
        </ul>
        <?php
        break;
}
$sub_menu = ob_get_clean();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >
    <head>  
        <meta charset="utf-8" />
        <title>AdvisorLead&trade;</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="AdvisorLead" name="description" />
        <?php
        switch ($current_view) {
            case "advisorlead":
                ?>
                <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/js/royalslider/royalslider.css" ?>"/>
                <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/js/royalslider/skins/universal/rs-universal.css" ?>"/>
                <?php
                break;
        }
        ?>
        <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/css/bootstrap.min.css" ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/css/bootstrap-responsive.min.css" ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/css/jquery-ui.min.css" ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/css/template.css" ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/css/left_menu.css" ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo ASSETS_URL . "/css/font-awesome.min.css" ?>"/>

        <script type="text/javascript" src="<?php echo ASSETS_URL . "/js/jquery.min.js" ?>"></script>                    
        <script>
            var API_URL = '<?php echo JURI::base() . "index.php?option=com_advisorlead&task=$current_view.api_call" ?>';
        </script>
		
		<link type="text/css" href="<?php echo ASSETS_URL; ?>/js/fancybox/jquery.fancybox.css?v=2.1.5" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/fancybox/jquery.fancybox.js?v=2.1.5"></script>
		
<script>
var app_id = '<?php echo APP_ID;?>';

window.fbAsyncInit = function() {
	FB.init({
		appId: app_id,
		version: 'v2.0',
		status: false,
		cookie: true,
		xfbml: false
	});
};

(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.com/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
		
    </head>
    <body id="wrapper">
	<div id="fb-root"></div>
        <div class="container">
            <div class="row-fuild" style="position: relative;">
                <a href="<?php echo ADVISORLEAD_URL; ?>" id="logo"></a>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <div class="row-fluid">
                        <div class="span12">
                            <ul id="left_menu" class="nav customize">
                                <?php foreach ($main_menu as $view => $item) { ?>
                                    <li class="<?php echo $current_view == $view ? 'active' : '' ?>">
                                        <a href="<?php echo $item['link'] ?>"><?php echo $item['name'] ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                            <div class="clearfix"></div>
                            <div id="sub_nav">
                                <?php echo $sub_menu; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span9">
                    <div id="alert_placeholder"></div>
                    <div class="container-fluid">

