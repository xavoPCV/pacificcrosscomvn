<?php
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class EnewsletterModelWeeklyupdatearchive extends JModel {
	
	function getItems(){
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		
		
		$query->select('*, YEAR(`dte_created`) as `archive_year`')->from('#__enewsletter')
				->where("`type` LIKE 'weeklyupdate'")
				->where("`email_sent_status` = 1")
				->where("`email_id` != ''")
				->order('`dte_created` DESC');
				
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		$rows_weekly = array();
		foreach ($rows as $row) {
			$rows_weekly[$row->archive_year][] = $row;
		}//for
		
		return $rows_weekly;
		
	}//func
	
	
	function makescreens(){
	
		ignore_user_abort(true); // run script in background
		set_time_limit(0); // run script forever 
		
		require_once(JPATH_ADMINISTRATOR.'/components/com_enewsletter/classes/screenmaster/function.php');
		
		if (!file_exists(JPATH_ROOT.'/media/com_enewsletter/screens')) {
			mkdir(JPATH_ROOT.'/media/com_enewsletter/screens');
		}//if
		
		
		$rows_weekly = $this->getItems();
		foreach ($rows_weekly as $year => $rows_weekly_arr) {
			foreach ($rows_weekly_arr as $row) {
				$id = $row->id;
				$filename_no_ext = $id;
				if ( !file_exists(JPATH_ROOT.'/media/com_enewsletter/screens/'.$filename_no_ext.'.jpg') ) {
					$urlpreview_full = JURI::base(false).'index.php?option=com_enewsletter&view=weeklyupdatearchive&format=raw&id='.$id;
					$screenshot_new = false;
					
					//echo '<p>'.$urlpreview_full;
					//continue;
					
					$screenshot_new = do_screenshot($urlpreview_full, JPATH_ROOT.'/', $filename_no_ext);
					if ( $screenshot_new ) {
						rename(JPATH_ADMINISTRATOR.'/components/com_enewsletter/classes/screenmaster/cache/'.$screenshot_new, JPATH_ROOT.'/media/com_enewsletter/screens/'.$screenshot_new);
					}//if
				}//if
			}//for
		}//for
		
		return true;
		
	}//func
	
   
}