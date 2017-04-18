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

abstract class JHtmlFifilters {
    public static function flexitype($value = null)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id AS value,name AS text');
        $query->from('#__flexicontent_types');
        $query->order('name ASC');
        $db->setQuery($query);
        if ($result = $db->loadObjectList()) {
            $options = array();
            $options[] = JHtml::_('select.option', '', ' - ' . JText::_('COM_FLEXIMPORT_FLEXI_TYPE') . ' - ');
            foreach ($result as $option) {
                $options[] = JHtml::_('select.option', $option->value, $option->text);
            }
            return JHtml::_('select.genericlist', $options, 'filter_flexi_type_id', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $value);
        } else {
            return;
        }
    }
    public static function id($value = null)
    {
        return '<input type="text" class="inputbox center" placeHolder="' . JText::_('COM_FLEXIMPORT_ID') . '" id="filter_id" name="filter_id"  size="2" value="' . $value . '" />';
    }
    public static function search($value = null)
    {
        return '<input type="text" class="inputbox" placeHolder="' . JText::_('COM_FLEXIMPORT_SEARCH') . '" id="filter_search" name="filter_search"  size="30" value="' . $value . '" />';
    }
    public static function type($value = null,$attribs='onchange="this.form.submit()"')
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id AS value,name AS text');
        $query->from('#__fleximport_types');
        $query->order('name ASC');
        $db->setQuery($query);
        if ($result = $db->loadObjectList()) {
            $options = array();
            $options[] = JHtml::_('select.option', '', ' - ' . JText::_('COM_FLEXIMPORT_TYPE') . ' - ');
            foreach ($result as $option) {
                $options[] = JHtml::_('select.option', $option->value, $option->text);
            }
            return JHtml::_('select.genericlist', $options, 'filter_type', $attribs, 'value', 'text', $value,'filter_type');
        } else {
            return;
        }
    }
    public static function usefor($value = null)
    {
        $options[] = JHtml::_('select.option', '1', JText::_('COM_FLEXIMPORT_ALL'));
        $options[] = JHtml::_('select.option', '2', JText::_('COM_FLEXIMPORT_IMPORT'));
        $options[] = JHtml::_('select.option', '3', JText::_('COM_FLEXIMPORT_EXPORT'));
        return JHtml::_('select.genericlist', $options, 'filter_usefor', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $value);
    }
    public static function typeplugin($value = null)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('COM_FLEXIMPORT_PLUGINS_SELECT'));
        $options[] = JHtml::_('select.option', 'field', JText::_('COM_FLEXIMPORT_PLUGINS_FIELD'));
        $options[] = JHtml::_('select.option', 'type', JText::_('COM_FLEXIMPORT_PLUGINS_TYPE'));
        return JHtml::_('select.genericlist', $options, 'type_plugin', 'class="inputbox" size="1" onchange="submitform();"', 'value', 'text', $value);
    }
	public static function importmethod($value = 'auto')
	{
		$options = array();
		$options[] = JHtml::_('select.option', 'auto', JText::_('COM_FLEXIMPORT_IMPORT_METHOD_AUTO'));
		$options[] = JHtml::_('select.option', 'manual', JText::_('COM_FLEXIMPORT_IMPORT_METHOD_MANUAL'));
		return JHtml::_('select.genericlist', $options, 'import_method', 'size="1" class="inputbox"', 'value', 'text', $value,'import_method');
	}
}