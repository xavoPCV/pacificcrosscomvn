<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
require(JPATH_SITE.'/components/com_enewsletter/setup_defines.php');


require_once JPATH_SITE.'/administrator/components/com_enewsletter/SmartDOMDocument.class.php';


JLoader::register('enewsletterHelper', JPATH_SITE . '/administrator/components/com_enewsletter/helpers/template.php');

                  



$controller = JControllerLegacy::getInstance('Enewsletter');

$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();