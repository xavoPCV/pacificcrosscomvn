<?php
/*------------------------------------------------------------------------
# advisorlead.php - Advisor Lead Component
# ------------------------------------------------------------------------
# author    Vu Nguyen
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   iexodus.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_advisorlead')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
};

// require helper files
JLoader::register('AdvisorleadHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'advisorlead.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Add CSS file for all pages
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_advisorlead/assets/css/advisorlead.css');
$document->addScript('components/com_advisorlead/assets/js/advisorlead.js');

// Get an instance of the controller prefixed by Advisorlead
$controller = JControllerAdmin::getInstance('Advisorlead');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>