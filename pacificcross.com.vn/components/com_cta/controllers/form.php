<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/cta.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/cusitems.php';

require(JPATH_COMPONENT.'/libraries/XML2Array.php');

require(JPATH_COMPONENT.'/libraries/constantcontact/src/Ctct/autoload.php');


use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;


require_once JPATH_COMPONENT.'/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_COMPONENT.'/libraries/maichimp/inc/config.inc.php'; //contains apikey


/**
 * @package		Joomla.Site
 * @subpackage	com_content
 */
class CtaControllerForm extends JControllerLegacy {
	
	
	
	
	function submit() {
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$mainframe	= JFactory::getApplication();
		
		$page_referer = JRequest::getVar('page_referer', NULL);
		
		$tmpl = JRequest::getVar('tmpl', NULL);
		
		
		if ($page_referer)
			$this->setRedirect($page_referer);
		else
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=form'.($tmpl?"&tmpl=$tmpl":''), false));
		
		
		$mainframe->setUserState( "cta.selected_video", NULL );
		$mainframe->setUserState( "cta.selected_cusitem", NULL );
		
		
		
		$params = JComponentHelper::getParams('com_cta');
		
		if ($params->get('use_captcha')) {
			//check recaptcha
			
			$plugin = &JPluginHelper::getPlugin('captcha', 'recaptcha');
			$pluginParams = new JRegistry($plugin->params);
			
			$privatekey	= $pluginParams->get('private_key');
			
			$remoteip	= JRequest::getVar('REMOTE_ADDR', '', 'SERVER');
			$challenge	= JRequest::getString('recaptcha_challenge_field', '');
			$response	= JRequest::getString('recaptcha_response_field', '');
			
	
			// Check for Private Key
			if (empty($privatekey)) {
				$this->setMessage('No private key!', 'warning');
				return false;
			}
	
			// Check for IP
			if (empty($remoteip)) {
				$this->setMessage('No IP address!', 'warning');
				return false;
			}//if
			
			
			// Discard spam submissions
			if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
				$this->setMessage('Captcha empty!', 'warning');
				return false;
			}
	
			$response = $this->_recaptcha_http_post("www.google.com", "/recaptcha/api/verify",
															array(
																'privatekey'	=> $privatekey,
																'remoteip'		=> $remoteip,
																'challenge'		=> $challenge,
																'response'		=> $response
															)
											  );
	
			$answers = explode("\n", $response[1]);
			
			if (trim($answers[0]) != 'true') {
				$this->setMessage($answers[1], 'warning');
				return false;
			}//if
		
		}//if
		
		//check required
		$first_name = JRequest::getVar('first_name', NULL);
		$last_name = JRequest::getVar('last_name', NULL);
		$email = JRequest::getVar('email', NULL);
		$phone = JRequest::getVar('phone', NULL);
		$video_arr = JRequest::getVar('video_id', array());
		$used_enewsletter = JRequest::getInt('used_enewsletter', 0);
		
		$cusitem_id_arr = JRequest::getVar('cusitem_id', array());
		
		
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		$setting = $ctaModel->getSetting();
		
		
		
