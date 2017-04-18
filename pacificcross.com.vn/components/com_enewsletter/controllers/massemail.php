<?php
// No direct access.
defined('_JEXEC') or die;

// Include XML2Array library to convert xml result into array
require_once JPATH_SITE.'/administrator/components/com_enewsletter/XML2Array.php';

jimport('joomla.application.component.controller');

// Include mailchimp/constant contact library files
require(JPATH_SITE.'/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Services\ListService;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\MessageFooter;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;


class EnewsletterControllerMassEmail extends JController {

	//ajax
	function send(){
	
		$result = array('status' => 0, 'msg' => 'Invalid data');
		
		$id = JRequest::getInt('id');
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__advisorsettings');
		$db->setQuery($query, 0, 1);
		$advisordetails = $db->loadObject();
		
		$query->clear()->select('*')->from('#__enewsletter')->where('`id` = '.(int)$id)->where('`email_sent_status` = 0')->where('`type` = "massemail"')->where('(`email_id` = "" OR `email_id` IS NULL)');
		$db->setQuery($query);
		$enewsletterRow = $db->loadObject();
		
		//echo $query->dump();
		
		$query->clear()->select('`group_id`')->from('#__enewsletter_groups')->where('`e_id` = '.(int)$id);
		$db->setQuery($query);
		$gid = $db->loadColumn();
		
		
		//var_dump($advisordetails);
		//var_dump($enewsletterRow);
		//var_dump($gid);
		//exit;
		
		if ( $advisordetails && $enewsletterRow && $gid ) {
			
			$api = $enewsletterRow->api_type;
			$from_email_address = $advisordetails->from_email;
			
			if ($api == 'M'){
				//$verified_email_name ???
				$from_email_address = trim($verified_email_name).'@'.trim($from_email_address);
			}//if
			
			// Get all details of logged user
			$loggeduser = JFactory::getUser();
			
			
			
			$data = array();
			$data['mdefaultemail'] = 3;//massemail.html
			$data['subject'] = $enewsletterRow->subject;
			$data['test_email'] = $from_email_address;
			$data['mass_email_content'] = $enewsletterRow->mass_email_content;
			$data['id'] = $id;
			$data['email_id'] = 0;
			$data['task'] = 'send';
			$data['massemail_disclosure'] = $advisordetails->mass_email_disclosure;
			
			
			$app = JFactory::getApplication();
			
			
			// Set all selected groups id's to state 
			$app->setUserState("com_enewsletter.gid",$gid);
			
			// Set current form's data to state 
			$app->setUserState("com_enewsletter.data",$data);
			
			/* check and make relative path to absolute path of all images start */
			$dom = new SmartDOMDocument();
			$dom->loadHTML($data['mass_email_content']);
			
			
			$mock = new SmartDOMDocument();
			$body = $dom->getElementsByTagName('body')->item(0);
			foreach ($body->childNodes as $child){
				$mock->appendChild($mock->importNode($child, true));
			}
			
			$data['mass_email_content'] = $mock->saveHTML();
			
	
			
			$MASSEMAIL = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/massemail.html"); 
			
			$content = enewsletterHelper::replaceTemplateCode('massemail', $data, $advisordetails, $MASSEMAIL);
			
			
			//print_r($content);
			
			$APIKEY  = CONSTANT_APIKEY;
			$ACCESS_TOKEN = $advisordetails->api_key;
			
			
			//no infusionsoft-getresponse
			if($api == 'M'){
				$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
		  
				$emails = $mailchimp->getVerifiedDomains();
			  
	
				$type = 'regular';
				$campaign_title = time().'_'.$data['subject'];
				$campaign_title =  substr($campaign_title, 0, 78);
				$opts['title'] = $campaign_title;
				$opts['subject'] = $data['subject'];
				
				$opts['from_name'] = $advisordetails->from_name;
				

				$opts['from_email'] = $from_email_address;
						
				$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
				$opts['authenticate'] = true;		
		  
				$email_content = array('html'=>$content);
				
				$email_ids = array();
				foreach($gid as $g){			
					$opts['list_id'] = $g;						
					$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
					if ($mailchimp->errorCode){
						$result['msg'] = JText::_($mailchimp->errorMessage);
					} else {
						$return = $mailchimp->campaignSendNow($retval);
						if ($mailchimp->errorCode){
							$result['msg'] = JText::_($mailchimp->errorMessage);
						} else {			
							$email_ids[] = $retval;
						} 
					}	
					
				}//FOR
				
				$data['email_id'] = implode(',',$email_ids);			
			
			} if($api == 'C'){

				$cc = new ConstantContact($APIKEY);	
				$campaign = new Campaign();
				$campaign_title = time().'_'.$data['subject'];
				$campaign_title =  substr($campaign_title, 0, 78);
				$campaign->name = $campaign_title;
				$campaign->subject = $data['subject'];
				
				$campaign->from_name = $advisordetails->from_name;
				
				$campaign->from_email = $from_email_address;
				$campaign->reply_to_email = $from_email_address; 
				
								
				$campaign->greeting_string = $data['subject'];		
				$campaign->text_content = $data['subject'];			
				$campaign->email_content_format = 'HTML';
				$campaign->email_content = $content;      
				
				foreach($gid as $g){
					$campaign->addList($g);
				}//for
				
				
				//print_r($gid);
				//exit;
				
				
				#HT
				//organization address
				
				$message_footer = new MessageFooter();
				$message_footer->city = $advisordetails->city;
				$message_footer->state = $advisordetails->state;
				$message_footer->country = $advisordetails->country;
				$message_footer->organization_name = $advisordetails->firm;
				$message_footer->address_line_1 = $advisordetails->address1;
				
				//$campaign->message_footer = $message_footer;
				
				
				
				/*echo '<pre>';
				print_r($message_footer);
				print_r($campaign);
				exit;*/
				
				try{
					$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
					
					//echo '<pre>';
					//print_r($return);
					//var_dump($return->id);
					//exit;
					
					try{
						$schedule = new Schedule();
						
						//var_dump($schedule);
						
						$cc->addEmailCampaignSchedule($ACCESS_TOKEN, $return->id, $schedule);
						
						
						$data['email_id'] = $return->id;
						$result['status'] = $return->id;
						
						//var_dump($return);
						
						//var_dump($data);
						
					} catch (CtctException $ex2) {
						
						
						$ccerror = $ex2->getErrors();
						$result['msg'] = JText::_($ccerror[0]['error_message'])." - (from Constant Contact1)";
						
						//print_r($ccerror);
						//print_r($result['msg']);
						
						
					}//try2
					
				}catch (CtctException $ex) {
					$ccerror = $ex->getErrors();
					$result['msg'] = JText::_($ccerror[0]['error_message'])." - (from Constant Contact2)";
				}//try1
			}//if C
			
			//exit;
			
			$app->setUserState("com_enewsletter.data",'');
			$app->setUserState("com_enewsletter.gid",'');
			
			if ( $return && $data['email_id'] ){
				
				// Get buildnesletter model
				require_once JPATH_ADMINISTRATOR.'/components/com_enewsletter/models/buildnewsletter.php';
				$model = JModelLegacy::getInstance('Buildnewsletter', 'EnewsletterModel', array('ignore_request' => true));
				
				// Save/update mass email data	
				$query = $db->getQuery(true);
				$query->update('#__enewsletter');
				$query->set('email_id = '.$db->Quote($data['email_id']));
				$query->set('email_sent_status = 1');
				$query->where('id = '.(int)$data['id']);
				$db->setQuery($query);
				$db->execute();
				
				//echo '<p>'.$query->dump();
			
				
				// Assigns and store data in history table after creating and sending campaign
				$historydata = array();
				$historydata['title'] 			= $data['subject'];
				$historydata['campaign_title'] 	= $campaign_title;
				$historydata['subject'] 		= $data['subject'];
				$historydata['content'] 		= $content;
				$historydata['e_id'] 			= $data['id'];
				$historydata['email_id'] 		= $data['email_id'];
				$historydata['api_type'] 		= $api;
				
				
				$app->setUserState("com_enewsletter.User_ID", $enewsletterRow->user_id);
				
				//print_r($historydata);
				
				require_once JPATH_ADMINISTRATOR.'/components/com_enewsletter/models/history.php';
				$historymodel = JModelLegacy::getInstance('History', 'EnewsletterModel', array('ignore_request' => true));
				$historyreturn = $historymodel->saveHistory($historydata);
				
				
				
				/* Send mail to archive list start */
				if ( $advisordetails->archive_cc_list != '' && $advisordetails->archive_cc_list != NULL ){
				
					if ($api == 'C'){
						$listcontacts = '<br><center><br>Massemail sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
						$curtime = date('m-d-Y h:m:i');
						$i = 1;
						foreach($gid as $g){
							$contacts = $cc->getContactsFromList($ACCESS_TOKEN,$g);
							
							foreach($contacts->results as $con){
								$listcontacts .= '<tr>';
								if($con->status == 'ACTIVE'){
									$listcontacts .= '<td>'.$i.'</td>';
									$listcontacts .= '<td>';
									$listcontacts .= $con->email_addresses[0]->email_address.'</td>';
									$listcontacts .= '<td>'.$curtime.'</td>';
									$i++;
								}
								$listcontacts .= '</tr>';
							}
						}//for
						$listcontacts .= '</table></center>';
					} else if($api == 'M'){
						$listcontacts = '<br><center><br>Massemail sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
						$curtime = date('m-d-Y h:m:i');
						$i = 1;
						foreach($gid as $g){
							$contacts = $mailchimp->listMembers($g);
							foreach($contacts['data'] as $con){
								$listcontacts .= '<tr>';
								$listcontacts .= '<td>'.$i.'</td>';
								$listcontacts .= '<td>';
								$listcontacts .= $con['email'].'</td>';
								$listcontacts .= '<td>'.$curtime.'</td>';
								$listcontacts .= '</tr>';
								$i++;
							}
						}
						$listcontacts .= '</table></center>';    
					}//if
					
					$mailer = JFactory::getMailer();
					$config = JFactory::getConfig();
					
					/*if($from_email_address == ''){
					   $from_email_address =  $app->getUserState("com_enewsletter.advisoremail");
					}*/
					
					$sender = array( 
									$from_email_address,
									$advisordetails->from_name
						 		);	
						 
					$mailer->setSender($sender);
					$recipient = explode(',',$advisordetails->archive_cc_list);		 
			
					$mailer->addRecipient($recipient);
					$mailer->isHTML(true);
					$mailer->Encoding = 'base64';
					$mailer->setSubject($data['subject']);
					$mailer->setBody($content.$listcontacts);
					
					//echo $content.$listcontacts;
	
					$send = $mailer->Send();
					if ( $send !== true ) {
						$result['msg'] = JText::_('Error sending test email.');
					} else {
						$result['msg'] = JText::_('Mail Sent to Archive List Successfully.');
					}//if
					
				}//if archive_cc_list
				/* Send mail to archive list end */	
				
				$result['msg'] = JText::_('Mass Email sent to Email Campaign system successfully.');
				
			}//if return
			
		}//if advisordetails
		
		echo json_encode($result);
		exit;
		
		
	}//func
	
  
  
}
