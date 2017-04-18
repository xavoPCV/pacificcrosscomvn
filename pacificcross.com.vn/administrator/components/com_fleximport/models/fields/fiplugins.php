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
jimport('joomla.filesystem.folder');

class JFormFieldFIPlugins extends JFormField {
    protected $type = 'FIplugins';

    protected function getInput()
    {
        $folder = $this->element['folder'];
        if (!in_array($folder, array('types', 'fields'))) return false;
        $pathPlugins = JPATH_ADMINISTRATOR . '/components/com_fleximport/classes/plugins/' . $folder . '/';

        $plugins = JFolder::files($pathPlugins, '.xml');

        $options = array();
        $optionsSys = array();
        foreach ($plugins as $plugin) {
            if (JFile::getExt(JPATH_ADMINISTRATOR . '/components/com_fleximport/classes/plugins/' . $folder . '/' . $plugin) == 'xml') {
                $plugin = str_replace('.xml', '', $plugin);
                if ($folder == 'fields') {
                    if (in_array($plugin, $GLOBALS['fi_fields_system'])) {
                        $optionsSys[] = JHtml::_('select.option' , $plugin);
                    } else {
                        $options[] = JHtml::_('select.option' , $plugin);
                    }
                } else {
                    $options[] = JHtml::_('select.option' , $plugin);
                }
            }
        }
        if ($folder == 'fields') {
            array_unshift ($optionsSys, JHtml::_('select.optgroup' , JText::_('COM_FLEXIMPORT_FIELD_CORE')));
        	$optionsSys[]=JHtml::_('select.optgroup' , JText::_('COM_FLEXIMPORT_FIELD_CORE'));
            array_unshift ($options, JHtml::_('select.optgroup' , JText::_('COM_FLEXIMPORT_FIELD_USER')));
        	$options[]=JHtml::_('select.optgroup' , JText::_('COM_FLEXIMPORT_FIELD_USER'));
            $options = array_merge($optionsSys, $options);
        }
        $html = JHtml::_('select.genericlist', $options, $this->name, array('class'=>'required'), 'value', 'text', $this->value, $this->id);
        $script = "
				jQuery.noConflict();
				function loadAjaxToolTips(){
					$$('.hasTip').each(function(el) {
						var title = el.get('title');
						if (title) {
							var parts = title.split('::', 2);
							el.store('tip:title', parts[0]);
							el.store('tip:text', parts[1]);
						}
					});
					$$('.modal,.modal_jform_params_created_by_default').each(function(el) {
						SqueezeBox.fromElement(el);
					});
					var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
				}
				jQuery(document).ready(function($){
					$(document).on('change','#" . $this->id . "',function(){
						$('#fiplugins + div.pane-slider').html('<p class=\"ajaxload\"><img src=\"" . JUri::root(true) . "/media/com_fleximport/images/ajax-loader.gif\"></p>');
						$.ajax({
							type:'POST',
							url:'index.php',
							data: {
								ajax: 'params',
								option: 'com_fleximport',
								view: 'ajax',
								format: 'raw',
								folder: '" . $folder . "',
								cid: '" . $this->form->getValue("id") . "',
								plugin: $(this).val()
								},
							dataType: 'html'
						}).done(function(html){
							$('#fiplugins  + div.pane-slider').closest('div.pane-slider').html(html);
							loadAjaxToolTips();
						});";
    	if ($folder == 'fields') {
    		$script .= "
        			$('#jform_flexi_field_id_zone').html('<p class=\"ajaxload\"><img src=\"" . JUri::root(true) . "/media/com_fleximport/images/ajax-loader-small.gif\"></p>');
        			$.ajax({
        				type:'POST',
        				url:'index.php',
        				data: {
        					ajax: 'flexifields',
        					option: 'com_fleximport',
        					view: 'ajax',
        					format: 'raw',
        					cid: '" . $this->form->getValue("id") . "',
        					type: $('#jformtype_id').val(),
        					plugin: $(this).val()
        				},
        				dataType: 'html'
        			}).done(function(html){
        				$('#jform_flexi_field_id_zone').html(html);
        			});
		";
    	}
    	$script .= "});});";
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($script);

        return $html;
    }
}