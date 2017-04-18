<?php
/**
 * @version     1.0.0
 * @package     com_faq
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sang <thanhsang52@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Faq helper.
 */
class FaqHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQ_TITLE_FAQS'),
			'index.php?option=com_faq&view=faqs',
			$vName == 'faqs'
		);
		JSubMenuHelper::addEntry(
			JText::_('API Product Updates'),
			'index.php?option=com_faq&view=updates',
			$vName == 'updates'
		);
		JSubMenuHelper::addEntry(
			'Categories (Faqs - Faq Category)',
			"index.php?option=com_categories&extension=com_faq.faqs.faq_category_id",
			$vName == 'categories.faqs'
		);
		
		if ($vName=='categories.faqs.faq_category_id') {			
		JToolBarHelper::title('Faq: Categories (Faqs - Faq Category)');		
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_faq';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