		if ( !$email || (!count($video_arr) && !count($cusitem_id_arr) ) ) {
			$this->setMessage('Missing required fields', 'warning');
			return false;
		} else if ( $setting->mandatory_enewsletter && $setting->used_enewsletter && !$used_enewsletter ) {
			$this->setMessage('Missing required fields', 'warning');
			return false;
		} else {
			//good form
			
			$mainframe->setUserState( "com_cta.moduledatacusitem", NULL );
			$mainframe->setUserState( "com_cta.moduledata", NULL );
			
			$db	= & JFactory::getDBO();
			$query = $db->getQuery( true );
			
			$query->insert('#__cta_register');
			$query->set('`first_name` = '.$db->quote($first_name));
			$query->set('`last_name` = '.$db->quote($last_name));
			$query->set('`email` = '.$db->quote($email));
			if ($phone) $query->set('`phone` = '.$db->quote($phone));
			$query->set('`used_enewsletter` = '.intval($used_enewsletter));
			
			#$selected_video = new JRegistry($video_id);
			#$selected_video = $selected_video->toString();
			
			$video_selected = $video_id = array();
			
			foreach ($video_arr as $video_s) {
				$v_id = substr($video_s, 0, strpos($video_s, '|'));
				$v_name = substr(strchr($video_s, '|'), 1);
				#echo $v_id;
				#echo '<hr/>';
				#echo $v_name;
				$video_id[] = $v_id;
				$video_selected[$v_id] = $v_name;
			}//for
			
			#echo '<hr/>';
			#print_r($video_id);
			#print_r($video_selected);
			#exit;
			
			if (count($video_selected)) {
				$video_selected = serialize($video_selected);
				$query->set('`video_selected` = '.$db->quote($video_selected));
			}//if
						
			if ($cusitem_id_arr) {
				
				
				$CusitemsModel = JModelLegacy::getInstance('Cusitems', 'CtaModel', array('ignore_request' => true));
				$CusitemsModel->setState('filter.selected_item_a', $cusitem_id_arr);
				$cusitems = $CusitemsModel->getItems();
				
				$cusitems_selected = array();
				foreach ($cusitems as $cusitem) {
					$cusitems_selected[$cusitem->id] = $cusitem->title;
				}//for
				
				if (count($cusitems_selected)) {
					$cusitems_selected = serialize($cusitems_selected);
					$query->set('`cusitems_selected` = '.$db->quote($cusitems_selected));
				}//if
			
			}//if cusitem_id_arr
			
			
			$session = JFactory::getSession();
			$session_id = $session->getId();
			
			$query->set('`session_id` = '.$db->quote($session_id));
			$query->set('`date_created` = now()');
			
			$db->setQuery( $query );
			$db->execute();
			
			$report_id = $db->insertid();
			
			//integrate enewsletter
			if ( $used_enewsletter && $email  ) {
				
				if ($setting->constantcontact) {
					
					$constant_contact_key = $params->get('constant_contact_key');
					$constant_contact_token = $setting->constant_contact_token;
					
					if ( $constant_contact_key && $constant_contact_token ) {
						$add_constant_contact = false;
						
						$cc = new ConstantContact($constant_contact_key);
						//get list
						$lists = $cc->getLists($constant_contact_token);
						
						
						
						//echo '<pre>';
						//print_r($lists);
						//exit;
						
						
						$list_id = 0;
						if (count($lists)) {
							$list_id = $lists[0]->id;
						}//if
						
						if ($list_id) {
						
							try {
							
								$response = $cc->getContactByEmail($constant_contact_token, $email);
								#echo '<pre>';
								#print_r($response);
								if ( empty($response->results) ) {
									$contact = new Contact();
									$contact->addEmail($email);
									$contact->addList($list_id);
									//$contact->first_name = $first_name;
									//$contact->last_name = $last_name;
									/*
									 * The third parameter of addContact defaults to false, but if this were set to true it would tell Constant
									 * Contact that this action is being performed by the contact themselves, and gives the ability to
									 * opt contacts back in and trigger Welcome/Change-of-interest emails.
									 *
									 * See: http://developer.constantcontact.com/docs/contacts-api/contacts-index.html#opt_in
									 */
									$returnContact = $cc->addContact($constant_contact_token, $contact, false);
								} else {	
									$contact = $response->results[0];
									$contact->addList($list_id);
									//$contact->first_name = $first_name;
									//$contact->last_name = $last_name;
									$returnContact = $cc->updateContact($constant_contact_token, $contact, false);
								}//if results
								
								if (isset($returnContact)) {
									$add_constant_contact = true;
									#echo '<pre>';
									#print_r($returnContact);
								}//if
							
							} catch (CtctException $ex) {
								#print_r($ex->getErrors());
								#exit;
							}//try
						}//if list_id
						#echo 'constant';
						#exit;
					}//if
					
				}//if constantcontact
				
				
				if ($setting->mailchimp) {
					$add_mailchimp = false;
					
					$mailchimp_api_key = $setting->mailchimp_api_key;
					
					if ( $mailchimp_api_key ) {
						
						$mm = new MCAPI(trim($mailchimp_api_key)); 
						$groups = $mm->lists();
						$groups = $groups['data'];
						if (count($groups)) {
							$list_id = $groups[0]['id'];
						}//if
						
						
						#print_r($groups);
						#echo $list_id;
						#exit;
						
						if ($list_id) {
							$merge_vars = array('FNAME' => $first_name, 'LNAME' => $last_name);
							
							
							//listSubscribe(string apikey, string id, string email_address, array merge_vars, string email_type, bool double_optin, bool update_existing, bool replace_interests, bool send_welcome)
							
							
							$returnContact = $mm->listSubscribe($list_id, $email, $merge_vars, 'html',  false);	
							
							#var_dump($returnContact);
							#echo '<pre>mm';
							#print_r($mm);
							
							if ($mm->errorCode){
								#echo '<pre>errorCode';
								#print_r($mm);
								#echo $mm->errorMessage;
							} elseif ($returnContact) {
								$add_mailchimp = true;
								#echo '<pre>returnContact';
								#print_r($returnContact);
							}//if
						}//if list_id						
					}//if
				}//if mailchimp
				
			}//if used_enewsletter
			//End enewsletter
			
			
			//send email
			$subject = 'CTA notification - '.$mainframe->getCfg( 'sitename' );
			$guest_name = $first_name.' '.$last_name;
			
			$message = "<table cellspacing='2' cellpadding='2' border='1' style='border-collapse:collapse;'>";
			//$message .= "<tr>"; 
			//$message .= "<td>First Name:</td>"; 
			//$message .= "<td>$first_name</td>"; 
			//$message .= "</tr>";
			//$message .= "<tr>"; 
			//$message .= "<td>Last Name:</td>"; 
			//$message .= "<td>$last_name</td>"; 
			//$message .= "</tr>";
			$message .= "<tr>"; 
			$message .= "<td>Email:</td>"; 
			$message .= "<td>$email</td>"; 
			$message .= "</tr>";
			
			if ($phone) {
				$message .= "<tr>"; 
				$message .= "<td>Phone:</td>"; 
				$message .= "<td>$phone</td>"; 
				$message .= "</tr>";
			}//if
			
			//$videos = $ctaModel->getVideos($video_id);
			$message .= "<tr valign='top'>"; 
			$message .= "<td>Reports:</td>"; 
			$message .= "<td>";
			
			foreach ($video_arr as $video_s) {
				$v_name = substr(strchr($video_s, '|'), 1);
				$message .= "$v_name<br/>"; 
			}//for
			
			if ($cusitems) {		
				foreach ($cusitems as $cusitem) {
					$message .= "$cusitem->title<br/>"; 
				}//for
			}//if
			
			$message .= "</td>"; 
			$message .= "</tr>";
			
			
			
			$message .= "<tr>"; 
			$message .= "<td>Newsletter:</td>"; 
			$message .= "<td>";
			$message .= $used_enewsletter?'Yes':'No';
			$message .= "</td>"; 
			$message .= "</tr>";
			$message .= "</table>"; 
			
			
			/*echo $message;
			echo $guest_name;
			echo $email;
			echo $mainframe->getCfg('mailfrom');
			exit;*/
			
			$mail = JFactory::getMailer();
			$mail->addRecipient($mainframe->getCfg('mailfrom'));
			$mail->addBCC('hungtt@iexodus.com');
			$mail->addReplyTo(array($email));
			$mail->setSender(array($email));
			$mail->setSubject($subject);
			$mail->setBody($message);
			$mail->IsHTML(true);
			$sent = $mail->Send();
			
			$mainframe->setUserState( "cta.selected_video", $video_id );
			$mainframe->setUserState( "cta.selected_cusitem", $cusitems );
			
			
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=video'.($tmpl?"&tmpl=$tmpl":''), false));
		}//if
			
