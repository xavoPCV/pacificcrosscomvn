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

class FlexImportController extends JControllerLegacy {
	protected $default_view = 'dashboard';

	public function display($cachable = false, $urlparams = false)
	{
		$view = JFactory::getApplication()->input->get('view',$this->default_view);
		$user = JFactory::getUser();

		if (!$user->authorise('core.manage', 'com_fleximport')) {
			$this->setError(JText::_('COM_FLEXIMPORT_NOACCESS'));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_fleximport&view='.$this->default_view, false));
			return $this;
		}
		// Load the submenu.
		FleximportHelper::addSubmenu($view);
		parent::display($cachable, $urlparams);
		return $this;
	}
}