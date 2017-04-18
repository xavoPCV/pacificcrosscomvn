<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 
/**
 * Advisorsetting View class 
 */
class EnewsletterViewAdvisorsetting extends JView
{

		
    // Overwriting JView display method
    function display($tpl = null) 
    {
			
		$app = JFactory::getApplication();
		
		
		
    
		$this->form			= $this->get('Form');
		$this->item			= $this->get('Item');						
		$this->post = $post = JRequest::get('post');
		$db= JFactory::getDbo();
		if (!$post['iduser']){
			$this->details      = $this->get('Advisordetails');
		}else{
			$qr = "SELECT * FROM #__advisorsettings where user_id = '".$post['iduser']."'";
			$db->setQuery($qr);
			if ( $post['datade'] == 1 ){
				$this->details      = $this->get('Advisordetails');
			}else{
				$this->details      = $db->loadObject();
		
				$app->setUserState("com_enewsletter.advisoremail",  $this->details->from_email);
		
			}
			//  $this->details      = ;
		}
		
		// Display the view
		JHtml::stylesheet($this->baseurl.'/com_enewsletter/css/jquery-ui.css', array(), true);
		
		$this->addToolbar();
				   
		$qr = "SELECT * FROM #__users where block = 0 ORDER BY id ASC";
		$db->setQuery($qr);
		$this->us = $db->loadObjectList();
		parent::display($tpl);
    }
		
	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
    	$app = JFactory::getApplication();
		JToolBarHelper::title(JText::_('E-Newsletter Set Up'));
		JToolBarHelper::apply('advisorsetting.apply');
		JToolBarHelper::divider();
		JToolBarHelper::cancel('advisorsetting.cancel');
		JToolBarHelper::divider();
    	JToolBarHelper::back('Menu','index.php?option=com_enewsletter');
    
		JToolBarHelper::divider();
		JToolBarHelper::help('../setup_help.html', true);
	}
}