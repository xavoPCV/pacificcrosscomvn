<?php
defined('_JEXEC') or die();

require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/cta.php';

class CtaViewForm extends JViewLegacy {
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$params	= $app->getParams();
		$this->assignRef('params', $params);
		
		
		#print_r($params);
		
		//print_r($_GET);
		
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		$this->setting = $ctaModel->getSetting();
		
		//is Submit form mod
		$video_arr = JRequest::getVar('video_id', array());
		if (!count($video_arr) && $app->getUserState('com_cta.moduledata') ) {
			$video_arr = $app->getUserState('com_cta.moduledata');
		}//if
		
		//is Submit form mod
		$cusitem_id_arr = JRequest::getVar('cusitem_id', array());
		if (!count($cusitem_id_arr) && $app->getUserState('com_cta.moduledatacusitem') ) {
			$cusitem_id_arr = $app->getUserState('com_cta.moduledatacusitem');
		}//if
		
		//print_r($cusitem_id_arr);
		//exit;
		
		$this->doMod = false;
		if (count($video_arr)) {
			$this->doMod = true;
			//overwrite setting
			$this->setting->is_auto = false;
			
			//$video_s = $video_arr[0];
			//$v_id = substr($video_s, 0, strpos($video_s, '|'));
			//$v_name = substr(strchr($video_s, '|'), 1);
			
			$selected_video = array();
			
			foreach ($video_arr as $video_s) {
				$v_id = substr($video_s, 0, strpos($video_s, '|'));
				$v_name = substr(strchr($video_s, '|'), 1);
				$selected_video[] = $v_id;
			}//for
			
			
			$this->setting->selected_video = $selected_video;
			//print_r($this->setting->selected_video);
			//exit;
			
			$app->setUserState('com_cta.moduledata', $video_arr);
		}//if
		
		if (count($cusitem_id_arr)) {
			$this->doMod = true;
			$this->setting->is_auto = false;
			$this->setting->selected_video = array(0);
			$app->setUserState('com_cta.moduledatacusitem', $cusitem_id_arr);
			
			
			$this->assignRef('cusitem_id_arr', $cusitem_id_arr);
			
		}//if
		
		
		if ($this->setting->is_auto) {
			$this->videos = $ctaModel->getVideos(NULL, $this->setting->auto_num);
		} else {
			$this->videos = $ctaModel->getVideos($this->setting->selected_video);
		}//if
		
		
		//echo '<pre>';
		//print_r($this->videos);
		//exit;
		
		parent::display($tpl);
	}
}