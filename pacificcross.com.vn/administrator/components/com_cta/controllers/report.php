<?php
// No direct access
defined('_JEXEC') or die;

class CtaControllerReport extends JControllerLegacy {
	
	
	function save() {
		
		#print_r($_POST);
		#exit;
		JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		$id = JRequest::getInt('id');
		$is_auto = JRequest::getInt('is_auto');
		$auto_num = JRequest::getInt('auto_num');
		$video_id = JRequest::getVar('video_id', array());
		
		if ($id) {
		
			$selected_video = new JRegistry($video_id);
			$selected_video = $selected_video->toString();
			
			$db = & JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update('#__cta_setting');
			$query->set('`is_auto` = '.(int)$is_auto);
			$query->set('`auto_num` = '.(int)$auto_num);
			$query->set('`selected_video` = '.$db->quote($selected_video));
			$query->where('`id` = '.(int)$id);
			$db->setQuery($query);
			$db->execute();
			
			#echo $query;
			#exit;
			//save to params
			$send_to_email = JRequest::getVar('send_to_email', NULL);
			
			
			$query = $db->getQuery(true);
			$query->select('`params`');
			$query->from('#__extensions');
			$query->where('`element` = "com_cta"');
			$query->where('`type` = "component"');
			$db->setQuery($query);
			$params = $db->loadResult();
			
			$params = json_decode($params);
			
			if (is_object($params)) {
				$params->send_to_email = $send_to_email;
				$params_str = json_encode($params);
				
				$query = $db->getQuery(true);
				
				$query->update('#__extensions');
				$query->set('`params` = '.$db->quote($params_str));
				$query->where('`element` = "com_cta"');
				$query->where('`type` = "component"');
				$db->setQuery($query);
				$db->execute();
				//echo $query->dump();
				
			}//if
			
			
			//exit;
			
			
			
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=report', false));
			return true;
			
		}//if
		
		
	}//func
}
