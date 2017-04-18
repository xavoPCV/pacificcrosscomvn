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

jimport('joomla.application.component.model');

class FleximportModelAjax extends JModelLegacy {
    function __construct()
    {
        parent::__construct();
        if (empty($this->context)) {
            $this->context = strtolower($this->option . '.' . $this->getName());
        }
    }
    public function getParamsForm()
    {
        $mainframe = JFactory::getApplication();
        $id = $mainframe->getUserStateFromRequest($this->context . ".id", 'cid');
        $folder = $mainframe->getUserStateFromRequest($this->context . ".folder", 'folder');
        $plugin = $mainframe->getUserStateFromRequest($this->context . ".name", 'plugin');

        $this->setState('plugin.id', $id);
        $this->setState('plugin.folder', $folder);
        $this->setState('plugin.name', $plugin);

        $this->loadPlugin();

        $pluginpath = JPATH_ADMINISTRATOR . '/components/com_fleximport/classes/plugins/' . $this->getState('plugin.folder') . '/' . $this->getState('plugin.name') . '.xml';
        if (!file_exists($pluginpath)) {
            throw new Exception(JText::sprintf('COM_PLUGINS_ERROR_FILE_NOT_FOUND', $this->getState('plugin.folder') . '.xml'));
            return false;
        }
        $options['control'] = "jform";
        $form = new JForm($this->context . 'plugin', $options);
        if (file_exists($pluginpath)) {
            // Get the plugin form.
            if (!$form->loadFile($pluginpath, false, '//form')) {
            	throw new Exception(JText::_('JERROR_LOADFILE_FAILED'), 400);
            }
        }
		$form->addFieldPath(array(JPATH_ADMINISTRATOR .'/components/com_fleximport/models/fields/'));
        $pluginParams = new JRegistry($this->getState('plugin.data'));
        $form->bind(array('params' => $pluginParams->toArray()));
        return $form;
    }
    private function loadPlugin()
    {
        if (!$this->getState('plugin.folder') || !$this->getState('plugin.name')) return false;

        if ($this->getState('plugin.data') === null) {
            $query = $this->_db->getQuery(true);
            $query->select('params');
            $query->from('#__fleximport_' . $this->getState('plugin.folder'));
            $query->where('id=' . (int)$this->setState('plugin.id'));
            $this->_db->setQuery($query);
            $result = $this->_db->loadResult();
            $this->setState('plugin.data', $result);
            return (boolean)$result;
        }
        return true;
    }

    public function getFieldForm()
    {
        $mainframe = JFactory::getApplication();
        $id = $mainframe->getUserStateFromRequest($this->context . ".id", 'cid');
        $type = $mainframe->getUserStateFromRequest($this->context . ".type", 'type');
        $plugin = $mainframe->getUserStateFromRequest($this->context . ".plugin", 'plugin');

        $this->setState('field.id', $id);
        $this->setState('field.type', $type);
        $this->setState('field.plugin', $plugin);

        $this->loadField();

        $options['control'] = "jform";
        $form = new JForm($this->context . 'field', $options);
        // Get the plugin form.
        if (!$form->loadFile(JPATH_ADMINISTRATOR . '/components/com_fleximport/models/forms/field.xml', false, '//form')) {
        	throw new Exception(JText::_('JERROR_LOADFILE_FAILED'), 400);
        }

        $form->bind($this->getState('field.data'));
        return $form;
    }
    private function loadField()
    {
        if (!$this->getState('field.id')) return false;

        if ($this->getState('field.data') === null) {
            $query = $this->_db->getQuery(true);
            $query->select('*');
            $query->from('#__fleximport_fields');
            $query->where('id=' . $this->setState('field.id'));
            $this->_db->setQuery($query);
            $result = $this->_db->loadObject();
            $result->type_id = $this->getState('field.type');
            $result->field_type = $this->getState('field.plugin');
            $this->setState('field.data', $result);
            return (boolean)$result;
        }
        return true;
    }
}