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

/**
 * Advisorlead component helper.
 */
abstract class AdvisorleadHelper
{
	/**
	 *	Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('Advisorlead'), 'index.php?option=com_advisorlead&view=advisorlead', $submenu == 'advisorlead');
	}

	/**
	 *	Get the actions
	 */
	public static function getActions($Id = 0)
	{
		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($Id)){
			$assetName = 'com_advisorlead';
		} else {
			$assetName = 'com_advisorlead.message.'.(int) $Id;
		};

		$actions = JAccess::getActions('com_advisorlead', 'component');

		foreach ($actions as $action){
			$result->set($action->name, $user->authorise($action->name, $assetName));
		};

		return $result;
	}
}
?>