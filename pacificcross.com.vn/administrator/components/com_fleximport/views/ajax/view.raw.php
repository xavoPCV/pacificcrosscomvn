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
jimport('joomla.application.component.view');

class FleximportViewAjax extends JViewLegacy {
    function display($tpl = null)
    {
        $ajax = JFactory::getApplication()->input->get('ajax');
        switch ($ajax) {
            case 'params':
            case 'flexifields':
            	$this->{$ajax}();
                break;
            default:
        }
        jexit();
    }
    private function params()
    {
        $cid = JFactory::getApplication()->input->get('cid');
        $plugin = JFactory::getApplication()->input->get('plugin');
        $folder = JFactory::getApplication()->input->get('folder');

        $lang = JFactory::getLanguage();
        $lang->load('plg_fleximport_' . $folder . '_' . $plugin, JPATH_ADMINISTRATOR);

        $form = $this->get('ParamsForm');

        if ($plugin) {
            echo '<fieldset class="panelform"><ul class="adminformlist">';
            foreach ($form->getFieldset('fleximport') as $field) {
                echo '<li>' . $field->label . $field->input . '</li>' ;
            }
            echo '</ul></fieldset>';
        }
    	jexit();
    }
    private function flexifields()
    {
    	$form = $this->get('FieldForm');
 		echo $form->getInput('flexi_field_id');
    	jexit();
    }
}