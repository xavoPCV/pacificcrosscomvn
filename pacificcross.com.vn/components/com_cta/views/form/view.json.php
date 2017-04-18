<?php
defined('_JEXEC') or die();


require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/cta.php';

class CtaViewForm extends JViewLegacy {

	public function display($tpl = null) {
	
	
		$app = JFactory::getApplication();
		$params	= $app->getParams();
		$this->assignRef('params', $params);
		
		
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		$this->setting = $ctaModel->getSetting();
		
		if ($this->setting->is_auto) {
			$this->videos = $ctaModel->getVideos(NULL, $this->setting->auto_num);
		} else {
			$this->videos = $ctaModel->getVideos($this->setting->selected_video);
		}//if
		//print_r($this->setting);
		
		
		$data = parent::loadTemplate($tpl);
		echo json_encode( $data );
	
	}//func
	
}//class