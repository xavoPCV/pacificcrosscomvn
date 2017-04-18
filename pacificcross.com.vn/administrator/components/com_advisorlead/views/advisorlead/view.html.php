<?php
/*------------------------------------------------------------------------
# view.html.php - Advisor Lead Component
# ------------------------------------------------------------------------
# author    Vu Nguyen
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   iexodus.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * advisorlead View
 */
class AdvisorleadViewadvisorlead extends JView
{
	/**
	 * Advisorlead view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JToolBarHelper::title(JText::_('Advisorlead Manager'), 'advisorlead');
	}

	/**
	 * Method to set up the document properties
	 *
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('Advisorlead Manager - Administrator'));
	}
}
?>