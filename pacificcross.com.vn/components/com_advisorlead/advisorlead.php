<?php

/* ------------------------------------------------------------------------
  # advisorlead.php - Advisor Lead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

global $user;
if (empty($user)) {
    $user = JFactory::getUser();
}

require_once (JPATH_SITE . DS . 'components' . DS . 'com_advisorlead' . DS . 'init_variable.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Advisorlead
$controller = JControllerLegacy::getInstance('Advisorlead');

// Perform the request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>