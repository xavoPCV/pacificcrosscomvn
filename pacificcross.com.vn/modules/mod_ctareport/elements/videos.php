<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('checkboxes');


class JFormFieldVideos extends JFormFieldCheckboxes {
	
	protected $type = 'videos';

	public function getOptions() {
	
		$opts = array();
		JLoader::import('cta', JPATH_ROOT . '/administrator/components/com_cta/models');
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		$videos = $ctaModel->getVideos();
		return $videos;
	}
	
	function getInput() {
	
		// Initialize variables.
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="checkboxes ' . (string) $this->element['class'] . '"' : ' class="checkboxes"';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		$videos = $this->getOptions();
		
		//echo '<pre>';
		//var_dump($this);

		// Build the radio field output.
		foreach ($videos as $i => $video) {

			// Initialize some option attributes.
			
			
			
			//$checked = ((string) $video['VideoId'] == (string) $this->value) ? ' checked="checked"' : '';
			
			
			$checked = in_array($video['VideoId'], $this->value)?' checked="checked"' : '';
			
			
			//$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
			//$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

			// Initialize some JavaScript option attributes.
			//$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			
			$html[] = '<input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($video['VideoId'], ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>'.chr(13);

			$view_video = JRoute::_('index.php?option=com_cta&view=report&layout=video&vidfile='.$video['VideoFile'].'&vidimg='.$video['ImgCTA']);

			$html[] = '<label for="' . $this->id . $i . '"' . $class . '><a href="'.$view_video.'" class="modal" rel="{handler: \'iframe\', size: {x:820, y:400}}">'
				. JText::alt($video['Title'], preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</a></label>'.chr(13);
				
			
			$html[] = '<input type="hidden" name="vid_hdd['.$video['VideoId'].']" id="vid_hdd'.$video['VideoId'].'" value="'.$video['VideoFile'].'" rel="'.$video['ImgCTA'].'" />'.chr(13);
			
				
				
		}//for

		// End the radio field output.
		$html[] = '</fieldset>';
		
		$html[] = '<script type="text/javascript">
window.addEvent(\'domready\', function() {
	$$(\'#jform_params_videos input[type=checkbox]\').addEvent(\'change\', function(e, target){
		
		var doCheck = this.checked;
		
		var chk = $$(\'#jform_params_videos input[type=checkbox]\');
		//chk.set(\'checked\', false);
		//if (doCheck) this.set(\'checked\', true);
	});
});
</script>';

		return implode($html);
	}
	
	
}