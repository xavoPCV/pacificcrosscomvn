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
require_once(JPATH_COMPONENT . '/classes/plugins/field.php');
class fleximportPluginMultifield extends fleximportFieldPlugin {

	public function getPostValues()
	{
		$MultifieldSeparator = $this->_fieldParams->get('multifield_separator', '=');
		$MultifieldSeparatorValue = $this->_fieldParams->get('multifield_separator_field', '||');

		$post = array($this->_field->fname => array());
		$i = 0;


		foreach ($this->_fieldValues as $fieldvalue)
		{
			$values = explode($MultifieldSeparatorValue, $fieldvalue);

			foreach ($values as $valueField)
			{
				$valueField = explode($MultifieldSeparator, $valueField);
				$post['custom'][$this->_field->fname][$i][$valueField[0]] = $valueField[1];
			}
			$i++;

		}
		return $post;
	}



	public function formatValuesExport()
	{
		$MultifieldSeparator = $this->_fieldParams->get('multifield_separator', '=');
		$MultifieldSeparatorValue = $this->_fieldParams->get('multifield_separator_field', '||');

		foreach ($this->_fieldValues as $idv => $fieldValues) {
			$valuesRaw=array();
			foreach ($fieldValues as $index => $fieldValue)
			{
				$valuesRaw[]=$index.$MultifieldSeparator.$fieldValue;
			}

			$sort_fields = array($valuesRaw[0], $valuesRaw[1], $valuesRaw[4],$valuesRaw[3], $valuesRaw[2],$valuesRaw[5]);

			for ($i = 0; $i <= count($sort_fields)-1; $i++)
			{
				if  ($sort_fields[$i]{strlen($sort_fields[$i])-1}=='=') unset($sort_fields[$i]);
			}

			$sort_fields = array_values($sort_fields);

			$this->_fieldValues[$idv] = implode($MultifieldSeparatorValue,$sort_fields);


		}
	}
}