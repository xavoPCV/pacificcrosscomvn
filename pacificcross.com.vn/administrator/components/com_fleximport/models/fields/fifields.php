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

jimport('joomla.form.formfield');

class JFormFieldFIFields extends JFormField {
	protected $type = 'FIFields';

	protected function getInput()
	{

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id AS value, name AS text');
		$query->from('#__fleximport_fields');
		$query->order('type_id ASC, name ASC');

		$db->setQuery($query);
		$fields = $db->loadObjectList();
		$arField = array(array("value" => '', "text" => JText::_("COM_FLEXIMPORT_FLEXI_FIELD_SELECT")));
		$fields = array_merge($arField, $fields);

		return JHtml::_('select.genericlist', $fields,$this->name, null, 'value', 'text', $this->value,$this->id);
	}
}