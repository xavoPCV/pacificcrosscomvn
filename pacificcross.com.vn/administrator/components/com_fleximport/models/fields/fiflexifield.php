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

class JFormFieldFIflexifield extends JFormField {
	protected $type = 'FIflexifield';

	protected function getInput()
	{
		$fieldHTML = '';
		$db = JFactory::getDBO();
		$isSystem = in_array($this->form->getValue("field_type"),$GLOBALS['fi_fields_system']);
		if ($isSystem && $this->form->getValue("type_id")) {
			$fieldHTML = '<input type="text" value="'.$this->form->getValue("field_type").'"  readonly/>';
		}elseif($this->form->getValue("type_id")){
			$query = $db->getQuery(true);
			$query->select('f.id AS value, f.label AS text');
			$query->from('#__flexicontent_fields as f');
			$query->leftJoin('#__flexicontent_fields_type_relations as r ON f.id=r.field_id');
			$query->leftJoin('#__fleximport_types as t ON t.flexi_type_id=r.type_id');
			$query->where('f.published = 1 and f.iscore=0');
			$query->where('t.id='.(int)$this->form->getValue("type_id"));
			$query->where('f.field_type NOT IN ("'.implode('","',$GLOBALS['fi_fields_nocopy']).'")');
			$query->order('f.name');
			$db->setQuery($query);
			$result = $db->loadAssocList();
			if ($result) {
				$arField = array(array("value" => '', "text" => JText::_("COM_FLEXIMPORT_FLEXI_FIELD_SELECT")));
				$result = array_merge($arField, $result);
				$fieldHTML = JHtml::_('select.genericlist', $result, $this->name.'_select', null, 'value', 'text', $this->value, $this->id.'_select');
			}
		}else{
			$query = $db->getQuery(true);
			$query->select('f.id AS value, f.label AS text');
			$query->from('#__flexicontent_fields as f');
			$query->where('f.published = 1 and f.iscore=0');
			$query->where('f.field_type NOT IN ("'.implode('","',$GLOBALS['fi_fields_nocopy']).'")');
			$query->order('f.name');
			$db->setQuery($query);
			$result = $db->loadAssocList();
			if ($result) {
				$arField = array(array("value" => '', "text" => JText::_("COM_FLEXIMPORT_FLEXI_FIELD_SELECT")));
				$result = array_merge($arField, $result);
				$fieldHTML = JHtml::_('select.genericlist', $result, $this->name.'_select', null, 'value', 'text', $this->value, $this->id.'_select');
			}
		}

		if (JFactory::getApplication()->input->get('format')=='raw') {
			// c'est en ajax on réinitialise la valeur du champs
			if ($isSystem) {
				// on va chercher la valeur correspondante dans flexi
				$query = $db->getQuery(true);
				$query->select('id');
				$query->from('#__flexicontent_fields');
				$query->where('field_type='.$db->quote($this->form->getValue("field_type")));
				$db->setQuery($query);
				$this->value = $db->loadResult();
			}else{
				$this->value='';
			}
		}else{
			$script = "
			jQuery(document).ready(function($){
				$(document).on('change','#" . $this->id . "_select',function(){
					$('#".$this->id."').val($(this).val());
				});
			});";
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration($script);
		}

		$html ='<div id="'. $this->id.'_zone">';
		$html .= $fieldHTML;
		$html .= '<input name="'.$this->name.'" value="'. $this->value . '" type="hidden"  id="'.$this->id.'" />';
		$html.='</div>';

		return $html;
	}
}