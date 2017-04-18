<?php
/**
* @version 1.5 stable $Id: types.php 1340 2012-06-06 02:30:49Z ggppdk $
* @package Joomla
* @subpackage FLEXIcontent
* @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
* @license GNU/GPL v2
*
* FLEXIcontent is a derivative work of the excellent QuickFAQ component
* @copyright (C) 2008 Christoph Lukes
* see www.schlu.net for more information
*
* FLEXIcontent is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
* Renders a multiple select element
*
*/

class JFormFieldMultiList extends JFormField
{
	/**
	* Element name
	*
	* @access       protected
	* @var          string
	*/
	var	$type = 'MultiList';

	function getInput()
	{
		$node = & $this->element;
		$attributes = get_object_vars($node->attributes());
		$attributes = $attributes['@attributes'];
		
		$values = $this->value;
		if ( ! is_array($values) )		$values = explode("|", $values);
		
		$fieldname	= $this->name;
		$element_id = $this->id;
		
		$name = $attributes['name'];
		$control_name = str_replace($name, '', $element_id);
		
		//$attribs = ' style="float:left;" ';
		$attribs = array(
	    'id' => $element_id, // HTML id for select field
	    'list.attr' => array( // additional HTML attributes for select field
	    ),
	    'list.translate'=>false, // true to translate
	    'option.key'=>'value', // key name for value in data array
	    'option.text'=>'text', // key name for text in data array
	    'option.attr'=>'attr', // key name for attr in data array
	    'list.select'=>$values, // value of the SELECTED field
		);
		
		if (@$attributes['multiple']=='multiple' || @$attributes['multiple']=='true' ) {
			$attribs['list.attr']['multiple'] = 'multiple';
			$attribs['list.attr']['size'] = @$attributes['size'] ? $attributes['size'] : "6";
		}
		
		
		// HTML Tag parameters
		if ($onchange = @$attributes['onchange']) {
			$onchange = str_replace('{control_name}', $control_name, $onchange);
			$attribs['list.attr']['onchange'] = $onchange;
		}
		
		$subtype = @$attributes['subtype'];

		$attribs['list.attr']['class'] = array();
		if ($subtype=='radio') {
			$attribs['list.attr']['class'][] = 'radio';
		}

		if ($class = @$attributes['class']) {
			$attribs['list.attr']['class'][] = $class;
		}
		
		if (@$attributes['fccustom_revert']) {
			$attribs['list.attr']['class'][] = 'fccustom_revert';
		}
		
		if (@$attributes['toggle_related']) {
			$attribs['list.attr']['class'][] = 'fcform_toggler_element';
		}
		$attribs['list.attr']['class'] = implode($attribs['list.attr']['class'], ' ');
		
		// Construct an array of the HTML OPTION statements.
		$options = array ();
		foreach ($node->children() as $option)
		{
			$name = FLEXI_J30GE ? $option->getName() : $option->name();
			//echo "<pre>"; print_r($option); echo "</pre>"; exit;
			
			// Check for current option is a GROUP and add its START
			if ($name=="group")  $options[] = JHTML::_('select.optgroup', JText::_( $option->attributes()->label ) );
			
			// If current option is group then iterrate through its children, otherwise create single value array
			$children = $name=="group" ? $option->children() : array( & $option );
			
			foreach ($children as $sub_option)
			{
				$attr_arr = array();
				if (isset($sub_option->attributes()->seton_list))  $attr_arr['seton_list']  = $sub_option->attributes()->seton_list;
				if (isset($sub_option->attributes()->setoff_list)) $attr_arr['setoff_list'] = $sub_option->attributes()->setoff_list;
				if (isset($sub_option->attributes()->refsh_list))  $attr_arr['refsh_list']  = $sub_option->attributes()->refsh_list;
				if (isset($sub_option->attributes()->force_list))  $attr_arr['force_list']  = $sub_option->attributes()->force_list;
				if (isset($sub_option->attributes()->show_list))   $attr_arr['show_list']   = $sub_option->attributes()->show_list;
				if (isset($sub_option->attributes()->hide_list))   $attr_arr['hide_list']   = $sub_option->attributes()->hide_list;
				if (isset($sub_option->attributes()->fcconfigs))   $attr_arr['fcconfigs']   = $sub_option->attributes()->fcconfigs;
				if (isset($sub_option->attributes()->fcreadonly))  $attr_arr['fcreadonly']  = $sub_option->attributes()->fcreadonly;
				
				if (isset($sub_option->attributes()->class))  $attr_arr['class'] = $sub_option->attributes()->class;
				
				$val  = $sub_option->attributes()->value;
				$text = FLEXI_J30GE ? $sub_option->__toString() : $sub_option->data();
				//$options[] = JHTML::_('select.option', $val, JText::_($text));
				$options[] = array(
					'value' => $val,
					'text'  => JText::_($text),
					'attr'  => $attr_arr
				);
			}
			
			// Check for current option is a GROUP and add its END
			if ($name=="group") $options[] = JHTML::_('select.optgroup', '' );
		}
		
		/* support for parameter multi-value, multi-parameter dependencies in non-FLEXIcontent views */
		static $js_added = false;
		if (!$js_added) {
			$js_added = true;
			$doc = JFactory::getDocument();
			$doc->addScript(JURI::root(true).'/components/com_flexicontent/assets/js/flexi-lib.js');
			if ( JRequest::getCmd('option')!='com_flexicontent' ) {
				$js = "
				jQuery(document).ready(function(){
					".(FLEXI_J30GE ?
						"fc_bind_form_togglers('body', 2, '.control-group');" :
						"fc_bind_form_togglers('body', 1, 'li');"
					)."
				});
				";
				$doc->addScriptDeclaration($js);
			}
		}
		
