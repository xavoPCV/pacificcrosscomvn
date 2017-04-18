<?php
/**
 * @version 1.0 $Id$
 * @package Joomla
 * @subpackage FLEXIcontent
 * @subpackage plugin.jprofile
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

class plgFlexicontent_fieldsJProfile extends JPlugin
{
	static $field_types = array('jprofile');
	
	// ***********
	// CONSTRUCTOR
	// ***********
	
	function plgFlexicontent_fieldsJProfile( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		JPlugin::loadLanguage('plg_flexicontent_fields_jprofile', JPATH_ADMINISTRATOR);
	}
	
	
	
	// *******************************************
	// DISPLAY methods, item form & frontend views
	// *******************************************
	
	// Method to create field's HTML display for item form
	function onDisplayField(&$field, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		return false;
	}

	
	
	// Method to create field's HTML display for frontend views
	function onDisplayFieldValue(&$field, $item, $values=null, $prop='display')
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		
		static $users = null;
		if ($users === null)
		{
			$users = array();
			jimport('joomla.user.helper');
			JFactory::getLanguage()->load('com_users', JPATH_SITE, 'en-GB', true);
			JFactory::getLanguage()->load('com_users', JPATH_SITE, null, true);
		}
		
		$displayed_user = $field->parameters->get('displayed_user', 1);
		switch($displayed_user)
		{
			// Current user
			case 3:
				if ( !isset($users[-1]) ) {
					$users[-1] = $users[$user_id] = new JUser();
				} else {
					$user = $users[-1];
				}
				$user_id = $users[-1]->id;
				$user = $users[-1];
				break;
				
			// User selected in item form
			case 2:
				$user_id = (int) reset($field->value);
				if ( !isset($users[$user_id]) ) {
					$user = new JUser($user_id);
				} else {
					$user = $users[$user_id];
				}
				break;
				
			// Item's author
			default:
			case 1:
				$user_id = $item->created_by;
				if ( !isset($users[$user_id]) ) {
					$user = new JUser($item->created_by);
				} else {
					$user = $users[$user_id];
				}
				break;
		}
		
		$user->params = new JRegistry($user->params);
		$user->params = $user->params->toArray();
		
		$user->profile = JUserHelper::getProfile( $user_id );
		//echo "<pre>"; echo print_r($user); echo "</pre>";
		
		$field->{$prop} = '
		<span class="alert alert-info fc-iblock" style="min-width:50%; margin-bottom:0px;">'.JText::_('COM_USERS_PROFILE_CORE_LEGEND').'</span><br/>
		<dl class="dl-horizontal">
			<dt><span class="label">
				'.JText::_('COM_USERS_PROFILE_NAME_LABEL').'
			</span><dt>
			<dd>
				'.$user->name.'
			</dd>
			<dt><span class="label">
				'.JText::_('COM_USERS_PROFILE_USERNAME_LABEL').'
			</span><dt>
			<dd>
				'.htmlspecialchars($user->username).'
			</dd>
			<dt><span class="label">
				'.JText::_('COM_USERS_PROFILE_REGISTERED_DATE_LABEL').'
			</span><dt>
			<dd>
				'.JHtml::_('date', $user->registerDate).'
			</dd>
			<dt><span class="label">
				'.JText::_('COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL').'
			</span><dt>
			'.
			($user->lastvisitDate != '0000-00-00 00:00:00' ? '
				<dd>
					'.JHtml::_('date', $user->lastvisitDate).'
				</dd>
			' : '
				<dd>
					'.JText::_('COM_USERS_PROFILE_NEVER_VISITED').'
				</dd>
			').'
		</dl>
		';
		
		$profile_info = array();
		if (!empty($user->profile->profile)) foreach($user->profile->profile as $pname => $pval) {
			$profile_info[] = '
				<dt><span class="label">'.$pname.'</span></dt>
				<dd>'.$pval.'</dd>';
		}
		if (count($profile_info))
			$field->{$prop} .= '
			<br/>
			<span class="alert alert-info fc-iblock" style="min-width:50%; margin-bottom:0px;">Profile details</span>
			<dl class="dl-horizontal">
				'. implode('', $profile_info).'
			</dl>';
		
		//$userProfile = JUserHelper::getProfile( $user_id );
		//echo "Main Address :" . $userProfile->profile['address1'];
	}
	
	
	
	// **************************************************************
	// METHODS HANDLING before & after saving / deleting field events
	// **************************************************************
	
	// Method to handle field's values before they are saved into the DB
	/*function onBeforeSaveField( &$field, &$post, &$file, &$item )
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
	}*/
	
	
	// Method to take any actions/cleanups needed after field's values are saved into the DB
	/*function onAfterSaveField( &$field, &$post, &$file, &$item ) {
		if ( !in_array($field->field_type, self::$field_types) ) return;
	}*/
	
	
	// Method called just before the item is deleted to remove custom item data related to the field
	/*function onBeforeDeleteField(&$field, &$item) {
		if ( !in_array($field->field_type, self::$field_types) ) return;
	}*/
	
	
	
	// *********************************
	// CATEGORY/SEARCH FILTERING METHODS
	// *********************************
	
	// Method to display a search filter for the advanced search view
	/*function onAdvSearchDisplayFilter(&$filter, $value='', $formName='searchForm')
	{
		if ( !in_array($filter->field_type, self::$field_types) ) return;
	}*/
	
	
 	// Method to get the active filter result (an array of item ids matching field filter, or subquery returning item ids)
	// This is for search view
	/*function getFilteredSearch(&$filter, $value, $return_sql=true)
	{
		if ( !in_array($filter->field_type, self::$field_types) ) return;
	}*/
	
	
	
	// *************************
	// SEARCH / INDEXING METHODS
	// *************************
	
	// Method to create (insert) advanced search index DB records for the field values
	/*function onIndexAdvSearch(&$field, &$post, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		if ( !$field->isadvsearch && !$field->isadvfilter ) return;
		return true;
	}*/
	
	
	// Method to create basic search index (added as the property field->search)
	/*function onIndexSearch(&$field, &$post, &$item)
	{
		if ( !in_array($field->field_type, self::$field_types) ) return;
		if ( !$field->issearch ) return;
		return true;
	}*/
	
	
	
	// **********************
	// VARIOUS HELPER METHODS
	// **********************
}
