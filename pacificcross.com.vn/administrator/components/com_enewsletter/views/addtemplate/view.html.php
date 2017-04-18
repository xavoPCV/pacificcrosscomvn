<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 
/**
 * Addtemplate View class 
 */
class EnewsletterViewAddtemplate extends JView
{

		
        // Overwriting JView display method
    function display($tpl = null) 
    {
      $app = JFactory::getApplication();
      $id = JRequest::getVar('id');
      if($id != '' && $app->getUserState("com_enewsletter.loaddata") != 'no'){
        $this->get('Template');
      }
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
		JToolBarHelper::title(JText::_('Add/Edit Template'));
		JToolBarHelper::apply('addtemplate.save');
    JToolBarHelper::divider();
		JToolBarHelper::custom('addtemplate.preview', 'preview.png', '', JText::_('Preview Email'), false);
		JToolBarHelper::divider();
		JToolBarHelper::cancel('addtemplate.cancel');
		JToolBarHelper::divider();
    JToolBarHelper::back('Menu','index.php?option=com_enewsletter');
		JToolBarHelper::divider();
		JToolBarHelper::help('../setup_help.html', true);
	}
}