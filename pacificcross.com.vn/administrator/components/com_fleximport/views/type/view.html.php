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

class FleximportViewType extends JViewLegacy {
    protected $form;
    protected $item;
    protected $state;
    protected $params;
    protected $user;

    function display($tpl = null)
    {
        // Initialiase variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->params = JComponentHelper::getParams('com_fleximport');
        $this->user = JFactory::getUser();

        $pluginName = $this->item->get('import_type', 'csv');
        $pluginpath = JPATH_COMPONENT_ADMINISTRATOR . '/classes/plugins/types/' . $pluginName . '.xml';

        $options['control'] = "jform";

        $formPlugin = new JForm('fleximport.plugin', $options);

        if (file_exists($pluginpath)) {
            $lang = JFactory::getLanguage();
            $lang->load('plg_fleximport_types_' . $pluginName, JPATH_ADMINISTRATOR);
            // Get the plugin form.
            if (!$formPlugin->loadFile($pluginpath, false, '//form')) {
                throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
            }
        }

        $formPlugin->bind(array('params' => $this->item->params));
        $this->pluginForm = $formPlugin;
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception( implode("\n", $errors), 500);
            return false;
        }

        $this->addToolbar();

        $this->item->cronLinkImport = '';
        $this->item->cronLinkExport = '';
        // affiche le lien de la tache cron uniquement en modification et uniquement si le plugin system est activ�
        if ($this->params->get('allow_cron', 0) && $this->item->id && JPluginHelper::isEnabled('system', 'fleximport')) {
            $cronLink = JUri::base() . 'index.php?option=com_fleximport&format=raw&task=';
            $cronUser = JFactory::getUser();
            $cronAccess = base64_encode($cronUser->username . '||' . $cronUser->password);
            $this->item->cronLinkImport = $cronLink . 'import.cron&cronid=' . $this->item->id . '&crona=' . $cronAccess;
            $this->item->cronLinkExport = $cronLink . 'export.cron&cronid=' . $this->item->id . '&crona=' . $cronAccess;
        }

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since 1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu',true);
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $isNew = ($this->item->id == 0);

        JToolBarHelper::title(JText::_('COM_FLEXIMPORT_' . ($isNew ? 'ADD_TYPE' : 'EDIT_TYPE')), 'types');

        if ($isNew) {
            // For new records, check the create permission.
            if ($user->authorise('core.create', 'com_fleximport')) {
                JToolBarHelper::apply('type.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('type.save', 'JTOOLBAR_SAVE');
            }
            JToolBarHelper::cancel('type.cancel', 'JTOOLBAR_CANCEL');
        }else {
            if ($user->authorise('core.edit', 'com_fleximport')) {
                // We can save the new record
                JToolBarHelper::apply('type.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('type.save', 'JTOOLBAR_SAVE');
            }
            JToolBarHelper::cancel('type.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}