		if ($subtype=='radio') {
			$_class = ' class ="'.$attribs['list.attr']['class'].'"';
			$_id = ' id="'.$element_id.'"';
			$html = '';
			foreach($options as $i => $option) {
				$selected = count($values) && $values[0]==$option['value'] ? ' checked="checked"' : '';
				$input_attribs = '';
				$label_class = '';
				foreach ($option['attr'] as $k => $v) {
					if ($k=='class') { $label_class = $v; continue; }
					$input_attribs .= ' ' .$k. '="' .$v. '"';
				}
				$html .= '
					<input id="'.$element_id.$i.'" type="radio" value="'.$option['value'].'" name="'.$fieldname.'" '. $input_attribs . $selected.'/>
					<label class="'.$label_class.'" for="'.$element_id.$i.'" value="'.$option['text'].'">
						'.$option['text'].'
					</label>';
			}
			$html = '
				<fieldset '.$_class.$_id.'>
				'.$html.'
				</fieldset>
				';
		}
		else {
			$html = JHTML::_('select.genericlist', $options, $fieldname, $attribs);
		}
		if ($inline_tip = @$attributes['inline_tip'])
		{
			$tip_img = @$attributes['tip_img'];
			$tip_img = $tip_img ? $tip_img : 'comment.png';
			$preview_img = @$attributes['preview_img'];
			$preview_img = $preview_img ? $preview_img : '';
			$tip_class = @$attributes['tip_class'];
			$tip_class .= FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
			$hintmage = JHTML::image ( 'administrator/components/com_flexicontent/assets/images/'.$tip_img, JText::_( 'FLEXI_NOTES' ), ' align="left" style="max-height:24px; padding:0px; margin-left:12px; margin-right:0px;" ' );
			$previewimage = $preview_img ? JHTML::image ( 'administrator/components/com_flexicontent/assets/images/'.$preview_img, JText::_( 'FLEXI_NOTES' ), ' align="left" style="max-height:24px; padding:0px; margin:0px;" ' ) : '';
			$tip_text = '<span class="'.$tip_class.'" style="float:left;" title="'.flexicontent_html::getToolTip(null, $inline_tip, 1, 1).'">'.$hintmage.$previewimage.'</span>';
		}
		if ($inline_tip = @$attributes['inline_tip2'])
		{
			$tip_img = @$attributes['tip_img2'];
			$tip_img = $tip_img ? $tip_img : 'comment.png';
			$preview_img = @$attributes['preview_img2'];
			$preview_img = $preview_img ? $preview_img : '';
			$tip_class = @$attributes['tip_class2'];
			$tip_class .= FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
			$hintmage = JHTML::image ( 'administrator/components/com_flexicontent/assets/images/'.$tip_img, JText::_( 'FLEXI_NOTES' ), ' align="left" style="max-height:24px; padding:0px; margin-left:12px; margin-right:0px;" ' );
			$previewimage = $preview_img ? JHTML::image ( 'administrator/components/com_flexicontent/assets/images/'.$preview_img, JText::_( 'FLEXI_NOTES' ), ' align="left" style="max-height:24px; padding:0px; margin:0px;" ' ) : '';
			$tip_text2 = '<span class="'.$tip_class.'" style="float:left;" title="'.flexicontent_html::getToolTip(null, $inline_tip, 1, 1).'">'.$hintmage.$previewimage.'</span>';
		}
		return $html.@$tip_text.@$tip_text2;
	}
}