		return false;
		
	}//func
	
	function submitone() {
	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$mainframe	= JFactory::getApplication();
		
		$page_referer = JRequest::getVar('page_referer', NULL);
		
		if ($page_referer)
			$this->setRedirect($page_referer);
		else
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=form', false));
			
		//check required
		$cus_name = JRequest::getVar('cus_name', NULL);
		$email = JRequest::getVar('email', NULL);
		$video_arr = JRequest::getVar('video_id', array());
		
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		$setting = $ctaModel->getSetting();
		
		if ( !$email || !count($video_arr) ) {
			$this->setMessage('Missing required fields', 'warning');
			return false;
		} else {
			//good form
			$db	= & JFactory::getDBO();
			$query = $db->getQuery( true );
			
			list($first_name, $last_name) = explode(' ',$cus_name);
			
			$query->insert('#__cta_register');
			$query->set('`first_name` = '.$db->quote($first_name));
			$query->set('`last_name` = '.$db->quote($last_name));
			$query->set('`email` = '.$db->quote($email));
			$video_selected = $video_id = array();
			
			$video_s = $video_arr[0];
			
			$v_id = substr($video_s, 0, strpos($video_s, '|'));
			$v_name = substr(strchr($video_s, '|'), 1);
			#echo $v_id;
			#echo '<hr/>';
			#echo $v_name;
			$video_id[] = $v_id;
			$video_selected[$v_id] = $v_name;
			
			#echo '<hr/>';
			#print_r($video_id);
			#print_r($video_selected);
			#exit;
			
			$video_selected = serialize($video_selected);
			$query->set('`video_selected` = '.$db->quote($video_selected));
			
			$session = JFactory::getSession();
			$session_id = $session->getId();
			
			$query->set('`session_id` = '.$db->quote($session_id));
			$query->set('`date_created` = now()');
			
			$db->setQuery( $query );
			$db->execute();
			
			$report_id = $db->insertid();
			
			//send email
			$subject = 'CTA notification - '.$mainframe->getCfg( 'sitename' );
			$guest_name = $cus_name;
			
			$message = "<table cellspacing='2' cellpadding='2' border='1' style='border-collapse:collapse;'>";
			$message .= "<tr>"; 
			$message .= "<td>Name:</td>"; 
			$message .= "<td>$cus_name</td>"; 
			$message .= "</tr>";
			$message .= "<tr>"; 
			$message .= "<td>Email:</td>"; 
			$message .= "<td>$email</td>"; 
			$message .= "</tr>";
			$videos = $ctaModel->getVideos($video_id);
			$message .= "<tr valign='top'>"; 
			$message .= "<td>Reports:</td>"; 
			$message .= "<td>";
			foreach ($videos as $video_row) {
				$message .= "$video_row[Title]<br/>"; 
			}//for
			$message .= "</td>"; 
			$message .= "</tr>";
			$message .= "</table>"; 
			
			
			/*echo $message;
			echo $guest_name;
			echo $email;
			echo $mainframe->getCfg('mailfrom');
			exit;*/
			
			$mail = JFactory::getMailer();
			$mail->addRecipient($mainframe->getCfg('mailfrom'));
			$mail->addReplyTo(array($email, $guest_name));
			$mail->setSender(array($email, $guest_name));
			$mail->setSubject($subject);
			$mail->setBody($message);
			$mail->IsHTML(true);
			$sent = $mail->Send();
			
			$mainframe->setUserState( "cta.selected_video", $video_id );
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=video', false));
			
		}//if	
	}//func
	
	
	
	/**
	 * Encodes the given data into a query string format.
	 *
	 * @param   string  $data  Array of string elements to be encoded
	 *
	 * @return  string  Encoded request
	 *
	 * @since  2.5
	 */
	private function _recaptcha_qsencode($data)
	{
		$req = "";
		foreach ($data as $key => $value)
		{
			$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}

		// Cut the last '&'
		$req = rtrim($req, '&');
		return $req;
	}

	/**
	 * Submits an HTTP POST to a reCAPTCHA server.
	 *
	 * @param   string  $host
	 * @param   string  $path
	 * @param   array   $data
	 * @param   int     $port
	 *
	 * @return  array   Response
	 *
	 * @since  2.5
	 */
	private function _recaptcha_http_post($host, $path, $data, $port = 80)
	{
		$req = $this->_recaptcha_qsencode($data);

		$http_request  = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
		$http_request .= "Content-Length: " . strlen($req) . "\r\n";
		$http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
		$http_request .= "\r\n";
		$http_request .= $req;
		
		
		//echo $http_request;
		//$fs = @fsockopen($host, $port, $errno, $errstr, 10);
		//var_dump($fs);
		//exit;

		$response = '';
		if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false )
		{
			die('Could not open socket2222');
		}

		fwrite($fs, $http_request);

		while (!feof($fs))
		{
			// One TCP-IP packet
			$response .= fgets($fs, 1160);
		}

		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);

		return $response;
	}//func

}