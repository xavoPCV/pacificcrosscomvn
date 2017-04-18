<?php
/**
 * @version 1.0 $Id: linkslist.php 1687 2013-06-19 02:00:34Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @subpackage plugin.list
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.event.plugin');

class plgFlexicontent_fieldsLinkslist extends JPlugin
{
	/**
	 * Default attributes
	 *
	 * @var array
	 */
	protected $_attribs = array();
	static $field_types = array('linkslist');

	// ***********
	// CONSTRUCTOR
	// ***********
	
	function plgFlexicontent_fieldsLinkslist( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		JPlugin::loadLanguage('plg_flexicontent_fields_linkslist', JPATH_ADMINISTRATOR);
	}
	
	
	
	// *******************************************
	// DISPLAY methods, item form & frontend views
	// *******************************************
	
	// Method to create field's HTML display for item form
	function onDisplayField(&$field, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		
		$field->label = JText::_($field->label);
		
		// some parameter shortcuts
		$field_elements	= $field->parameters->get( 'field_elements' ) ;
		$default_values	= $field->parameters->get( 'default_values', '' ) ;
		
		// Prefix - Suffix - Separator parameters, replacing other field values if found
		$pretext			= $field->parameters->get( 'pretext_form', '' ) ;
		$posttext			= $field->parameters->get( 'posttext_form', '' ) ;
		$separator		= $field->parameters->get( 'separator', 0 ) ;
		$opentag			= $field->parameters->get( 'opentag_form', '' ) ;
		$closetag			= $field->parameters->get( 'closetag_form', '' ) ;
		
		$required = $field->parameters->get( 'required', 0 ) ;
		$required = $required ? ' required' : '';
		
		switch($separator)
		{
			case 0:
			$separator = '&nbsp;';
			break;

			case 1:
			$separator = '<br />';
			break;

			case 2:
			$separator = '&nbsp;|&nbsp;';
			break;

			case 3:
			$separator = ',&nbsp;';
			break;

			case 4:
			$separator = $closetag . $opentag;
			break;

			default:
			$separator = '&nbsp;';
			break;
		}

		// initialise property
		if($item->version == 0 && $default_values) {
			$field->value = explode(",", $default_values);
		} else if (!$field->value) {
			$field->value = array();
			$field->value[0] = '';
		}
		
		if(strlen($field_elements) === 0) return $field->html = '<div id="fc-change-error" class="fc-error">Please enter at least one item. Example: <pre style="display:inline-block; margin:0">{"item1":{"name":"Item1"},"item2":{"name":"Item2"}}</pre></div>';
		
		$elements = $this->parseElements($field, $field_elements);
		
		$fieldname = 'custom['.$field->name.'][]';
		$elementid = 'custom_'.$field->name;
		
		$options = array();
		
		// Render as mult-select form field
		if ( $field->parameters->get( 'editform_field_type', 1 ) == 2 )
		{
			foreach ($elements as $li_title => $li_params) {
				$options[] = JHTML::_('select.option', $li_title, $li_title);
			}
			$field->html	= JHTML::_('select.genericlist', $options, $fieldname, 'class="use_select2_lib'.$required.'" multiple="multiple"', 'value', 'text', $field->value, $elementid);
		}
		
		// Render as checkboxes
		else
		{
			$n = 0;
			foreach ($elements as $li_title => $li_title)
			{
				$checked  = in_array($li_title, $field->value) ? ' checked="checked"' : null;
				$options[] =
					'<input type="checkbox" class="'.$required.'" name="'.$fieldname.'" value="'.$li_title.'" id="'.$elementid.'_'.$n.'"'.$checked.' />'.
					'<label for="'.$elementid.'_'.$n.'">'.$li_title.'</label>';
				$n++;
			}
			
			// Apply values separator
			$field->html = implode($separator, $options);
			
			// Apply field 's opening / closing texts
			if ($field->html)
				$field->html = $opentag . $field->html . $closetag;
		}
		
	}
	
	
	// Method to create field's HTML display for frontend views
	function onDisplayFieldValue(&$field, $item, $values=null, $prop='display')
	{
		$field->label = JText::_($field->label);
		if ( !in_array($field->field_type, self::$field_types) ) return;
		
		$values = $values ? $values : $field->value;
		
		$add_non_selected = $field->parameters->get( 'add_non_selected', 0 ) ;
		// Parse list elements, and create HTML of list elements
		$field_elements = $field->parameters->get( 'field_elements', '' ) ;
		$elements = $this->parseElements($field, $field_elements);
		
		
		// Get list type and its list TAG parameters
		$list_type  = $field->parameters->get( 'list_type', 'ul' ) ;
		$list_class = $field->parameters->get( 'list_class', '' ) ;
		$list_id    = $field->parameters->get( 'list_id', '' ) ;
		
		$list_params = '';
		if ($list_class) $list_params .= ' class="'.$list_class.'"' ;
		if ($list_id)    $list_params .= ' id="'.$list_id.'"' ;
		
		
		// Create HTML of list elements
		$options = array();
		foreach($elements as $li_title => $li_params)
		{
			$is_selected = in_array($li_title, $values);
			if ( !$add_non_selected && !$is_selected ) continue;
			
			$prefix = $suffix = '';
			if ($is_selected)
			{
				if (isset($li_params['link'])) {
					$prefix = '<a href="'.$li_params['link'].'">';
					$suffix = '</a>';
				} else {
					$prefix = '<span class="fc_linklist_text_only" >';
					$suffix = '</span>';
				}
			}
			else {
				$prefix = '<span class="fc_linklist_non_selected" >';
				$suffix = '</span>';
			}
			unset($li_params['link']);
			
			array_walk($li_params, array($this, 'walk'), $li_title);
			$li_params = $li_params ? ' '.implode(' ', $li_params) : null;
			$options[] = '<li'.$li_params.'>'.$prefix.$li_title.$suffix.'</li>';
		}
		
		static $js_code_added = null;
		if ( $js_code_added===null ) {
			$js_code = $field->parameters->get( 'js_code', '' ) ;
			if ($js_code)  JFactory::getDocument()->addScriptDeclaration($js_code);
			$js_code_added = true;
		}
		static $css_code_added = null;
		if ( $css_code_added===null ) {
			$css_code = $field->parameters->get( 'css_code', '' ) ;
			if ($css_code) JFactory::getDocument()->addStyleDeclaration($css_code);
			$css_code_added = true;
		}
		
		// Create the HTML of the list
		if (!count($options)) return $field->{$prop} = '';
		return $field->{$prop} =
			'<'.$list_type . $list_params.'>'.
				implode($options).
			'</'.$list_type.'>';
	}
	
	
	
	// **************************************************************
	// METHODS HANDLING before & after saving / deleting field events
	// **************************************************************
	
	// Method to handle field's values before they are saved into the DB
	function onBeforeSaveField( &$field, &$post, &$file, &$item )
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		if(!is_array($post) && !strlen($post)) return;
		
		// create the fulltext search index
		if ($field->issearch) {
			$searchindex = '';
			
			$field_elements = $field->parameters->get( 'field_elements', '' ) ;
			$elements = $this->parseElements($field, $field_elements);
			
			$searchindex  = implode(' ', array_keys($elements));
			$searchindex .= ' | ';
	
			$field->search = $searchindex;
		} else {
			$field->search = '';
		}
	}
	
	
	// Method to take any actions/cleanups needed after field's values are saved into the DB
	function onAfterSaveField( &$field, &$post, &$file, &$item ) {
	}
	
	
	// Method called just before the item is deleted to remove custom item data related to the field
	function onBeforeDeleteField(&$field, &$item) {
	}
	
	
	
	// *********************************
	// CATEGORY/SEARCH FILTERING METHODS
	// *********************************
	
	// Method to display a search filter for the advanced search view
	/*function onAdvSearchDisplayFilter(&$filter, $value='', $formName='searchForm')
	{
		if ( !in_array($filter->field_type, self::$field_types) ) return;
		
		$size = (int)$filter->parameters->get( 'size', 30 );
		$filter->html	='<input name="filter_'.$filter->id.'" class="fc_field_filter" type="text" size="'.$size.'" value="'.$value.'" />';
	}*/
	
	
	// Method to display a category filter for the category view
	function onDisplayFilter(&$filter, $value='', $formName='adminForm')
	{
		if ( !in_array($filter->field_type, self::$field_types) ) return;

		// some parameter shortcuts
		$field_elements = $filter->parameters->get( 'field_elements' ) ;
		$elements = $this->parseElements($filter, $field_elements);
		
		$options = array(); 
		$options[] = JHTML::_('select.option', '', '-'.JText::_('FLEXI_ALL').'-');
		foreach ($elements as $val => $title) {
			$options[] = JHTML::_('select.option', $val, $title); 
		}
		
		$filter->html	= JHTML::_('select.genericlist', $options, 'filter_'.$filter->id, ' class="fc_field_filter" onchange="document.getElementById(\''.$formName.'\').submit();"', 'value', 'text', $value);
	}
	
	
	
	// **********************
	// VARIOUS HELPER METHODS
	// **********************
	
	private function parseElements(&$field, &$field_elements)
	{
		static $elements_arr = array();
		if (isset($elements_arr[$field->id])) return $elements_arr[$field->id];
		
		$listelements = array_map('trim', preg_split('/\s*::\s*/', $field_elements));
		$elements = $matches = array();
		foreach($listelements as $listelement)
		{
			preg_match("/\[(.*)\]/i", $listelement, $matches);
			$name = trim(preg_replace("/\s*\[(.*)\]\s*/i", '', $listelement));
			if(isset($matches[1]))
			{
				$attribs	  = array();
				$parts  	  = explode('"', str_replace('="', '"', $matches[1]));
				$length		  = count($parts);
				$range		  = range(0, $length, 2);
				foreach($range as $i)
				{
					if(!isset($parts[$i+1])) continue;
					$attribs[trim($parts[$i])] = $parts[$i+1];
				}
				$elements[$name] = array_merge($this->_attribs, $attribs);
			}
			else
			{
				$elements[$name] = $this->_attribs;
			}
		}
		
		$elements_arr[$field->id] = $elements;
		return $elements;
	}	
	
	
	function walk(&$value, $key)
	{
		if($key == 'href') $value = false;
		$value = $key.'="'.$value.'"';
	}
	
}
