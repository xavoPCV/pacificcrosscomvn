<?php
/**
 * @version 0.6.0 stable $Id: default.php yannick berges
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2015 Berges Yannick - www.com3elles.com
 * @license GNU/GPL v2
 
 * special thanks to ggppdk and emmanuel dannan for flexicontent
 * special thanks to my master Marc Studer
 
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 **/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldMicrodatatype extends JFormField {

	protected $type = 'microdatatype';

	// getLabel() left out

	public function getInput()
	{
		$node = & $this->element;
		$attributes = get_object_vars($node->attributes());
		$attributes = $attributes['@attributes'];
		
		$values = array(
			'NewsArticle' => 'NewsArticle',
			'Person' => 'Person',
			'Product' => 'Product',
			'Event' => 'Event',
			'Recipe' => 'Recipe',
			'Organization' => 'Organization',
			'Movie' => 'Movie',
			'Book' => 'Book',
			'Review' => 'Review',
			'SoftwareApplication' => 'SoftwareApplication'
		);
		
		$first_option = @$attributes['first_option'];
		
		## Initialize array to store dropdown options ##
		$options = array();
		$options[] = JHTML::_('select.option','', '-- '.JText::_($first_option ? $first_option : 'FLEXI_USE_GLOBAL').' --');

		foreach($values as $key=>$value) :
		## Create $value ##
		$options[] = JHTML::_('select.option', $key, $value);
		endforeach;

		## Create <select name="icons" class="inputbox"></select> ##
		$dropdown = JHTML::_('select.genericlist', $options, $this->name, 'class="use_select2_lib inputbox"', 'value', 'text', $this->value, $this->id);

		## Output created <select> list ##
		return $dropdown;
	}
}