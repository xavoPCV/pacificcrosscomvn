<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('checkboxes');


class JFormFieldCusitems extends JFormFieldCheckboxes {
	
	protected $type = 'cusitems';

	public function getOptions() {
	
		$opts = array();
		JLoader::import('cusitems', JPATH_ROOT . '/administrator/components/com_cta/models');
		
		$ctaModel = JModelLegacy::getInstance('Cusitems', 'CtaModel', array('ignore_request' => true));
		
		$options = $ctaModel->getItems();
		return $options;
	}
	
	function getInput() {
	
		// Initialize variables.
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="checkboxes ' . (string) $this->element['class'] . '"' : ' class="checkboxes"';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		$options = $this->getOptions();
		
		//echo '<pre>';
		//var_dump($this->value);
		
		//print_r($options);
		

		// Build the radio field output.
		foreach ($options as $i => $option) {
			$checked = in_array($option->id, $this->value)?' checked="checked"' : '';
			$html[] = '<input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($option->id, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>'.chr(13);
			$html[] = '<label for="' . $this->id . $i . '"' . $class . '>'.$option->title. '</label>'.chr(13);
		}//for

		// End the radio field output.
		$html[] = '</fieldset>';
		
		
		$html[] = '<script type="text/javascript">
window.addEvent(\'domready\', function() {
	$$(\'#jform_params_cusitems input[type=checkbox]\').addEvent(\'change\', function(e, target){
		var doCheck = this.checked;
		var chk = $$(\'#jform_params_cusitems input[type=checkbox]\');
		//chk.set(\'checked\', false);
		//if (doCheck) this.set(\'checked\', true);
	});
});
</script>';
		

		return implode($html);
	}
	
	
}