<?php
// no direct access
defined('_JEXEC') or die;

$baseurl = JURI::base(true);
$doc = JFactory::getDocument();
$doc->addStyleSheet($baseurl.'/modules/'.$module->module.'/assets/style.css');

//JLoader::import('cta', JPATH_ROOT . '/administrator/components/com_cta/models');
//change this for not conflict with advisorlead module
require_once JPATH_ROOT.'/administrator/components/com_cta/models/cta.php';


$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));

//JHtml::_('behavior.formvalidation');

$modid = "modctareport".$module->id;




if ($params->get('mod_type')==1) {

	$content_css = "
	#$modid .ctalegend {
		color:".$params->get('title_color', '#fff').";\r\n
		background-color:".$params->get('title_bg', '#000').";\r\n
		
		font-size:".$params->get('font_size', '14')."px !important;\r\n
		font-weight:".$params->get('font_weight', 'bold')." !important;\r\n
		
		
	}\r\n
	#$modid button.ctabutton {
		color:".$params->get('button_color', '#fff').";\r\n
		background-color:".$params->get('button_bg', '#000').";\r\n
		
		
	}\r\n
	#$modid .cta_report_form > ul {
		color:".$params->get('main_color', '#fff').";\r\n
		background-color:".$params->get('main_bg', '#4169E1').";\r\n
	}\r\n
	#$modid .cta_report_form ul li {
		color:".$params->get('main_color', '#000')." !important;\r\n
		
		font-size:".$params->get('font_size', '14')."px !important;\r\n
		font-weight:".$params->get('font_weight', 'bold')." !important;\r\n
	}
	";
	
	$doc->addStyleDeclaration($content_css);
	
	$selectedVideo_a = $params->get('videos');
	$limit_num = intval($params->get('lastest_limit_num', NULL));
	$cusitems_a = $params->get('cusitems');
	
	
	if (is_array($selectedVideo_a) && count($selectedVideo_a) ) {
		$videos = $ctaModel->getVideos( $selectedVideo_a );
	} else if ( $limit_num ) {
		$videos = $ctaModel->getVideos( NULL, $limit_num );
	} else if  (is_array($cusitems_a) && count($cusitems_a) ) {
		
		JLoader::import('cusitems', JPATH_ROOT . '/administrator/components/com_cta/models');
		$CusitemsModel = JModelLegacy::getInstance('Cusitems', 'CtaModel', array('ignore_request' => true));
		$CusitemsModel->setState('filter.selected_item_a', $cusitems_a);
		$cusitems = $CusitemsModel->getItems();
	}//if
	
	//echo '<pre>';
	//var_dump($videos);
	//exit;
	
	
	require JModuleHelper::getLayoutPath('mod_ctareport', $params->get('layout', 'default'));
} else {
	$videos = $ctaModel->getVideos(NULL, $params->get('num_rotate'));
	
	list($slide_code) = $ctaModel->makeSlider($videos, 1, '', $params->get('loadjQuery', 1));
	
	require JModuleHelper::getLayoutPath('mod_ctareport', $params->get('layout', 'rotate'));
	
}//if