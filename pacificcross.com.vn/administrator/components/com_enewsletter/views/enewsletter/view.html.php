<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 
/**
 * Enewsletter view class 
 */
class EnewsletterViewEnewsletter extends JView
{
        // Overwriting JView display method
    function display($tpl = null) 
    {
			$app = JFactory::getApplication();
			$loggeduser = JFactory::getUser();
			
			
			if(in_array('9',$loggeduser->groups)){
			
				$this->items		= $this->get('Advisordetails');	
				
				// Get weeklyupdate groups and set to state
				$weeklyupdategroups = $this->get('WeeklyupdateGroups');				
				$weeklyupdategroup = array();
				foreach($weeklyupdategroups as $wu){
					$weeklyupdategroup[] = $wu->group_id;
				}
				
				$this->user = 'advisor';
				$app->setUserState("com_enewsletter.User",'advisor');
				$app->setUserState("com_enewsletter.UpdateGroups",$weeklyupdategroup);
				
				
				// Constant contact API key
				$app->setUserState("com_enewsletter.APIKEY",CONSTANT_APIKEY);			
				$app->setUserState("com_enewsletter.ACCESS_TOKEN",$this->items->api_key);
				$app->setUserState("com_enewsletter.API",$this->items->newsletter_api);
				$app->setUserState("com_enewsletter.Weekly_subject",$this->items->update_subject);
				$app->setUserState("com_enewsletter.Weekly_intro",$this->items->weekly_update_intro);
										

			}else if(in_array('8',$loggeduser->groups)){
				$app->setUserState("com_enewsletter.User",'admin');
				$this->user = 'admin';
			}else{
				echo 'other';
				
			}
				
			JToolBarHelper::title(JText::_('Build E-Newsletter Control Panel'));

            parent::display($tpl);
        }
}