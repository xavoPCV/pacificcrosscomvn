<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Login view class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.5
 */
class EnewsletterViewSubscription extends JViewLegacy
{

	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.5
	 */
	public function display($tpl = null)
	{
		$this->data = $this->get('Details');
    //echo '<pre>';
    //print_r($this->data);exit;
		$app = JFactory::getApplication();
		$app->setUserState("com_enewsletter.ACCESS_TOKEN",$this->data->api_key);
		$app->setUserState("com_enewsletter.API",$this->data->newsletter_api);
		if($this->data->newsletter_api == 'C'){
			$this->setLayout( 'constantcontactsubscribe' );
		}else if($this->data->newsletter_api == 'M'){
			$this->setLayout( 'mailchimpsubscribe' );
		}else{
			$this->setLayout( 'notavailable' );		
		}
		parent::display($tpl);
	}

}
