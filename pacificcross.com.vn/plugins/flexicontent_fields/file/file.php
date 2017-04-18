<?php
/**
 * @version 1.0 $Id: file.php 1959 2014-09-18 00:15:15Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @subpackage plugin.file
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

//jimport('joomla.plugin.plugin');
jimport('joomla.event.plugin');
JLoader::register('FCField', JPATH_ADMINISTRATOR . '/components/com_flexicontent/helpers/fcfield/parentfield.php');

class plgFlexicontent_fieldsFile extends FCField
{
	static $field_types = array('file');
	var $task_callable = array('share_file_form', 'share_file_email');
	
	// ***********
	// CONSTRUCTOR
	// ***********
	
	function plgFlexicontent_fieldsFile( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		JPlugin::loadLanguage('plg_flexicontent_fields_file', JPATH_ADMINISTRATOR);
	}
	
	
	
	// *******************************************
	// DISPLAY methods, item form & frontend views
	// *******************************************
	
	// Method to create field's HTML display for item form
	function onDisplayField(&$field, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		
		$field->label = JText::_($field->label);
		$use_ingroup = 0;  // Not supported  //$field->parameters->get('use_ingroup', 0);
		if ($use_ingroup) $field->formhidden = 3;
		if ($use_ingroup && empty($field->ingroup)) return;
		
		// initialize framework objects and other variables
		$document = JFactory::getDocument();
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();
		$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
		
		
		// ****************
		// Number of values
		// ****************
		$multiple   = $use_ingroup || $field->parameters->get( 'allow_multiple', 1 ) ;
		$max_values = $use_ingroup ? 0 : (int) $field->parameters->get( 'max_values', 0 ) ;
		$required   = (int)$field->parameters->get( 'required', 0 ) ;
		$required_class = $required ? ' required' : '';
		
		// Input field configuration
		$inputmode = (int)$field->parameters->get( 'inputmode', 1 ) ;
		$top_notice = ($inputmode==0 && $multiple) ?'<div class="alert alert-warning">Multi-value mode is not implenent for inline mode in current version, please disable</div>' : '';
		$multiple  = !$inputmode ? 0 : $multiple;
		if ($inputmode==0) {
			$iform_title = $field->parameters->get('iform_title', 1);
			$iform_desc  = $field->parameters->get('iform_desc',  1);
			$iform_lang  = $field->parameters->get('iform_lang',  0);
			$iform_dir   = $field->parameters->get('iform_dir',   0);
		}
		
		// Get a unique id to use as item id if current item is new
		$u_item_id = $item->id ? $item->id : JRequest::getVar( 'unique_tmp_itemid' );
		
		// Load file data
		if ( !$field->value ) {
			// Field value empty
			$files_data = array();
			$form_data = array();
			$field->value = array();
		}
		else {
			$file_ids  = array();
			$form_data = array();
			
			// Check if reloading user data after form validation error
			$v = reset($field->value);
			if (is_array($v) && isset($v['file-id']))
			{
				foreach($field->value as $v) {
					$file_ids[] = $v['file-id'];
					$form_data[$v['file-id']] = $v;
				}
			} else {
				$file_ids = $field->value;
			}
			
			// Get data for given file ids
			$files_data = $this->getFileData( $file_ids, $published=false );
			
			$field->value = array();
			foreach($files_data as $file_id => $file_data) {
				$field->value[] = $file_id;
			}
		}
		
		// Inline mode needs an default value
		$has_values = count($field->value);
		if ($inputmode==0 && empty($field->value))
		{
			// Create fake value, to allow the inline form fields to work
			$field->value = array(0=>0);
			$files_data = array(0 => (object)array(
				'id'=>'', 'filename'=>'', 'filename_original'=>'', 'altname'=>'', 'description'=>'',
				'url'=>'',
				'secure'=>$field->parameters->get('iform_dir_default', '1'),
				'ext'=>'', 'published'=>1,
				'language'=>$field->parameters->get('iform_lang_default', '*'),
				'hits'=>0,
				'uploaded'=>'', 'uploaded_by'=>0, 'checked_out'=>false, 'checked_out_time'=>'', 'access'=>0,
			) );
		}
		
		// CSS classes of value container
		$value_classes  = 'fcfieldval_container valuebox fcfieldval_container_'.$field->id;
		
		// Field name and HTML TAG id
		if ($inputmode)
		{
			$fieldname = 'custom['.$field->name.'][]';
			$elementid = 'custom_'.$field->name;
		} else {
			$fieldname = 'custom['.$field->name.']';
			$elementid = 'custom_'.$field->name;
		}
		
		$js = "";
		$css = "";
		
		if ($multiple && $inputmode==1) // handle multiple records
		{
			// Add the drag and drop sorting feature
			if (!$use_ingroup) $js .= "
			jQuery(document).ready(function(){
				jQuery('#sortables_".$field->id."').sortable({
					handle: '.fcfield-drag-handle',
					containment: 'parent',
					tolerance: 'pointer'
				});
			});
			";
			
			if ($max_values) JText::script("FLEXI_FIELD_MAX_ALLOWED_VALUES_REACHED", true);
			$js .= "
			var uniqueRowNum".$field->id."	= ".count($field->value).";  // Unique row number incremented only
			var rowCount".$field->id."	= ".count($field->value).";      // Counts existing rows to be able to limit a max number of values
			var maxValues".$field->id." = ".$max_values.";
			
			function qfSelectFile".$field->id."(obj, id, file)
			{
				var insert_before   = (typeof params!== 'undefined' && typeof params.insert_before   !== 'undefined') ? params.insert_before   : 0;
				var remove_previous = (typeof params!== 'undefined' && typeof params.remove_previous !== 'undefined') ? params.remove_previous : 0;
				var scroll_visible  = (typeof params!== 'undefined' && typeof params.scroll_visible  !== 'undefined') ? params.scroll_visible  : 1;
				var animate_visible = (typeof params!== 'undefined' && typeof params.animate_visible !== 'undefined') ? params.animate_visible : 1;
				
				if((rowCount".$field->id." >= maxValues".$field->id.") && (maxValues".$field->id." != 0)) {
					alert(Joomla.JText._('FLEXI_FIELD_MAX_ALLOWED_VALUES_REACHED') + maxValues".$field->id.");
					return 'cancel';
				}
				
				if (1)
				{
					// A non-empty container is being removed ... get counter (which is optionally used as 'required' form element and empty it if is 1, or decrement if 2 or more)
					var valcounter = document.getElementById('".$field->name."');
					if ( typeof valcounter.value === 'undefined' || valcounter.value=='' ) valcounter.value = '1';
					else valcounter.value = parseInt(valcounter.value) + 1;
					//if(window.console) window.console.log ('valcounter.value: ' + valcounter.value);
				}
				
				var lastField = null;
				var newField = jQuery('\
				<li class=\"".$value_classes."\">\
					<span class=\"fcfield_textval inline_style_published inlinefile-file-info-txt\" id=\"a_name'+id+'\">'+file+'</span> \
					<input type=\"hidden\" id=\"a_id'+id+'_".$field->id."\" name=\"".$fieldname."\" value=\"'+id+'\" class=\"contains_fileid\"/> \
					<span class=\"fcfield-drag-handle\" title=\"".JText::_( 'FLEXI_CLICK_TO_DRAG' )."\"></span> \
					<span class=\"fcfield-delvalue\" title=\"".JText::_( 'FLEXI_REMOVE_VALUE' )."\" onclick=\"deleteField".$field->id."(this);\"></span> \
				</li>\
				');
				";
			
			// Add new field to DOM
			$js .= "
				lastField ?
					(insert_before ? newField.insertBefore( lastField ) : newField.insertAfter( lastField ) ) :
					newField.appendTo( jQuery('#sortables_".$field->id."') ) ;
				if (remove_previous) lastField.remove();
				
				// Add jQuery modal window to the select image file button
				jQuery('a.addfile_".$field->id."').each(function(index, value) {
					jQuery(this).on('click', function() {
						var url = jQuery(this).attr('href');
						fc_field_dialog_handle_".$field->id." = fc_showDialog(url, 'fc_modal_popup_container');
						return false;
					});
				});
				";
			
			// Add new element to sortable objects (if field not in group)
			if (!$use_ingroup) $js .= "
				jQuery('#sortables_".$field->id."').sortable({
					handle: '.fcfield-drag-handle',
					containment: 'parent',
					tolerance: 'pointer'
				});
				";
			
			// Show new field, increment counters
			$js .="
				//newField.fadeOut({ duration: 400, easing: 'swing' }).fadeIn({ duration: 200, easing: 'swing' });
				if (scroll_visible) fc_scrollIntoView(newField, 1);
				if (animate_visible) newField.css({opacity: 0.1}).animate({ opacity: 1 }, 800);
				
				rowCount".$field->id."++;       // incremented / decremented
				uniqueRowNum".$field->id."++;   // incremented only
			}

			function deleteField".$field->id."(el, groupval_box, fieldval_box)
			{
				// Find field value container
				var row = fieldval_box ? fieldval_box : jQuery(el).closest('li');
				
				if ( 1 )
				{
					// A deleted container always has a value, thus decrement (or empty) the counter value in the 'required' form element
					var valcounter = document.getElementById('".$field->name."');
					valcounter.value = ( !valcounter.value || valcounter.value=='1' )  ?  ''  :  parseInt(valcounter.value) - 1;
					//if(window.console) window.console.log ('valcounter.value: ' + valcounter.value);
				}
				
				// Add empty container if last element, instantly removing the given field value container
				if(rowCount".$field->id." == 0)
					addField".$field->id."(null, groupval_box, row, {remove_previous: 1, scroll_visible: 0, animate_visible: 0});
				
				// Remove if not last one, if it is last one, we issued a replace (copy,empty new,delete old) above
				if(rowCount".$field->id." > 0) {
					// Destroy the remove/add/etc buttons, so that they are not reclicked, while we do the hide effect (before DOM removal of field value)
					row.find('.fcfield-delvalue').remove();
					row.find('.fcfield-insertvalue').remove();
					row.find('.fcfield-drag-handle').remove();
					// Do hide effect then remove from DOM
					row.slideUp(400, function(){
						this.remove();
					});
					rowCount".$field->id."--;
				}
			}
			";
			
			$remove_button = '<span class="fcfield-delvalue" title="'.JText::_( 'FLEXI_REMOVE_VALUE' ).'" onclick="deleteField'.$field->id.'(this);"></span>';
			$move2 = '<span class="fcfield-drag-handle" title="'.JText::_( 'FLEXI_CLICK_TO_DRAG' ).'"></span>';
		} else if ($inputmode==0) {
			$remove_button = '';
			$move2 = '';
			$js .= "
			function file_fcfield_del_existing_value".$field->id."(el)
			{
				var el = jQuery(el);
				if ( el.prop('checked') )
					el.parent().find('.inlinefile-file-info-txt').css('text-decoration', 'line-through');
				else
					el.parent().find('.inlinefile-file-info-txt').css('text-decoration', '');
			}
			";
			$css .= '';
		} else {
			$remove_button = '';
			$move2 = '';
			$js .= '';
			$css .= '';
		}
		
		$css .= '
			#sortables_'.$field->id.' li span.inline_style_published   { color:#444; }
			#sortables_'.$field->id.' li span.inline_style_unpublished { background: #ffffff; color:gray; border-width:0px; text-decoration:line-through; }
			';
		if ($js)  $document->addScriptDeclaration($js);
		if ($css) $document->addStyleDeclaration($css);
		flexicontent_html::loadFramework('flexi-lib');
		
		// Add jQuery modal window to the select image file button, the container will be created if it does not exist already
		if ( $inputmode ) {
			$js ="
			jQuery(document).ready(function() {
				jQuery('a.addfile_".$field->id."').each(function(index, value) {
					jQuery(this).on('click', function() {
						var url = jQuery(this).attr('href');
						fc_field_dialog_handle_".$field->id." = fc_showDialog(url, 'fc_modal_popup_container');
						return false;
					});
				});
			});
			";
			if ($js)  $document->addScriptDeclaration($js);
		}
		
		
		$field->html = array();
		$n = 0;
		
		if ($inputmode == 0)
		{
			//$this->setField($field);
			//$this->setItem($item);
			
			$formlayout = $field->parameters->get('formlayout', '');
			$formlayout = $formlayout ? 'field_'.$formlayout : 'field_InlineBoxes';
			
			//$this->displayField( $formlayout );
			include(self::getFormPath($this->fieldtypes[0], $formlayout));
		}
		
		else foreach($files_data as $file_id => $file_data)
		{
			$filename_original = $file_data->filename_original ? $file_data->filename_original : $file_data->filename;
			
			$field->html[] = '
				'.($file_data->published ?
				'  <span class="fcfield_textval inline_style_published inlinefile-file-info-txt" id="a_name'.$n.'">'.$filename_original.'</span> '
					.($file_data->url ? ' ['.$file_data->altname.']' : '') :
				'  <span class="fcfield_textval inline_style_unpublished inlinefile-file-info-txt" style="opacity:0.5; text-style:italic;" id="a_name'.$n.'" [UNPUBLISHED]">'.$filename_original.'</span> '
					.($file_data->url ? ' ['.$file_data->altname.']' : '')
				).'
				'.'<input type="hidden" id="a_id'.$file_id.'_'.$field->id.'" name="'.$fieldname.'" value="'.$file_id.'"  class="contains_fileid" />'.'
				'.($use_ingroup ? '' : $move2).'
				'.($use_ingroup ? '' : $remove_button).'
				';
			
			$n++;
			//if (!$multiple) break;  // multiple values disabled, break out of the loop, not adding further values even if the exist
			//if ($max_values && $n >= $max_values) break;  // break out of the loop, if maximum file limit was reached
		}
		
		if ($use_ingroup) { // do not convert the array to string if field is in a group
		} else if ($multiple) { // handle multiple records
			$field->html = !count($field->html) ? '' :
				'<li class="'.$value_classes.'">'.
					implode('</li><li class="'.$value_classes.'">', $field->html).
				'</li>';
			$field->html = '<ul class="fcfield-sortables" id="sortables_'.$field->id.'">' .$field->html. '</ul>';
		} else {  // handle single values
			$field->html = '<div class="fcfieldval_container valuebox fcfieldval_container_'.$field->id.'">' . $field->html[0] .'</div>';
		}
		
		// Add button for popup file selection
		if ($inputmode)
		{
			$autoselect = $field->parameters->get( 'autoselect', 1 ) ;
			$linkfsel = JURI::base(true)
				.'/index.php?option=com_flexicontent&amp;view=fileselement&amp;tmpl=component&amp;layout=default&amp;filter_secure=S&amp;folder_mode=0&amp;index='.$n
				.'&amp;field='.$field->id.'&amp;u_item_id='.$u_item_id.'&amp;autoselect='.$autoselect.'&amp;filter_uploader='.$user->id
				.'&amp;'.(FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken()).'=1';
			
			$_prompt_txt = JText::_( 'FLEXI_ADD_FILE' );
			$field->html .= '
				<span class="fcfield-button-add">
					<a class="addfile_'.$field->id.'" id="'.$elementid.'_addfile" title="'.$_prompt_txt.'" href="'.$linkfsel.'" >'
						.$_prompt_txt.'
					</a>
				</span>';
			
			$field->html .= '<input id="'.$field->name.'" class="'.$required_class.'" type="hidden" name="__fcfld_valcnt__['.$field->name.']" value="'.($n ? $n : '').'" />';
		}
		if ($top_notice) $field->html = $top_notice.$field->html;
	}
	
	
	// Method to create field's HTML display for frontend views
	function onDisplayFieldValue(&$field, $item, $values=null, $prop='display')
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		
		static $langs = null;
		if ($langs === null) $langs = FLEXIUtilities::getLanguages('code');
		
		static $tooltips_added = false;
		static $isMobile = null;
		static $isTablet = null;
		static $useMobile = null;
		if ($useMobile===null) 
		{
			$cparams = JComponentHelper::getParams( 'com_flexicontent' );
			$force_desktop_layout = $cparams->get('force_desktop_layout', 0 );
			//$start_microtime = microtime(true);
			$mobileDetector = flexicontent_html::getMobileDetector();
			$isMobile = $mobileDetector->isMobile();
			$isTablet = $mobileDetector->isTablet();
			//$time_passed = round(1000000 * 10 * (microtime(true) - $start_microtime)) / 10;
			//printf('<br/>-- [Detect Mobile: %.3f s] ', $time_passed/1000000);
		}
		if (!$tooltips_added) {
			FLEXI_J30GE ? JHtml::_('bootstrap.tooltip') : JHTML::_('behavior.tooltip');
			$tooltips_added = true;
		}
		
		$field->label = JText::_($field->label);
		
		$values = $values ? $values : $field->value;
		if ( empty($values) ) { $field->{$prop} = ''; return; }
		
		// Prefix - Suffix - Separator parameters, replacing other field values if found
		$remove_space = $field->parameters->get( 'remove_space', 0 ) ;
		$pretext		= FlexicontentFields::replaceFieldValue( $field, $item, $field->parameters->get( 'pretext', '' ), 'pretext' );
		$posttext		= FlexicontentFields::replaceFieldValue( $field, $item, $field->parameters->get( 'posttext', '' ), 'posttext' );
		$separatorf	= $field->parameters->get( 'separatorf', 1 ) ;
		$opentag		= FlexicontentFields::replaceFieldValue( $field, $item, $field->parameters->get( 'opentag', '' ), 'opentag' );
		$closetag		= FlexicontentFields::replaceFieldValue( $field, $item, $field->parameters->get( 'closetag', '' ), 'closetag' );
		
		if($pretext)  { $pretext  = $remove_space ? $pretext : $pretext . ' '; }
		if($posttext) { $posttext = $remove_space ? $posttext : ' ' . $posttext; }
		
		// some parameter shortcuts
		$useicon = $field->parameters->get( 'useicon', 1 ) ;
		$lowercase_filename = $field->parameters->get( 'lowercase_filename', 1 ) ;
		$link_filename      = $field->parameters->get( 'link_filename', 1 ) ;
		$display_filename	= $field->parameters->get( 'display_filename', 1 ) ;
		$display_lang     = $field->parameters->get( 'display_lang', 1 ) ;
		$display_size			= $field->parameters->get( 'display_size', 0 ) ;
		$display_hits     = $field->parameters->get( 'display_hits', 0 ) ;
		$display_descr		= $field->parameters->get( 'display_descr', 1 ) ;
		
		$add_lang_img = $display_lang == 1 || $display_lang == 3;
		$add_lang_txt = $display_lang == 2 || $display_lang == 3 || $isMobile;
		$add_hits_img = $display_hits == 1 || $display_hits == 3;
		$add_hits_txt = $display_hits == 2 || $display_hits == 3 || $isMobile;
		
		$usebutton    = $field->parameters->get( 'usebutton', 1 ) ;
		$buttonsposition = $field->parameters->get('buttonsposition', 1);
		$use_infoseptxt   = $field->parameters->get( 'use_infoseptxt', 1 ) ;
		$use_actionseptxt = $field->parameters->get( 'use_actionseptxt', 1 ) ;
		$infoseptxt   = $use_infoseptxt   ?  ' '.$field->parameters->get( 'infoseptxt', '' ).' '    :  ' ';
		$actionseptxt = $use_actionseptxt ?  ' '.$field->parameters->get( 'actionseptxt', '' ).' '  :  ' ';
		
		$allowdownloads = $field->parameters->get( 'allowdownloads', 1 ) ;
		$downloadstext  = $allowdownloads==2 ? $field->parameters->get( 'downloadstext', 'FLEXI_DOWNLOAD' ) : 'FLEXI_DOWNLOAD';
		$downloadstext  = JText::_($downloadstext);
		$downloadsinfo  = JText::_('FLEXI_FIELD_FILE_DOWNLOAD_INFO', true);
		
		$allowview = $field->parameters->get( 'allowview', 0 ) ;
		$viewtext  = $allowview==2 ? $field->parameters->get( 'viewtext', 'FLEXI_FIELD_FILE_VIEW' ) : 'FLEXI_FIELD_FILE_VIEW';
		$viewtext  = JText::_($viewtext);
		$viewinfo  = JText::_('FLEXI_FIELD_FILE_VIEW_INFO', true);
		
		$allowshare = $field->parameters->get( 'allowshare', 0 ) ;
		$sharetext  = $allowshare==2 ? $field->parameters->get( 'sharetext', 'FLEXI_FIELD_FILE_EMAIL_TO_FRIEND' ) : 'FLEXI_FIELD_FILE_EMAIL_TO_FRIEND';
		$sharetext  = JText::_($sharetext);
		$shareinfo  = JText::_('FLEXI_FIELD_FILE_EMAIL_TO_FRIEND_INFO', true);
		
		$allowaddtocart = $field->parameters->get( 'use_downloads_manager', 0);
		$addtocarttext  = $allowaddtocart==2 ? $field->parameters->get( 'addtocarttext', 'FLEXI_FIELD_FILE_ADD_TO_DOWNLOADS_CART' ) : 'FLEXI_FIELD_FILE_ADD_TO_DOWNLOADS_CART';
		$addtocarttext  = JText::_($addtocarttext);
		$addtocartinfo  = JText::_('FLEXI_FIELD_FILE_ADD_TO_DOWNLOADS_CART_INFO', true);
		
		$noaccess_display	     = $field->parameters->get( 'noaccess_display', 1 ) ;
		$noaccess_url_unlogged = $field->parameters->get( 'noaccess_url_unlogged', false ) ;
		$noaccess_url_logged   = $field->parameters->get( 'noaccess_url_logged', false ) ;
		$noaccess_msg_unlogged = JText::_($field->parameters->get( 'noaccess_msg_unlogged', '' ));
		$noaccess_msg_logged   = JText::_($field->parameters->get( 'noaccess_msg_logged', '' ));
		$noaccess_addvars      = $field->parameters->get( 'noaccess_addvars', 0);

		// Select appropriate messages depending if user is logged on
		$noaccess_url = JFactory::getUser()->guest ? $noaccess_url_unlogged : $noaccess_url_logged;
		$noaccess_msg = JFactory::getUser()->guest ? $noaccess_msg_unlogged : $noaccess_msg_logged;
		
		// VERIFY downloads manager module is installed and enabled
		static $mod_is_enabled = null;
		if ($allowaddtocart && $mod_is_enabled === null) {
			$db = JFactory::getDBO();
			$query = "SELECT published FROM #__modules WHERE module = 'mod_flexidownloads' AND published = 1";
			$db->setQuery($query);
			$mod_is_enabled = $db->loadResult();
			if (!$mod_is_enabled) {
				JFactory::getApplication()->enqueueMessage("FILE FIELD: please disable parameter \"Use Downloads Manager Module\", the module is not install or not published", 'message' );
			}
		}
		$allowaddtocart = $allowaddtocart ? $mod_is_enabled : 0;
		
		
		// Downloads manager feature
		if ($allowshare) {
			if (file_exists ( JPATH_SITE.DS.'components'.DS.'com_mailto'.DS.'helpers'.DS.'mailto.php' )) {
				$com_mailto_found = true;
				require_once(JPATH_SITE.DS.'components'.DS.'com_mailto'.DS.'helpers'.DS.'mailto.php');
				
				$status = 'width=700,height=360,menubar=yes,resizable=yes';
			} else {
				$com_mailto_found = false;
			}
		}
		
		if($pretext) { $pretext = $remove_space ? $pretext : $pretext . ' '; }
		if($posttext) {	$posttext = $remove_space ? $posttext : ' ' . $posttext; }

		// Description as tooltip
		if ($display_descr==2) JHTML::_('behavior.tooltip');

		switch($separatorf)
		{
			case 0:
			$separatorf = '&nbsp;';
			break;

			case 1:
			$separatorf = '<br />';
			break;

			case 2:
			$separatorf = '&nbsp;|&nbsp;';
			break;

			case 3:
			$separatorf = ',&nbsp;';
			break;

			case 4:
			$separatorf = $closetag . $opentag;
			break;

			case 5:
			$separatorf = '';
			break;

			default:
			$separatorf = '&nbsp;';
			break;
		}
		
		// Initialise property with default value
		$field->{$prop} = array();

		// Get user access level (these are multiple for J2.5)
		$user = JFactory::getUser();
		$aid_arr = JAccess::getAuthorisedViewLevels($user->id);
		
		$n = 0;

		// Get All file information at once (Data maybe cached already)
		// TODO (maybe) e.g. contentlists should could call this function ONCE for all file fields,
		// This may be done by adding a new method to fields to prepare multiple fields with a single call
		$files_data = $this->getFileData( $values, $published=true );   //print_r($files_data); exit;
		
		// Optimization, do some stuff outside the loop
		static $hits_icon = null;
		if ($hits_icon===null && ($display_hits==1 || $display_hits==3)) {

			if ($display_hits==1) {
				$_tooltip_title   = '';
				$_tooltip_content = '%s '.JText::_( 'FLEXI_HITS', true );
				$_attribs = FLEXI_J30GE ?
					'class="hasTooltip fcicon-hits" title="'.JHtml::tooltipText($_tooltip_title, $_tooltip_content, 0, 0).'"' :
					'class="hasTip fcicon-hits" title="'.$_tooltip_title.'::'.$_tooltip_content.'"';
			} else {
				$_attribs = ' class="fcicon-hits"';
			}
			
			$hits_icon = JHTML::image('components/com_flexicontent/assets/images/'.'user.png', JText::_( 'FLEXI_HITS' ), $_attribs) . ' ';
		}
		
		$show_filename = $display_filename || $prop=='namelist';
		$public_acclevel = 1;
		
		
		$viewlayout = $field->parameters->get('viewlayout', '');
		$viewlayout = $viewlayout ? 'value_'.$viewlayout : 'value_InlineBoxes';
		
		//$this->values = $values;
		//$this->displayFieldValue( $prop, $viewlayout );
		include(self::getFormPath($this->fieldtypes[0], $viewlayout));
		
		if (!empty($fancybox_needed)) flexicontent_html::loadFramework('fancybox');
		
		// Apply seperator and open/close tags
		if(count($field->{$prop})) {
			$field->{$prop}  = implode($separatorf, $field->{$prop});
			$field->{$prop}  = $opentag . $field->{$prop} . $closetag;
		} else {
			$field->{$prop} = '';
		}
	}
	
	
	// **************************************************************
	// METHODS HANDLING before & after saving / deleting field events
	// **************************************************************
	
	// Method to handle field's values before they are saved into the DB
	function onBeforeSaveField( &$field, &$post, &$file, &$item )
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		
		// Check if field has posted data
		if ( empty($post) ) return;
		
		// Make sure posted data is an array 
		//echo "<pre>"; print_r($post); exit;
		$post = !is_array($post) ? array($post) : $post;   //echo "<pre>"; print_r($post);
		
		// Get configuration
		$inputmode = (int)$field->parameters->get( 'inputmode', 1 ) ;
		$is_importcsv      = JRequest::getVar('task') == 'importcsv';
		$import_docs_folder  = JRequest::getVar('import_docs_folder');
		
		if ($inputmode==0) {
			$iform_allowdel = $field->parameters->get('iform_allowdel', 1);
			$iform_title = $field->parameters->get('iform_title', 1);
			$iform_desc  = $field->parameters->get('iform_desc',  1);
			$iform_lang  = $field->parameters->get('iform_lang',  0);
			$iform_dir   = $field->parameters->get('iform_dir',   0);
		} else {
			$target_dir = $field->parameters->get('target_dir', 1);
		}
		
		// Execute once
		static $initialized = null;
		static $srcpath_original = '';
		if ( ($is_importcsv || $inputmode==0) && !$initialized ) {
			$initialized = 1;
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.jpath');
			$srcpath_original  = JPath::clean( JPATH_SITE .DS. $import_docs_folder .DS );
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_flexicontent'.DS.'controllers'.DS.'filemanager.php');
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_flexicontent'.DS.'models'.DS.'filemanager.php');
		}
		
		$new=0;
		$newpost = array();
    foreach ($post as $n => $v)
    {
    	if (empty($v)) continue;
			
			// support for basic CSV import / export
			if ( $is_importcsv ) {
				if ( !is_numeric($v) ) {
					$filename = basename($v);
					$sub_folder = dirname($v);
					$sub_folder = $sub_folder && $sub_folder!='.' ? DS.$sub_folder : '';
					
					$fman = new FlexicontentControllerFilemanager();
					JRequest::setVar( 'return-url', null, 'post' );
					JRequest::setVar( 'file-dir-path', DS. $import_docs_folder . $sub_folder, 'post' );
					JRequest::setVar( 'file-filter-re', preg_quote($filename), 'post' );
					JRequest::setVar( 'secure', 1, 'post' );
					JRequest::setVar( 'keep', 1, 'post' );
					$file_ids = $fman->addlocal();
					$v = !empty($file_ids) ? reset($file_ids) : false; // Get fist element
					//$_filetitle = key($file_ids);  this is the cleaned up filename, currently not needed
				}
			}
			
			else if ( $inputmode==0 ) {
				$file_id = (int) $v['file-id'];
				
				$err_code = $_FILES["custom"]["error"][$field->name][$n]['file-data'];
				$new_file = $err_code == 0;
				if ( $err_code && $err_code!=UPLOAD_ERR_NO_FILE)
				{
					$err_msg = array(
						UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
						UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
						UPLOAD_ERR_PARTIAL  => 'The uploaded file was only partially uploaded',
						UPLOAD_ERR_NO_FILE  => 'No file was uploaded',
						UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
						UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
						UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
					);
					JFactory::getApplication()->enqueueMessage("FILE FIELD: ".$err_msg[$err_code], 'warning' );
					continue;
				}
				
				// validate data or empty/set default values
				$v['file-del']   = !$iform_allowdel ? 0 : (int) @ $v['file-del'];
				$v['file-title'] = !$iform_title ? '' : flexicontent_html::dataFilter($v['file-title'],  1000,  'STRING', 0);
				$v['file-desc']  = !$iform_desc  ? '' : flexicontent_html::dataFilter($v['file-desc'],   10000, 'STRING', 0);
				$v['file-lang']  = !$iform_lang  ? '' : flexicontent_html::dataFilter($v['file-lang'],   9,     'STRING', 0);
				$v['secure']     = !$iform_dir   ? $field->parameters->get('iform_dir_default', '1') : ((int) $v['secure'] ? 1 : 0);
				
				// UPDATE existing file
				if( !$new_file && $file_id ) {
					$dbdata = array();
					
					$dbdata['id'] = $file_id;
					if ($iform_title)  $dbdata['altname'] = $v['file-title'];
					if ($iform_desc)   $dbdata['description'] = $v['file-desc'];
					if ($iform_lang)   $dbdata['language'] = $v['file-lang'];
					// !! Do not change folder for existing files
					//if ($iform_dir) {  $dbdata['secure'] = $v['secure'];
					
					// Load file data from DB
					$row = JTable::getInstance('flexicontent_files', '');
					$row->load( $file_id );
					$dbdata['secure'] = $row->secure ? 1 : 0;  // !! Do not change media/secure -folder- for existing files
					
					// Security concern, check file is assigned to current item
					$isAssigned = $this->checkFileAssignment($field, $file_id, $item);
					if ( !$isAssigned ) {
						if ( !$v['file-del'] )
							JFactory::getApplication()->enqueueMessage("FILE FIELD: refusing to update file properties of a file: '".$row->filename_original."', that is not assigned to current item", 'warning' );
						else
							JFactory::getApplication()->enqueueMessage("FILE FIELD: refusing to delete file: '".$row->filename_original."', that is not assigned to current item", 'warning' );
						continue;
					}
					
					// Delete existing file if so requested
					if ( $v['file-del'] && $this->canDeleteFile($field, $file_id, $item) ) {
						$fm = new FlexicontentModelFilemanager();
						$fm->delete( array($file_id) );
						continue;
					}
					
					// Set the changed data into the object
					foreach ($dbdata as $index => $data) $row->{$index} = $data;
					
					// Update DB data of the file 
					if ( !$row->check() || !$row->store() ) {
						JFactory::getApplication()->enqueueMessage("FILE FIELD: ".JFactory::getDBO()->getErrorMsg(), 'warning' );
						continue;
					}
					
					// Set file id as value of the field
					$v = $file_id;
				}
				
				//INSERT new file
				else if( $new_file )
				{
					// new file was uploaded, but also handle previous selected file ...
					if ($file_id)
					{
						// Security concern, check file is assigned to current item
						if ( !$this->checkFileAssignment($field, $file_id, $item) ) {
							$row = JTable::getInstance('flexicontent_files', '');
							$row->load( $file_id );
							JFactory::getApplication()->enqueueMessage("FILE FIELD: refusing to delete file: '".$row->filename_original."', that is not assigned to current item", 'warning' );
						}
						
						// Delete previous file if no longer used
						else if ( $this->canDeleteFile($field, $file_id, $item) ) {
							$fm = new FlexicontentModelFilemanager();
							$fm->delete( array($file_id) );
						}
					}
					$fman = new FlexicontentControllerFilemanager();   // Controller will do the data filter too
					JRequest::setVar( 'return-url', null, 'post' );  // needed !
					JRequest::setVar( 'secure', $v['secure'], 'post' );
					JRequest::setVar( 'file-title', $v['file-title'], 'post' );
					JRequest::setVar( 'file-desc', $v['file-desc'], 'post' );
					JRequest::setVar( 'file-lang', $v['file-lang'], 'post' );
					
					// The dform field name of the <input type="file" ...
					JRequest::setVar( 'file-ffname', 'custom', 'post' );
					JRequest::setVar( 'fname_level1', $field->name, 'post' );
					JRequest::setVar( 'fname_level2', $n, 'post' );
					JRequest::setVar( 'fname_level3', 'file-data', 'post' );
					$file_id = $fman->upload();
					$v = !empty($file_id) ? $file_id : false;
				}
				
				else {
					// no existing file and no new file uploaded
					$v = 0;
				}
			}
			
			if ( !empty ($v) && is_numeric($v) ) $newpost[$v] = $new++;
    }
    $post = array_flip($newpost);
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
	function onAdvSearchDisplayFilter(&$filter, $value='', $formName='searchForm')
	{
		if ( !in_array($filter->field_type, self::$field_types) ) return;
		
		$filter->parameters->set( 'display_filter_as_s', 1 );  // Only supports a basic filter of single text search input
		FlexicontentFields::createFilter($filter, $value, $formName);
	}
	
	
 	// Method to get the active filter result (an array of item ids matching field filter, or subquery returning item ids)
	// This is for search view
	function getFilteredSearch(&$filter, $value, $return_sql=true)
	{
		if ( !in_array($filter->field_type, self::$field_types) ) return;
		
		$filter->parameters->set( 'display_filter_as_s', 1 );  // Only supports a basic filter of single text search input
		return FlexicontentFields::getFilteredSearch($filter, $value, $return_sql);
	}
	
	
	
	// *************************
	// SEARCH / INDEXING METHODS
	// *************************
	
	// Method to create (insert) advanced search index DB records for the field values
	function onIndexAdvSearch(&$field, &$post, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		if ( !$field->isadvsearch && !$field->isadvfilter ) return;
		
		if ($post) {
			$_files_data = $this->getFileData( $post, $published=true, $extra_select =', id AS value_id' );
			$values = array();
			if ($_files_data) foreach($_files_data as $_file_id => $_file_data) $values[$_file_id] = (array)$_file_data;
		} else {
			$field->field_rawvalues = 1;
			$field->field_valuesselect = ' file.id AS value_id, file.altname, file.description, file.filename';
			$field->field_valuesjoin   = ' JOIN #__flexicontent_files AS file ON file.id = fi.value';
			$field->field_groupby      = null;
		}
		FlexicontentFields::onIndexAdvSearch($field, $values, $item, $required_properties=array('filename'), $search_properties=array('altname', 'description'), $properties_spacer=' ', $filter_func='strip_tags');
		return true;
	}
	
	
	// Method to create basic search index (added as the property field->search)
	function onIndexSearch(&$field, &$post, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		if ( !$field->issearch ) return;
		
		if ($post) {
			$_files_data = $this->getFileData( $post, $published=true, $extra_select =', id AS value_id' );
			$values = array();
			if ($_files_data) foreach($_files_data as $_file_id => $_file_data) $values[$_file_id] = (array)$_file_data;
		} else {
			$field->unserialize = 0;
			$field->field_rawvalues = 1;
			$field->field_valuesselect = ' file.id AS value_id, file.altname, file.description, file.filename';
			$field->field_valuesjoin   = ' JOIN #__flexicontent_files AS file ON file.id = fi.value';
			$field->field_groupby      = null;
		}
		FlexicontentFields::onIndexSearch($field, $values, $item, $required_properties=array('filename'), $search_properties=array('altname', 'description'), $properties_spacer=' ', $filter_func='strip_tags');
		return true;
	}
	
	
	
	// **********************
	// VARIOUS HELPER METHODS
	// **********************
	
	function getFileData( $value, $published=1, $extra_select='' )
	{
		// Find which file data are already cached, and if no new file ids to query, then return cached only data
		static $cached_data = array();
		$return_data = array();
		$new_ids = array();
		$values = is_array($value) ? $value : array($value);
		foreach ($values as $file_id) {
			$f = (int)$file_id;
			if ( !isset($cached_data[$f]) && $f)
				$new_ids[] = $f;
		}
		
		// Get file data not retrieved already
		if ( count($new_ids) )
		{
			// Only query files that are not already cached
			$db = JFactory::getDBO();
			$query = 'SELECT * '. $extra_select //filename, filename_original, altname, description, ext, id'
					. ' FROM #__flexicontent_files'
					. ' WHERE id IN ('. implode(',', $new_ids) . ')'
					. ($published ? '  AND published = 1' : '')
					;
			$db->setQuery($query);
			$new_data = $db->loadObjectList('id');

			if ($new_data) foreach($new_data as $file_id => $file_data) {
				$cached_data[$file_id] = $file_data;
			}
		}
		
		// Finally get file data in correct order
		foreach($values as $file_id) {
			$f = (int)$file_id;
			if ( isset($cached_data[$f]) && $f)
				$return_data[$file_id] = $cached_data[$f];
		}

		return !is_array($value) ? @$return_data[(int)$value] : $return_data;
	}


	function addIcon( &$file )
	{
		static $icon_exists = array();
		
		switch ($file->ext)
		{
			// Image
			case 'jpg':
			case 'png':
			case 'gif':
			case 'xcf':
			case 'odg':
			case 'bmp':
			case 'jpeg':
				$file->icon = 'components/com_flexicontent/assets/images/mime-icon-16/image.png';
			break;

			// Non-image document
			default:
				if ( !isset($icon_exists[$file->ext]) ) {
					$icon = JPATH_SITE.DS.'components'.DS.'com_flexicontent'.DS.'assets'.DS.'images'.DS.'mime-icon-16'.DS.$file->ext.'.png';
					$icon_exists[$file->ext] = file_exists($icon);
				}
				if ( $icon_exists[$file->ext] ) {
					$file->icon = 'components/com_flexicontent/assets/images/mime-icon-16/'.$file->ext.'.png';
				} else {
					$file->icon = 'components/com_flexicontent/assets/images/mime-icon-16/unknown.png';
				}
			break;
		}
		return $file;
	}
	
	
	
	// **********************
	// VARIOUS HELPER METHODS
	// **********************
	
	/**
	 * Create form for sharing the download link of given file
	 *
	 * @access public
	 * @since 1.0
	 */
	function share_file_form($tpl = null)
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDBO();
		$session  = JFactory::getSession();
		$document = JFactory::getDocument();
		
		//$tree_var = JRequest::getVar( 'tree_var', "" );
		$file_id    = (int) JRequest::getInt( 'file_id', 0 );
		$content_id = (int) JRequest::getInt( 'content_id', 0 );
		$field_id   = (int) JRequest::getInt( 'field_id', 0 );
		$tpl = JRequest::getCmd( '$tpl', 'default' );
		
		// Check for missing file id
		if (!$file_id) {
			jexit( JText::_('file id is missing') );
		}
		
		// Check file exists
		$query = ' SELECT * FROM #__flexicontent_files WHERE id='. $file_id;
		$db->setQuery( $query );
		$file = $db->loadObject();
		
		if ($db->getErrorNum())  {
			jexit( __FUNCTION__.'(): SQL QUERY ERROR:<br/>'.nl2br($db->getErrorMsg()) );
		}
		if (!$file) {
			jexit( JText::_('file id no '.$file_id.', was not found') );
		}
		
		$data = new stdClass();
		$data->file_id    = $file_id;
		$data->content_id = $content_id;
		$data->field_id   = $field_id;

		// Load with previous data, if it exists
		$mailto		= JRequest::getString('mailto', '', 'post');
		$sender		= JRequest::getString('sender', '', 'post');
		$from			= JRequest::getString('from', '', 'post');
		$subject	= JRequest::getString('subject', '', 'post');
		$desc     = JRequest::getString('desc', '', 'post');

		if ($user->get('id') > 0) {
			$data->sender	= $user->get('name');
			$data->from		= $user->get('email');
		}
		else
		{
			$data->sender	= $sender;
			$data->from		= $from;
		}

		$data->subject = $subject;
		$data->desc    = $desc;
		$data->mailto  = $mailto;
		
		$document->addStyleSheet(JURI::base() . 'components/com_flexicontent/assets/css/flexicontent.css');
		include('file'.DS.'share_form.php');
		$session->set('com_flexicontent.formtime', time());
	}
	
	
	/**
	 * Send email with download (file) link, to the given email address
	 *
	 * @access public
	 * @since 1.0
	 */
	function share_file_email()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		$app  = JFactory::getApplication();
		$session  = JFactory::getSession();
		$document = JFactory::getDocument();
		
		$timeout = $session->get('com_flexicontent.formtime', 0);
		if ($timeout == 0 || time() - $timeout < 2) {
			JError::raiseNotice(500, JText:: _ ('FLEXI_FIELD_FILE_EMAIL_NOT_SENT'));
			return $this->share_file_form();
		}
		
		$SiteName	= $app->getCfg('sitename');
		$MailFrom	= $app->getCfg('mailfrom');
		$FromName	= $app->getCfg('fromname');
		
		
		$file_id    = (int) JRequest::getInt( 'file_id', 0 );
		$content_id = (int) JRequest::getInt( 'content_id', 0 );
		$field_id   = (int) JRequest::getInt( 'field_id', 0 );
		$tpl = JRequest::getCmd( '$tpl', 'default' );
		
		// Check for missing file id
		if (!$file_id) {
			jexit( JText::_('file id is missing') );
		}
		
		// Check file exists
		$query = ' SELECT * FROM #__flexicontent_files WHERE id='. $file_id;
		$db->setQuery( $query );
		$file = $db->loadObject();
		
		if ($db->getErrorNum())  {
			jexit( __FUNCTION__.'(): SQL QUERY ERROR:<br/>'.nl2br($db->getErrorMsg()) );
		}
		if (!$file) {
			jexit( JText::_('file id no '.$file_id.', was not found') );
		}
		


		// Create SELECT OR JOIN / AND clauses for checking Access
		$access_clauses['select'] = '';
		$access_clauses['join']   = '';
		$access_clauses['and']    = '';
		$access_clauses = $this->_createFieldItemAccessClause( $get_select_access = false, $include_file = true );
		
		
		// Get field's configuration
		$q = 'SELECT attribs, name FROM #__flexicontent_fields WHERE id = '.(int) $field_id;
		$db->setQuery($q);
		$fld = $db->loadObject();
		$field_params = new JRegistry($fld->attribs);
		
		// Get all needed data related to the given file
		$query  = 'SELECT f.id, f.filename, f.altname, f.secure, f.url,'
				.' i.title as item_title, i.introtext as item_introtext, i.fulltext as item_fulltext, u.email as item_owner_email, '
				
				// Item and Current Category slugs (for URL)
				. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as itemslug,'
				. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug'
				
				.' FROM #__flexicontent_fields_item_relations AS rel'
				.' LEFT JOIN #__flexicontent_files AS f ON f.id = rel.value'
				.' LEFT JOIN #__flexicontent_fields AS fi ON fi.id = rel.field_id'
				.' LEFT JOIN #__content AS i ON i.id = rel.item_id'
				.' LEFT JOIN #__categories AS c ON c.id = i.catid'
				.' LEFT JOIN #__flexicontent_items_ext AS ie ON ie.item_id = i.id'
				.' LEFT JOIN #__flexicontent_types AS ty ON ie.type_id = ty.id'
				.' LEFT JOIN #__users AS u ON u.id = i.created_by'
				. $access_clauses['join']
				.' WHERE rel.item_id = ' . $content_id
				.' AND rel.field_id = ' . $field_id
				.' AND f.id = ' . $file_id
				.' AND f.published= 1'
				. $access_clauses['and']
				;
		$db->setQuery($query);
		$file = $db->loadObject();
		
		if ($db->getErrorNum())  {
			jexit( __FUNCTION__.'(): SQL QUERY ERROR:<br/>'.nl2br($db->getErrorMsg()) );
		}
		if ( empty($file) ) {
			// this is normally not reachable because the share link should not have been displayed for the user, but it is reachable if e.g. user session has expired
			jexit( JText::_( 'FLEXI_ALERTNOTAUTH' ). "File data not found OR no access for file #: ". $file_id ." of content #: ". $content_id ." in field #: ".$field_id );
		}
		
		$coupon_vars = '';
		if ( $field_params->get('enable_coupons', 0) ) 
		{
			// Insert new download coupon into the DB, in the case the file is sent to a user with no ACCESS
			$coupon_token = uniqid();  // create coupon token
			$query = ' INSERT #__flexicontent_download_coupons '
				. 'SET user_id = ' . (int)$user->id
				. ', file_id = ' . $file_id
				. ', token = ' . $db->Quote($coupon_token)
				. ', hits = 0'
				. ', hits_limit = '. (int)$field_params->get('coupon_hits_limit', 3)
				. ', expire_on = NOW() + INTERVAL '. (int)$field_params->get('coupon_expiration_days', 15).' DAY'
				;
			$db->setQuery( $query );
			$db->query();
			$coupon_id = $db->insertid();  // get id of newly created coupon
			$coupon_vars = '&conid='.$coupon_id.'&contok='.$coupon_token;
		}
		
		$uri  = JURI::getInstance();
		$base = $uri->toString( array('scheme', 'host', 'port'));
		$vars = '&id='.$file_id.'&cid='.$content_id.'&fid='.$field_id . $coupon_vars;
		$link = $base . JRoute::_( 'index.php?option=com_flexicontent&task=download'.$vars, false );
		
		// Verify that this is a local link
		if (!$link || !JURI::isInternal($link)) {
			//Non-local url...
			JError::raiseNotice(500, JText:: _ ('FLEXI_FIELD_FILE_EMAIL_NOT_SENT'));
			return $this->share_file_form();
		}

		// An array of email headers we do not want to allow as input
		$headers = array (	'Content-Type:',
							'MIME-Version:',
							'Content-Transfer-Encoding:',
							'bcc:',
							'cc:');

		// An array of the input fields to scan for injected headers
		$fields = array(
			'mailto',
			'sender',
			'from',
			'subject',
		);

		/*
		 * Here is the meat and potatoes of the header injection test.  We
		 * iterate over the array of form input and check for header strings.
		 * If we find one, send an unauthorized header and die.
		 */
		foreach ($fields as $field)
		{
			foreach ($headers as $header)
			{
				if (strpos($_POST[$field], $header) !== false)
				{
					JError::raiseError(403, '');
				}
			}
		}

		/*
		 * Free up memory
		 */
		unset ($headers, $fields);

		$email		= JRequest::getString('mailto', '', 'post'); echo "<br>";
		$sender		= JRequest::getString('sender', '', 'post'); echo "<br>";
		$from			= JRequest::getString('from', '', 'post'); echo "<br>";
		$_subject = JText::sprintf('FLEXI_FIELD_FILE_SENT_BY', $sender); echo "<br>";
		$subject  = JRequest::getString('subject', $_subject, 'post'); echo "<br>";
		$desc     = JRequest::getString('desc', '', 'post'); echo "<br>";
		
		// Check for a valid to address
		$error	= false;
		if (! $email  || ! JMailHelper::isEmailAddress($email))
		{
			$error	= JText::sprintf('FLEXI_FIELD_FILE_EMAIL_INVALID', $email);
			JError::raiseWarning(0, $error);
		}

		// Check for a valid from address
		if (! $from || ! JMailHelper::isEmailAddress($from))
		{
			$error	= JText::sprintf('FLEXI_FIELD_FILE_EMAIL_INVALID', $from);
			JError::raiseWarning(0, $error);
		}

		if ($error)
		{
			return $this->share_file_form();
		}

		// Build the message to send
		$body  = JText::sprintf('FLEXI_FIELD_FILE_EMAIL_MSG', $SiteName, $sender, $from, $link);
		$body	.= "\n\n".JText::_('FLEXI_FIELD_FILE_EMAIL_SENDER_NOTES').":\n\n".$desc;
		
		// Clean the email data
		$subject = JMailHelper::cleanSubject($subject);
		$body    = JMailHelper::cleanBody($body);
		$sender  = JMailHelper::cleanAddress($sender);
		
		$html_mode=false; $cc=null; $bcc=null;
		$attachment=null; $replyto=null; $replytoname=null;
		
		// Send the email
		$send_result = JFactory::getMailer()->sendMail( $from, $sender, $email, $subject, $body, $html_mode, $cc, $bcc, $attachment, $replyto, $replytoname );
		if ( $send_result !== true )
		{
			JError::raiseNotice(500, JText:: _ ('FLEXI_FIELD_FILE_EMAIL_NOT_SENT'));
			return $this->share_file_form();
		}
		
		$document->addStyleSheet(JURI::base() . 'components/com_flexicontent/assets/css/flexicontent.css');
		include('file'.DS.'share_result.php');
	}


	// Private common method to create join + and-where SQL CLAUSEs, for checking access of field - item pair(s), IN FUTURE maybe moved
	function _createFieldItemAccessClause($get_select_access = false, $include_file = false )
	{
		$user  = JFactory::getUser();
		$select_access = $joinacc = $andacc = '';
		
		$aid_arr = JAccess::getAuthorisedViewLevels($user->id);
		$aid_list = implode(",", $aid_arr);
		
		// Access Flags for: content item and field
		if ( $get_select_access ) {
			$select_access = '';
			if ($include_file) $select_access .= ', CASE WHEN'.
				'   f.access IN (0,'.$aid_list.')  THEN 1 ELSE 0 END AS has_file_access';
			$select_access .= ', CASE WHEN'.
				'  fi.access IN (0,'.$aid_list.')  THEN 1 ELSE 0 END AS has_field_access';
			$select_access .= ', CASE WHEN'.
				'  ty.access IN (0,'.$aid_list.') AND '.
				'   c.access IN (0,'.$aid_list.') AND '.
				'   i.access IN (0,'.$aid_list.')'.
				' THEN 1 ELSE 0 END AS has_content_access';
		}
		
		else {
			if ($include_file)
				$andacc .= ' AND  f.access IN (0,'.$aid_list.')';  // AND file access
			$andacc   .= ' AND fi.access IN (0,'.$aid_list.')';  // AND field access
			$andacc   .= ' AND ty.access IN (0,'.$aid_list.')  AND  c.access IN (0,'.$aid_list.')  AND  i.access IN (0,'.$aid_list.')';  // AND content access
		}
		
		$clauses['select'] = $select_access;
		$clauses['join']   = $joinacc;
		$clauses['and']    = $andacc;
		return $clauses;
	}
	
	
	// ************************************************
	// Returns an array of images that can be deleted
	// e.g. of a specific field, or a specific uploader
	// ************************************************
	function canDeleteFile( &$field, $file_id, &$item )
	{
		// Check file exists in DB
		$db   = JFactory::getDBO();
		$query = 'SELECT id'
			. ' FROM #__flexicontent_files'
			. ' WHERE id='. $db->Quote($file_id)
			;
		$db->setQuery($query);
		$file_id = $db->loadResult();
		if (!$file_id)  return true;
		
		$ignored['item_id'] = $item->id;
		
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_flexicontent'.DS.'models'.DS.'filemanager.php');
		$fm = new FlexicontentModelFilemanager();
		return $fm->candelete( array($file_id), $ignored );
	}
	
	
	// *****************************************
	// Check if file is assigned to current item
	// *****************************************
	function checkFileAssignment( &$field, $file_id, &$item )
	{
		// Check file exists in DB
		$db   = JFactory::getDBO();
		$query = 'SELECT item_id '
			. ' FROM #__flexicontent_fields_item_relations '
			. ' WHERE '
			. '  field_id='. $db->Quote($field->id)
			. '  AND item_id='. $db->Quote($item->id)
			. '  AND value='. $db->Quote($file_id)
			. ' LIMIT 1'
			;
		$db->setQuery($query);
		$db_id = $db->loadResult();
		return (boolean)$db_id;
	}
	
}