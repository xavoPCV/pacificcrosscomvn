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
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the flexIMPORT View
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportViewDashboard extends JViewLegacy {
    public $icons = null;
    /**
     * Creates the Entrypage
     *
     * @since 1.0
     */
    function display($tpl = null)
    {
        // activate the tooltips
        $icons = array(
            array(
                'link' => JRoute::_('index.php?option=com_fleximport&view=types'),
                'image' => '48-types',
                'id' => 'icon-48-types',
                'text' => JText::_('COM_FLEXIMPORT_TYPES'),
                'access' => array('core.manage', 'com_fleximport', 'core.create', 'com_fleximport', 'core.delete', 'com_fleximport', 'core.edit', 'com_fleximport')
                ),
            array(
                'link' => JRoute::_('index.php?option=com_fleximport&view=fields'),
                'image' => '48-fields',
                'id' => 'icon-48-fields',
                'text' => JText::_('COM_FLEXIMPORT_FIELDS'),
                'access' => array('core.manage', 'com_fleximport', 'core.create', 'com_fleximport', 'core.delete', 'com_fleximport', 'core.edit', 'com_fleximport')
                ),
            array(
                'link' => JRoute::_('index.php?option=com_fleximport&view=import'),
                'image' => '48-import',
                'id' => 'icon-48-import',
                'text' => JText::_('COM_FLEXIMPORT_IMPORT'),
                'access' => array('core.manage', 'com_fleximport', 'fleximport.import', 'com_fleximport')
                ),
            array(
                'link' => JRoute::_('index.php?option=com_fleximport&view=export'),
                'image' => '48-export',
                'id' => 'icon-48-export',
                'text' => JText::_('COM_FLEXIMPORT_EXPORT'),
                'access' => array('core.manage', 'com_fleximport', 'fleximport.export', 'com_fleximport')
                ),
            array(
                'link' => JRoute::_('index.php?option=com_fleximport&view=logs'),
                'image' => '48-logs',
                'id' => 'icon-48-logs',
                'text' => JText::_('COM_FLEXIMPORT_LOGS'),
                'access' => array('core.manage', 'com_fleximport')
                ),
            array(
                'link' => JRoute::_('index.php?option=com_fleximport&view=plugins'),
                'image' => '48-plugins',
                'id' => 'icon-48-plugins',
                'text' => JText::_('COM_FLEXIMPORT_PLUGINS'),
                'access' => array('core.admin', 'com_fleximport')
                ),
            );
        $this->addToolBar();

        $this->icons = $icons;

    	parent::display($tpl);
    }

    protected function addToolbar()
    {
        $user = JFactory::getUser();
        JToolBarHelper::title(JText::_('COM_FLEXIMPORT_DASHBOARD'), 'fleximport');
        if ($user->authorise('core.admin', 'com_fleximport')) {
            JToolBarHelper::preferences('com_fleximport');
        }
    }
}