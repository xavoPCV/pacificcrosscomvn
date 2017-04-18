<?php
/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
//$app = JFactory::getApplication();
//$router = $app->getRouter();
//$router->setMode(0);
$controller = JControllerLegacy::getInstance('Gnosis');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
