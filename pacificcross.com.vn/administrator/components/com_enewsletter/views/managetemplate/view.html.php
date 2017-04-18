<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 
/**
 * Managetemplate View class 
 */
class EnewsletterViewManagetemplate extends JView
{

		
        // Overwriting JView display method
    function display($tpl = null) 
    {
			
     	$app = JFactory::getApplication();					
		$this->templatedata      = $this->get('Alltemplates');		
		
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
		JToolBarHelper::title(JText::_('Manage Templates'));
    JToolBarHelper::back('Menu','index.php?option=com_enewsletter');
    JToolBarHelper::divider();
		JToolBarHelper::help('../setup_help.html', true);
	}
}