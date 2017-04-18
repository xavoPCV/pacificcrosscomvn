<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.'/administrator/components/com_enewsletter/SmartDOMDocument.class.php';
require(JPATH_SITE.'/components/com_enewsletter/setup_defines.php');

$loggeduser = JFactory::getUser();

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_enewsletter')) 
{
        return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
 

// import joomla controller library
jimport('joomla.application.component.controller');

// Register helper class
JLoader::register('UsersHelper', dirname(__FILE__) . '/helpers/users.php');


JLoader::register('enewsletterHelper', dirname(__FILE__) . '/helpers/template.php');
 
// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance('Enewsletter');
 
// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();