<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 
/**
 * History view class 
 */
class EnewsletterViewHistory extends JView
{
	
    // Overwriting JView display method
    function display($tpl = null) 
    {
  			$this->item		= $this->get('History');
  
  			// Include the component HTML helpers.
  			JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
  			     
  			parent::display($tpl);
  			$this->addToolbar();
      }
		
	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
  
    $app = JFactory::getApplication();
		JToolBarHelper::title(JText::_('History'));	

		JToolBarHelper::cancel('history.cancel');
		JToolBarHelper::divider();
    	JToolBarHelper::back('Menu','index.php?option=com_enewsletter');    
		JToolBarHelper::divider();
		JToolBarHelper::help('../history.html', true);
	}
}