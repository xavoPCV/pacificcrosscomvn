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

class JFormFieldFItype extends JFormField {
	protected $type = 'FItype';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id AS value, name AS text')->from(' #__fleximport_types')->where('published = 1')->order('name ASC');
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$arField = array(array("value" => '', "text" => JText::_("COM_FLEXIMPORT_FLEXI_TYPE_SELECT")));
		$result = array_merge($arField, $result);
		$html = JHtml::_('select.genericlist', $result, $this->name,  array('class'=>'required'), 'value', 'text', $this->value, $this->id);

		$script = "
			jQuery(document).ready(function($){
				$(document).on('change','#" . $this->id . "',function(){
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
        					type: $(this).val(),
        					plugin: $('#jform_field_type').val()
        				},
        				dataType: 'html'
        			}).done(function(html){
        				$('#jform_flexi_field_id_zone').html(html);
        			});
       			});
      		});
		";
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($script);
		return $html;
	}
}