<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework'); 

$baseurl = JURI::base();

$page_id = (int)$params->get('page_id', 0);

if ($page_id) {
	
	require_once (JPATH_SITE . DS . 'components' . DS . 'com_advisorlead' . DS . 'init_variable.php');
	
	JLoader::import('cta', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'models');
	$model = JModelLegacy::getInstance('cta', 'AdvisorleadModel');
	
	$cc_data = $model->get_cta($page_id);
	
	if (!$cc_data) return false;
	
    
	$screenshot = $params->get('myimage', '');
	if ($screenshot) {
		$screenshot = $baseurl.$screenshot;
	} else {
		$db = JFactory::getDbo();
        $query = "SELECT `slug` as template_slug FROM " . TEMPLATES_TABLE . " WHERE id = ".$cc_data->template_id;
		$db->setQuery($query);
		$template_slug = $db->loadResult();
		if ($template_slug) $screenshot = ASSETS_URL . "/inc/cta-templates/$template_slug/screenshot.png";
	}//if
	
	$doc = JFactory::getDocument();
	$doc->addStyleSheet($baseurl.'modules/'.$module->module.'/assets/style.css');

	require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));	
	
}//if


