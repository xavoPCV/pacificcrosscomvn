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
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldFIcategories extends JFormFieldList{
	protected $type = 'ficategories';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput() {
		// Initialize variables.
		$html = array();
		$attr = '';

		if(!is_array($this->value)) $this->value = array($this->value);
		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		$html[] = '<select name="'.$this->name.'" '.trim($attr).'>';
		foreach($options as $opt) {
			$disabled = '';
			$selected = '';
			if( @$opt->disable )
				$disabled = ' disabled="disabled"';
			if(in_array($opt->value, $this->value))
				$selected = ' selected="selected"';
			$html[] = '<option value="'.$opt->value.'"'.$disabled.$selected.'>'.$opt->text.'</option>';
		}
		$html[] = '</select>';

		return implode("\n", $html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions() {
		global $globalcats;

		$catlist 	= array();

		foreach ($globalcats as $item) {
				$obj = new stdClass;
				$obj->value = $item->id;
				$obj->text = $item->treename;
				$obj->level = $item->level;
				$catlist[] = $obj;
		}
		// Merge any additional options in the XML definition.
		$catlist = array_merge(parent::getOptions(), $catlist);
		return $catlist;
	}
}
