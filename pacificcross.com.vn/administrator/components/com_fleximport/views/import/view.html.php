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

/**
 * View class for the flexIMPORT field screen
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportViewImport extends JViewLegacy {
	public $form = array();
    function display($tpl = null)
    {
        // initialise variables
        $document = JFactory::getDocument();

        // build selectlists
		$this->form['import_method'] = JHtml::_('fifilters.importmethod','auto');
		$this->form['type_id'] = JHtml::_('fifilters.type',null,'size="1" class="required"');

    	$scriptReturn="
		if (json.html){
			$('#import_return fieldset').append(json.html);
		}
		".(FLEXIMPORT_DEBUG?"
		if (json.errors){
			$('#import_errors fieldset').append(json.errors);
		}":"")."
		if (json.reload == true){
			importAjax();
		} else {
			$('#import_progress').html('');
			var redirect = 'window.location.replace(\"" . JRoute::_('index.php?option=com_fleximport&view=logs',false)."\");';
			".(FLEXIMPORT_DEBUG?"":"setTimeout(redirect,3000);")."
		}";
    	$script="jQuery.noConflict();
		jQuery(document).ready(function($){
			$(document).on('change','#import_method',function(){
				if($(this).val()=='auto'){
					$('#file_zone').html('');
				}else{
					$('#file_zone').html('<label for=\"import_file\">".JText::_('COM_FLEXIMPORT_IMPORT_FILE',true)."</label><input type=\"file\" name=\"import_file\" id=\"import_file\" />');
				}
			});
			function importAjax(){
				$.ajax({
					type:'POST',
					url:'index.php',
					data: {
						option: 'com_fleximport',
						task: 'import.ajax',
						format: 'raw'
						},
					dataType: 'json'
				}).done(function(json){
					".$scriptReturn."
				});
			}
			Joomla.submitbutton = function(pressbutton){
				if ((pressbutton=='import' && $('#filter_type').val() && $('#import_method').val()=='auto')||(pressbutton=='import' && $('#filter_type').val() && $('#import_method').val()=='manual') && $('#import_file').val()){
					if ($('#import_method').val()=='manual'){
						$('form').ajaxForm({
							type:'POST',
							url:'index.php',
							data: {
								option: 'com_fleximport',
								task: 'import.start',
								format: 'raw',
								type_id:$('#filter_type').val(),
								import_method:$('#import_method').val()
								},
							dataType: 'json',
							success:function(json){
							".$scriptReturn."
							}
						}).submit();
					}else{
						$.ajax({
							type:'POST',
							url:'index.php',
							data: {
								option: 'com_fleximport',
								task: 'import.start',
								format: 'raw',
								type_id:$('#filter_type').val(),
								import_method:$('#import_method').val()
								},
							dataType: 'json'
						}).done(function(json){
							".$scriptReturn."
						});
					}
					$('#toolbar,#adminForm').hide();
					$('#import_progress,#import_return".(FLEXIMPORT_DEBUG?",#import_errors":"")."').show();
					$('#import_progress').html('<p class=\"ajaxload\"><img src=\"" . JUri::root(true) . "/media/com_fleximport/images/ajax-loader.gif\"></p>');
				}
			}
		});
		";

        $document->addScript(JUri::root(true).'/media/com_fleximport/js/jquery.form.min.js');
        $document->addScriptDeclaration($script);

		$this->addToolbar();

        parent::display($tpl);
    }

	protected function addToolbar()
	{
		$user = JFactory::getUser();
        JToolBarHelper::title(JText::_('COM_FLEXIMPORT_IMPORT'), 'import');
		if ($user->authorise('fleximport.import', 'com_fleximport')) {
			JToolBarHelper::custom('import', 'import.png', 'import.png', 'COM_FLEXIMPORT_IMPORT_ACTION', false);
		}

		if ($user->authorise('core.admin', 'com_fleximport')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_fleximport');
		}
	}
}