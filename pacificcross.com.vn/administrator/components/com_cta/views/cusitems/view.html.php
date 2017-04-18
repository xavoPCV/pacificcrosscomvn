<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of articles.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @since		1.6
 */
class CtaViewCusitems extends JViewLegacy {
	
	public function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/cta.php';
		JToolBarHelper::title(JText::_('Custom Items'));
		
		JToolBarHelper::addNew('cusitem.add');
		JToolBarHelper::editList('cusitem.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'cusitems.delete', 'JTOOLBAR_REMOVE');
		
	}
}