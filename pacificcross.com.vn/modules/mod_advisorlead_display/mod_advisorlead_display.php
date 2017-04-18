<?php

// no direct access
defined('_JEXEC') or die;

$input = JFactory::getApplication()->input;
$view = $input->get('view');

//var_dump($view);
//exit;

if ($view != 'login') {

    require_once (JPATH_SITE . DS . 'components' . DS . 'com_advisorlead' . DS . 'init_variable.php');
		
	JLoader::import('cta', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'models');
	$model = JModelLegacy::getInstance('cta', 'AdvisorleadModel');
	echo $model->show_published_cta();
			
	
}//if