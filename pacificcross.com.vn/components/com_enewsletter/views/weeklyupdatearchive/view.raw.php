<?php
defined('_JEXEC') or die;

class EnewsletterViewWeeklyupdatearchive extends JViewLegacy {

	public function display($tpl = null) {
	
	
		$app = JFactory::getApplication();
		
		
		$id = JRequest::getInt('id');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('*')->from('#__enewsletter')->where("`id` = ".(int)$id);
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		if ($row->content) {
			echo $row->content;
		}//if
		
		$app->close();
		
	}//func

}
