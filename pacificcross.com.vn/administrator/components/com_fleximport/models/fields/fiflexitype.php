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

class JFormFieldFIflexitype extends JFormField {
	protected $type = 'FIflexitype';

	protected function getInput()
	{

		$db = JFactory::getDBO();
		$query = 'SELECT id AS value, name AS text'
		 . ' FROM #__flexicontent_types'
		 . ' WHERE published = 1'
		 . ' ORDER BY name ASC' ;
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$arField = array(array("value" => '', "text" => JText::_("COM_FLEXIMPORT_FLEXI_TYPE_SELECT")));
		$result = array_merge($arField, $result);
		$html = JHtml::_('select.genericlist', $result, $this->name, null, 'value', 'text', $this->value);

		return $html;
	}
}