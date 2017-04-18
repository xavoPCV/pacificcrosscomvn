<?php
// No direct access
defined('_JEXEC') or die;

class CtaControllerSlide extends JControllerLegacy {
	
	
	function save() {
		
		//print_r($_POST);
		//exit;
		#JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		$id = JRequest::getInt('id');
		$nu_slide = JRequest::getInt('nu_slide', 1);
		$is_auto = JRequest::getInt('is_auto');
		$cta_url = JRequest::getVar('cta_url', NULL);
		$slide_list = JRequest::getVar('slide_list', array());
		
		//echo $id;
		//exit;
		
		if ($id) {
			
			
			$ctaModel = &$this->getModel('Cta');
			
			if ($is_auto) {
				$video_rows = $ctaModel->getVideos(NULL, 6);
			} else if (count($slide_list)) {
				$video_rows = $ctaModel->getVideos($slide_list);
				$slide_list = new JRegistry($slide_list);
				$slide_list = $slide_list->toString();
			}//if
			
			if ($video_rows) {
			
				list($slide_code, $slide_code2) = $ctaModel->makeSlider($video_rows, $nu_slide, $cta_url);
				
				//echo $slide_code;
				//exit;
			
				$db = & JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->update('#__cta_setting');
				$query->set('`nu_slide` = '.(int)$nu_slide);
				$query->set('`slide_code` = '.$db->quote($slide_code));
				$query->set('`slide_code2` = '.$db->quote($slide_code2));
				$query->set('`slide_video` = '.$db->quote($slide_list));
				$query->where('`id` = '.(int)$id);
				$db->setQuery($query);
				$db->execute();
			
				//echo $query;
				//exit;
				
			}//video_rows
			
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=slide', false));
			return true;
			
		}//if
		
		
	}//func
}
