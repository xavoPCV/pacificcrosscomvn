<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_apicontent
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('CtaHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/cta.php');

class CtaController extends JControllerLegacy {
	 
	protected $default_view = 'report';
	 
	public function display($cachable = false, $urlparams = false) {
		
		// Load the submenu.
		CtaHelper::addSubmenu(JRequest::getCmd('view', 'report'));
		
		parent::display();
		
		return $this;
	}
}
