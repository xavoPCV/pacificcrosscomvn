<?php
/**
 *
 * @version 2.0
 * @package Joomla
 * @subpackage flexIMPORT
 * @copyright (C) 2011 NetAssoPro - www.netassopro.com
 * @license GNU/GPL v2
 *
 * flexIMPORT is a addon for the excellent FLEXIcontent component. Some part of
 * code is directly inspired.
 * @copyright (C) 2009 Emmanuel Danan
 * see www.vistamedia.fr for more information
 *
 * flexIMPORT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
defined('_JEXEC') or die;

/**
 * View class for the flexIMPORT logs screen
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportViewLogs extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	protected $filters;
	protected $user;
    function display($tpl = null)
    {

    	$this->items = $this->get('Items');
    	$this->state = $this->get('State');
    	$this->pagination = $this->get('Pagination');
    	$this->params = JComponentHelper::getParams('com_fleximport');
    	$this->user = JFactory::getUser();

    	$filters = array();
    	$filters['type'] = JHtml::_('fifilters.type',$this->state->get('filter.type'));

    	$this->filters = $filters;

    	if (count($errors = $this->get('Errors'))) {
    		throw new Exception( implode("\n", $errors), 500);
    		return false;
    	}

    	$this->addToolBar();

    	parent::display($tpl);
    }

	protected function addToolbar()
	{
		$user = JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_FLEXIMPORT_LOGS'), 'logs');

		if ($user->authorise('core.delete', 'com_fleximport')) {
			JToolBarHelper::deleteList('', 'logs.delete', 'JTOOLBAR_DELETE');
			JToolBarHelper::divider();
			JToolBarHelper::spacer();
		}
		if ($user->authorise('core.admin', 'com_fleximport')) {
			JToolBarHelper::preferences('com_fleximport');
		}
	}
}
