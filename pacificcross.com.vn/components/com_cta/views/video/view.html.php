<?php
defined('_JEXEC') or die();

require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/cta.php';

class CtaViewVideo extends JViewLegacy {
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$params	= $app->getParams();
		$this->assignRef('params', $params);
		
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		$this->setting = $ctaModel->getSetting();
		
		$layout = JRequest::getVar('layout', NULL);
		
		
		
		
		
		
		if ($layout=='video') {
			$this->vidfile = JRequest::getVar('vidfile');
			$this->vidimg = JRequest::getVar('vidimg');
			
			$app->setTemplate('system');
			
			JRequest::setVar('tmpl', 'component');
			$this->setLayout($layout);
		} else {
			$selected_video = $app->getUserState( "cta.selected_video", NULL );
			
			$selected_cusitem = $app->getUserState( "cta.selected_cusitem", NULL );
			
			//var_dump($selected_cusitem);
			//exit;
			
			if ( !$selected_video && !$selected_cusitem ) {
				$app->enqueueMessage('No videos selected!', 'warning');
				$app->redirect(JRoute::_('index.php?option=com_cta&view=form'));
				return false;
			}//if
			
			if ($selected_video) {
				$this->videos = $ctaModel->getVideos($selected_video);
				if (!$this->videos) {
					$app->enqueueMessage('No video selected!', 'warning');
					return false;
				}//if
				
				if (count($this->videos)==1) {
					$video = $this->videos[0];
					$this->vidfile = $video['VideoFile'];
					$this->vidimg = $video['ImgCTA'];
					$this->modal = 0;
					$this->setLayout('video');
				}//if
			} else if ($selected_cusitem) {
			
				$video = $selected_cusitem[0];
			
				if (count($selected_cusitem)==1 && in_array($video->file_type, array('flv','mp4','wmv')) ) {
					$this->cusvidfile = JURI::base(false).'media/com_cta/'.$video->file_name;
					$this->vidimg = '';
					$this->modal = 0;
					$this->setLayout('video');
					$this->video = $video;
				} else {
					$this->setLayout('cusitems');
					$this->assignRef('selected_cusitem', $selected_cusitem);
				}//if
				
			}//if
			
			
						
		}//if
		
		parent::display($tpl);
	}
}