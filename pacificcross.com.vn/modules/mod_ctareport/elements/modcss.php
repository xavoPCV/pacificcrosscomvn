<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('spacer');


class JFormFieldModcss extends JFormFieldSpacer {
	
	protected $type = 'modcss';
	
	protected function getLabel() {
		
		JHTML::_('behavior.modal', 'a.modal');
		
		$doc = JFactory::getDocument();
		
		$doc->addStyleSheet( JURI::root() . 'modules/mod_ctareport/assets/module.css');
		
		$script = 'window.addEvent(\'domready\', function() {
					$$(\'#jform_params_videos input[type=checkbox]\').addEvent( \'click\', function(e) {
						var label = this.getNext(\'label\');
						var tmp = {title: label.get(\'text\'), vidfile:$(\'vid_hdd\'+this.value).value, vidimg:$(\'vid_hdd\'+this.value).get(\'rel\')};
						$(\'jform_params_forcompliance\').set(\'value\', JSON.stringify(tmp));
						
						
						//console.log($(\'jform_params_forcompliance\').value);
						
					});
				});';
		
		$doc->addScriptDeclaration($script);
		
		
		return '';
	}

}