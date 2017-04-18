<?php
/*------------------------------------------------------------------------
# controller.php - Advisor Lead Component
# ------------------------------------------------------------------------
# author    Vu Nguyen
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   iexodus.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of Advisorlead component
 */
class AdvisorleadController extends JControllerAdmin
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'Advisorlead'));

		// call parent behavior
		parent::display($cachable);

		// set view
		$view = strtolower(JRequest::getVar('view'));

		// Set the submenu
		AdvisorleadHelper::addSubmenu($view);
	}
}
?>