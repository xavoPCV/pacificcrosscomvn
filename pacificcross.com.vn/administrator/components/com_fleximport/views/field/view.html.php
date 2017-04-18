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

class FleximportViewField extends JViewLegacy {
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

		$document = JFactory::getDocument();

    	$pluginName = $this->item->get('field_type', 'text');
    	$pluginpath = JPATH_COMPONENT_ADMINISTRATOR . '/classes/plugins/fields/' . $pluginName . '.xml';

    	$options['control'] = "jform";

    	$formPlugin = new JForm('fleximport.plugin', $options);
    	if (file_exists($pluginpath)) {
    		$lang = JFactory::getLanguage();
    		$lang->load('plg_fleximport_fields_' . $pluginName, JPATH_ADMINISTRATOR);
    		// Get the plugin form.
    		if (!$formPlugin->loadFile($pluginpath, false, '//form')) {
    			throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
    		}
    	}

    	$formPlugin->bind(array('params' => $this->item->params));
    	$this->pluginForm = $formPlugin;

    	$this->addToolbar();

        parent::display($tpl);
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.6
	 */
	protected function addToolbar()
	{
        JFactory::getApplication()->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$isNew = ($this->item->id == 0);

		JToolBarHelper::title(JText::_('COM_FLEXIMPORT_' . ($isNew ? 'ADD_FIELD' : 'EDIT_FIELD')), 'fields');

		if ($isNew) {
			// For new records, check the create permission.
			if ($user->authorise('core.create', 'com_fleximport')) {
				JToolBarHelper::apply('field.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('field.save', 'JTOOLBAR_SAVE');
			}
			JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CANCEL');
		}else {
			if ($user->authorise('core.edit', 'com_fleximport')) {
				// We can save the new record
				JToolBarHelper::apply('field.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('field.save', 'JTOOLBAR_SAVE');
			}
			JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
