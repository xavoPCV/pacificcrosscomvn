<?php
// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');


// Include mailchimp/constant contact library files
require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Services\ListService;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\MessageFooter;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;

require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/jsonRPCClient.php');


/**
 * Massemail controller class.
 */
class EnewsletterControllerMassemail extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EnewsletterControllerMassemail
	 *
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since	1.6
	 */
	public function getModel($name = 'Buildnewsletter', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Used to save massemail.
	 *
	 * @return void
	 */
	public function apply(){
		$app = JFactory::getApplication();
		
		// Get current api (C = constant contact/ M = mailchimp)
		$api=$app->getUserState("com_enewsletter.API");
    	
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'post', 'array');
    
   		 $task = JRequest::getVar('tmptask', '','post', '');
		
		
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		
		$templates = $app->getUserState("com_enewsletter.massemailtemplatefiles");
	    $MASSEMAIL = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['mdefaultemail']]); 
		
		
		$content = enewsletterHelper::replaceTemplateCode('massemail', $data, $advisordetails, $MASSEMAIL);
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['mass_email_content']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
			}
		}//for
		$data['mass_email_content'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['mass_email_content'] = $mock->saveHTML();
		
		
		// Assigns data in $data variable to save
		$data['title'] = $data['subject'];
		$data['subject'] = $data['subject'];
		$data['intro'] = '';
		$data['trailer'] = '';
		$data['mass_email_content'] = $data['mass_email_content'];	
		$data['type'] = 'massemail';
		$data['api_type'] = $api;
		$data['email_id'] = '';
		$data['is_active'] = '1';
		$data['email_sent_status'] = '0';
		$data['content'] = $content;
		
		
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// get Buildnewsletter model
		$model = $this->getModel();
    
		 if($task == 'massemail.saveascopy'){
			$data['id'] = '';
		}

		// Save/update massemail data
		if($data['id'] != ''){
			$result = $model->updateNewsletter($data, null, $gid);
		}else{		
			$result = $model->saveNewsletter($data, null, $gid);
		}
    
    	// Update default massemail template
    	$model->updateDefaultTemplate($data['mdefaultemail'],'massemail');
    
   	 	if($data['id'] != ''){
			 $this->setMessage(JText::_('Mass Email Updated Successfully.'));
		}else{
			 $this->setMessage(JText::_('Mass Email Saved Successfully.'));
		}
		
		
		
		#HT - Compliance
		if ( file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php') ) {
			
			require_once(JPATH_ADMINISTRATOR.'/components/com_contentmanager/classes/screenmaster/function.php');
			JLoader::register('ContentManagerHelper', JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
			$site_id = ContentManagerHelper::getSiteID();
			
			$doComplianceConfig = ContentManagerHelper::getSiteConfig($site_id);
			if ($doComplianceConfig->enewsletter_compliance) {
				$this->setMessage('Your Request Has been Submitted To Compliance', 'message');
				$this->setRedirect('index.php?option=com_enewsletter&view=savedemail');
				
				return;
			}//if
			
		}//if
		
		
		 if ( !$site_id || !$doComplianceConfig->enewsletter_compliance ) {
			if($task == 'massemail.saveandnew'){
				$app->setUserState("com_enewsletter.data",'');
				$app->setUserState("com_enewsletter.gid",'');
				$app->setUserState("com_enewsletter.cid",'');	
			}else if($task == 'massemail.saveascopy'){
				$data = $model->getNewsletter($result);
				$data = get_object_vars($data);
				$app->setUserState("com_enewsletter.data",$data);
				$app->setUserState("com_enewsletter.gid",$gid);
				$app->setUserState("com_enewsletter.cid",$cid);
			} else{
				$app->setUserState("com_enewsletter.data",$data);
				$app->setUserState("com_enewsletter.gid",$gid);
				$app->setUserState("com_enewsletter.cid",$cid);               
			}
			$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
	
		}//if
		
		

	}
	
	/**
	 * Used to save and send massemail to email campaign system.
	 *
	 * @return void
	 */
	 public function send2(){
	 
	 	$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
	 	
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
		
		/*echo '<pre>';
		print_r($data);
		print_r(JURI::root(false)."index.php?option=com_enewsletter&task=massemail.send&id=");
		exit;*/
		
		$id = $data['id'];
		
		if ($id) {
			$resulStr = file_get_contents(JURI::root(false)."index.php?option=com_enewsletter&task=massemail.send&id=".$data['id']);
			echo $resulStr;
			
			echo JURI::root(false)."index.php?option=com_enewsletter&task=massemail.send&id=".$data['id'];
			
			exit;
		}//if
		
		
		
		return false;
		
	 }//func
	 
	 
	public function send(){
	
		$app = JFactory::getApplication();
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
    
    
	     // Fetch api details
		$api=$app->getUserState("com_enewsletter.API");
    
		// Get verified email address to use in email campaign creation 
		$from_email_address = JRequest::getVar('verified_emails');
		
		/*echo '<pre>';
		
		print_r($_POST);
		
		var_dump($data);
		//var_dump($from_email_address);
		exit;*/
		
		if($api == 'M'){
			$from_email_address = trim($data['verified_email_name']).'@'.trim($from_email_address);
		}
		
		// Get all details of logged user
		$loggeduser = JFactory::getUser();
		
		// Get api token key to access web service
		$apitoken = $app->getUserState("com_enewsletter.APIToken");  
		
		//var_dump($apitoken);
		//exit; 
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');
		
		// Get and assigns mass email disclosure
    	$data['massemail_disclosure'] = $advisordetails->mass_email_disclosure;
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
		
		
    	$templates = $app->getUserState("com_enewsletter.massemailtemplatefiles");
    	$MASSEMAIL = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['mdefaultemail']]); 
		
		
		$content = enewsletterHelper::replaceTemplateCode('massemail', $data, $advisordetails, $MASSEMAIL);
		
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['mass_email_content']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
			}
		}//for
		$data['mass_email_content'] = $dom->saveHTML();
		
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['mass_email_content'] = $mock->saveHTML();
		
		
		// Fetch api details
		
		$APIKEY  = CONSTANT_APIKEY;
		$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN"); 
		
		
		// Check for current api mailchimp/constant contact and based on that create campaign
		if($api == 'M'){
			$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
      
		  	$emails = $mailchimp->getVerifiedDomains();
		  //echo '<pre>';
		  //print_r($emails);exit;

			$type = 'regular';
			$campaign_title = time().'_'.$data['subject'];
			$campaign_title =  substr($campaign_title, 0, 78);
			$opts['title'] = $campaign_title;
			$opts['subject'] = $data['subject'];
  	  		$opts['from_name'] = $app->getUserState("com_enewsletter.advisorname");
			if($from_email_address){
  		  		$opts['from_email'] = $from_email_address;
			  }else{
				$opts['from_email'] = $app->getUserState("com_enewsletter.advisoremail");
			}	
      				
			$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
			$opts['authenticate'] = true;		
      
     	 //echo '<pre>';
     	 //print_r($opts);exit;	
			$email_content = array('html'=>$content);
			
			$email_ids = array();
			foreach($gid as $g){			
				$opts['list_id'] = $g;						
				$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
				if ($mailchimp->errorCode){
					$this->setMessage(JText::_($mailchimp->errorMessage),'error');
      				$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
					return;
				} else 
				{
					$return = $mailchimp->campaignSendNow($retval);
					if ($mailchimp->errorCode){
						$this->setMessage(JText::_($mailchimp->errorMessage),'error');
        		$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
						return;
					} else {			
						$email_ids[] = $retval;	
					} 
				}	
				
			} 
			
			$data['email_id'] = implode(',',$email_ids);			
		
		}else if($api == 'C'){

			$cc = new ConstantContact($APIKEY);	
			$campaign = new Campaign();
			$campaign_title = time().'_'.$data['subject'];
			$campaign_title =  substr($campaign_title, 0, 78);
			$campaign->name = $campaign_title;
			$campaign->subject = $data['subject'];
	   		$campaign->from_name = $app->getUserState("com_enewsletter.advisorname");
			
			
			
			
	    	if($from_email_address){
				  $campaign->from_email = $from_email_address;
				  $campaign->reply_to_email = $from_email_address;  
			  }else{
				$campaign->from_email = $app->getUserState("com_enewsletter.advisoremail");
				$campaign->reply_to_email = $app->getUserState("com_enewsletter.advisoremail");  
			  }
      		    			
			$campaign->greeting_string = $data['subject'];		
			$campaign->text_content = $data['subject'];			
			$campaign->email_content_format = 'HTML';
			$campaign->email_content = $content;      
			
			foreach($gid as $g){
				$campaign->addList($g);
			}  
			try{
				$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
				try{
					$schedule = new Schedule();
					$cc->addEmailCampaignSchedule($ACCESS_TOKEN, $return->id, $schedule);
					$data['email_id'] = $return->id;
				}catch (CtctException $ex) {
					$ccerror = $ex->getErrors();
					$this->setMessage(JText::_($ccerror[0]['error_message'])." - (from Constant Contact)",'error');
      				$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
					return;
				}
				
			}catch (CtctException $ex) {
				$ccerror = $ex->getErrors();
				$this->setMessage(JText::_($ccerror[0]['error_message'])." - (from Constant Contact)",'error');
    			$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
				return;
			}					
		} else if ($api == 'G') {
			
			$api_url = 'http://api2.getresponse.com';
			$client = new jsonRPCClient($api_url);
			
			$details = $client->send_newsletter(
													$ACCESS_TOKEN,
													array (
														'campaign' => $gid[0],
														'subject'  => $data['subject'],
														'name'     => date('Ymd-h:i:s'),
														"contents" => array('html' => $content),
														'get_contacts' => array('campaigns' => $gid)
													)
												);
												
			if ($details && !$details['error'] && $details['result']) {
				$return = $details['result'];
			} else {
				if ($details['error']) {
					$this->setMessage($details['error']['message']." - (from GetResponse)",'error');
				} else {
					$this->setMessage('send massmail error');
				}//if
				
    			$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
				return;
				
			}//if									
		} else if ($api == 'I') {
		
			$infusionsoft_host = $advisordetails->api_login_name;
				
			$infusionsoft_api_key = $ACCESS_TOKEN;
			
			require JPATH_SITE.'/administrator/components/com_enewsletter/libraries/infusionsoft/infusionsoft.php';
			
			$contacts = Infusionsoft_DataService::query(new Infusionsoft_Contact(), array('Company' => 'api'));
			$email_a = array();
			foreach ($contacts as $contact) {
				$email_a[$contact->Id] = $contact->Email;
			}//for
			
			if (count($email_a)) {
				
				$contactFromApp1 = array_shift($contacts);
				
				$fromAddress = $advisordetails->from_email;
				$toAddress = $contactFromApp1->Email;
				$ccAddresses = $advisordetails->from_email;
				$bccAddresses = $advisordetails->from_email;
				$contentType = 'HTML';
				$subject = $data['subject'];
				$htmlBody = $content;
				$textBody = strip_tags($content);
				
			
				$return = Infusionsoft_EmailService::sendEmail(array_keys($email_a), $fromAddress, $toAddress, $ccAddresses, $bccAddresses, $contentType, $subject, $htmlBody, $textBody);
				
				if (!$return) {
					$this->setMessage('send massmail error');
					$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
					return;
				}//if
				
			}//if
			
			
		
		}//if
		
		$app->setUserState("com_enewsletter.data",'');
		$app->setUserState("com_enewsletter.gid",'');
		$app->setUserState("com_enewsletter.email",'');
		
		if(!empty($return)){
			// Assigns and save current forms data in newsletter table
			$data['email_id'] = $data['email_id'];
			$data['title'] = $data['subject'];
			$data['subject'] = $data['subject'];
			$data['intro'] = '';
			$data['trailer'] = '';
			$data['mass_email_content'] = $data['mass_email_content'];	
			$data['type'] = 'massemail';
			$data['api_type'] = $api;		
			$data['is_active'] = '1';		
			$data['email_sent_status'] = '1';
			$data['content'] = $content;
			
      		// Get buildnesletter model
			$model = $this->getModel();	
      
      		// Save/update mass email data	
			$db = JFactory::getDBO();
			if($data['id'] != ''){
				//$result = $model->updateNewsletter($data, null, $gid);
				
				$query = $db->getQuery(true);
				$query->update('#__enewsletter');
				$query->set('email_id = '.$db->Quote($data['email_id']));
				
				$query->set('`title` = '.$db->Quote($data['title']));
				$query->set('`subject` = '.$db->Quote($data['subject']));
				$query->set('`mass_email_content` = '.$db->Quote($data['mass_email_content']));
				$query->set('`content` = '.$db->Quote($data['content']));
				
				$query->set('email_sent_status = 1');
				$query->where('id = '.(int)$data['id']);
				$db->setQuery($query);
				$db->execute();
				
				$result = (int)$data['id'];
				
			}else{	
				$result = $model->saveNewsletter($data, null, $gid);
			}
      
      		// Update default massemail template
     	 	$model->updateDefaultTemplate($data['mdefaultemail'],'massemail');
			
			// Assigns and store data in history table after creating and sending campaign
			$historydata = array();
			$historydata['title'] = $data['subject'];
			$historydata['campaign_title'] = $campaign_title;
			$historydata['subject'] = $data['subject'];
			$historydata['content'] = $content;
			$historydata['e_id'] 	= $result;
			$historydata['email_id'] = $data['email_id'];
			$historydata['api_type'] = $api;
			
			$historymodel = $this->getModel('History');
			$historyreturn = $historymodel->saveHistory($historydata);
			
			/* Send mail to archive list start */
			if($advisordetails->archive_cc_list != '' && $advisordetails->archive_cc_list != null){
			
				if($api == 'C'){
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
						
					}
					$listcontacts .= '</table></center>';
				}else if($api == 'M'){
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
				}
				
				$mailer = JFactory::getMailer();
				$config = JFactory::getConfig();
				if($from_email_address == ''){
				   $from_email_address =  $app->getUserState("com_enewsletter.advisoremail");
				}
				$sender = array( 
					$from_email_address,
				  $app->getUserState("com_enewsletter.advisorname")
					 );	
					 
				$mailer->setSender($sender);
				$recipient = explode(',',$advisordetails->archive_cc_list);		 
		
				$mailer->addRecipient($recipient);
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';
				$mailer->setSubject($data['subject']);
				$mailer->setBody($content.$listcontacts);

				$send = $mailer->Send();
				if ( $send !== true ) {
					$this->setMessage(JText::_('Error sending test email.'),'error');
				} else {
					$this->setMessage(JText::_('Mail Sent to Archive List Successfully.'));
				}
			}
			/* Send mail to archive list end */	

		
			$this->setMessage(JText::_('Mass Email sent to Email Campaign system successfully.'));
		}else{
			$this->setMessage(JText::_('Mass Email Not Sent.'),'error');
		}
		
		$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
	}//func
	
	/**
	 * Used to send test newsletter to test email address.
	 *
	 * @return void
	 */
	public function test(){
		$app = JFactory::getApplication();
		
    // Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
    
     // Fetch api details
		$api=$app->getUserState("com_enewsletter.API");
    
		// Get verified email address to use in email campaign creation 
		$from_email_address = JRequest::getVar('verified_emails');
		
		if($api == 'M'){
			$from_email_address = trim($data['verified_email_name']).'@'.trim($from_email_address);
		}
	
		$apitoken = $app->getUserState("com_enewsletter.APIToken");
		
		$gid =  JRequest::getVar('gid');				
	
    // Get enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
    // Get aand set dvisor details
		$advisordetails = $emodel->getAdvisordetails();
		
    // Get and set current task
		$data['task'] =  JRequest::getVar('task');
		
    // Get and set massemail disclosure
		$data['massemail_disclosure'] = $advisordetails->mass_email_disclosure;
		
    // Set current data in state
		$app->setUserState("com_enewsletter.cid",$cid);			
		$app->setUserState("com_enewsletter.data",$data);
		
		
    
	
	    $templates = $app->getUserState("com_enewsletter.massemailtemplatefiles");
    	$MASSEMAIL = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['mdefaultemail']]); 
		
		$content = enewsletterHelper::replaceTemplateCode('massemail', $data, $advisordetails, $MASSEMAIL);

		
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		if($from_email_address == ''){
			 $from_email_address =  $app->getUserState("com_enewsletter.advisoremail");
		  }
		  
		 
		  
		$sender = array( 
				$from_email_address,
				$app->getUserState("com_enewsletter.advisorname")
			 );		
			  
		$mailer->setSender($sender);
		$recipient = $data['test_email'];		 

		$mailer->addRecipient($recipient);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject($data['subject']);
		$mailer->setBody($content);
	
		$send = $mailer->Send();

		if ($send !== true) {
			$this->setMessage(JText::_('Error sending test email.'),'error');
		} else {
			$this->setMessage(JText::_('Test Mail Sent Successfully.'));
		}
				
		$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
		
	}
	
	/**
	 * Used to preview massemail.
	 *
	 * @return void
	 */
	public function preview()
	{
		$app = JFactory::getApplication();
		
    // Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
		
		/* check and make relative path to absolute path of all images start */
		$dom=new SmartDOMDocument();
		$dom->loadHTML($data['mass_email_content']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
		
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
				
			}
		}//for
		
		$data['mass_email_content'] = $dom->saveHTML();
		/* check and make relative path to absolute path of all images end */
		   
    // Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
    	// Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
    	// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
    	// Get current task
		$data['task'] =  JRequest::getVar('task');
		
		
		enewsletterHelper::replaceTemplateCode('massemail', $data, $advisordetails);
		
		
		//echo '<pre>';
		//print_r($data);
		//exit;
		
		// Set form data in session state start
		$app->setUserState("com_enewsletter.gid",$gid);		
		$app->setUserState("com_enewsletter.data",$data);

		$this->setRedirect('index.php?option=com_enewsletter&view=massemail');

	}
	
	/**
	 * Used to send mail to compliance for approval.
	 *
	 * @return void
	 */
	public function sendtocompliance(){
		$app = JFactory::getApplication();
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
    
     // Fetch api details
		$api=$app->getUserState("com_enewsletter.API");
    
		// Get verified email address to use in email campaign creation 
		$from_email_address = JRequest::getVar('verified_emails');
		
		if($api == 'M'){
			$from_email_address = trim($data['verified_email_name']).'@'.trim($from_email_address);
		}
		
		// Get api token key to access web service
		$apitoken = $app->getUserState("com_enewsletter.APIToken");
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');
		
		$data['massemail_disclosure'] = $advisordetails->mass_email_disclosure;
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
		
		
		// Set mass email content
    	$model = $this->getModel('Buildnewsletter');
		
		$footer = '';
    	$mailfooter = '<p>&nbsp;</p><p>'.$advisordetails->address1.' '.$advisordetails->address2.', <br>'.$advisordetails->city.', '.$advisordetails->state.' - '.$advisordetails->zip.'</p>' ;
    	
		

	    
    	$templates = $app->getUserState("com_enewsletter.massemailtemplatefiles");
    	$MASSEMAIL = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['mdefaultemail']]); 
	
		
		
		
		$content = enewsletterHelper::replaceTemplateCode('massemail', $data, $advisordetails, $MASSEMAIL);
		
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['mass_email_content']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
			}
		}//for
		$data['mass_email_content'] = $dom->saveHTML();
		
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['mass_email_content'] = $mock->saveHTML();
		
		
    	$orgid = $app->getUserState("com_enewsletter.orgid");
			
		// Assigns data in $data variable to save
   	 	$wdata['orgid'] = $orgid;
		$wdata['title'] = $data['subject'];
		$wdata['subject'] = $data['subject'];
		$wdata['intro'] = '';
		$wdata['trailer'] = '';
		$wdata['mass_email_content'] = $data['mass_email_content'];	
		$wdata['type'] = 'massemail';
		$wdata['ctype'] = 'Email';
		$wdata['api_type'] = $api;
		$wdata['email_id'] = '';
		$wdata['is_active'] = '1';
		$wdata['email_sent_status'] = '0';
		$wdata['approval_status'] = 'PND';
		$wdata['content'] = $content;
		$wdata['id'] = $data['id'];
		
		$complianceresultid = $model->sendToCompliance($wdata,$gid);
		$wdata['approval_email_id'] = $complianceresultid;
		
		// Save update weekly update data
		if($data['id'] != ''){
			$result = $model->updateNewsletter($wdata, null, $gid);
		}else{		
			$result = $model->saveNewsletter($wdata, null, $gid);
		}	
    
    	// Update default massemail template
    	$model->updateDefaultTemplate($data['mdefaultemail'],'massemail');
		
		
		if($result){
		
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
		  if($from_email_address == ''){
			 $from_email_address =  $app->getUserState("com_enewsletter.advisoremail");
		  }
			$sender = array( 
				$from_email_address,
				$app->getUserState("com_enewsletter.advisorname")
				 );					 
			$mailer->setSender($sender);
      
      		$advisorsitedetails = $app->getUserState("com_enewsletter.advisormssqldetails");
			$mailsubject = 'Compliance Review Of Email Requested by '.$advisorsitedetails["url"].' - '.$advisorsitedetails["orgName"].'';
			$mailbody = 'Compliance Review Of Email Requested by '.$advisorsitedetails["url"].' - '.$advisorsitedetails["orgName"].'<br>Please review the email submitted by '.$loggeduser->name.' to the compliance backoffice at http:\\backoffice1.advisorsites.com\login.asp';

			$recipient = array();
			if($advisorsitedetails['OSJEmail'])
			$recipient[] = $advisorsitedetails['OSJEmail'];			
			$supervisoryInstEmail = explode(',',$advisorsitedetails['supervisoryInstEmail']);
			foreach($supervisoryInstEmail as $sie){
				$recipient[] = $sie;
			}
      
        	$recipient = array();
        	$recipient[] = 'paresh.nagar@agileinfoways.com';

			$mailer->addRecipient($recipient);
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->setSubject($mailsubject);
			$mailer->setBody($mailbody.$mailfooter);
			$send = $mailer->Send();
	
			if ( $send !== true ) {
				$this->setMessage(JText::_('Error sending test email.'),'error');
			}
			
			$app->setUserState("com_enewsletter.cid",$cid);	
		
			$app->setUserState("com_enewsletter.data",$data);
			
			$this->setMessage(JText::_('MassEmail sent for approval successfully.'));
		}else{		
			$this->setMessage(JText::_('Could not save MassEmail.'),'error');
		}
		$this->setRedirect('index.php?option=com_enewsletter&view=massemail');

	}


	/**
	 * Performs cancel action
	 *
	 * @return void
	 */
	function cancel(){
		
		$app = JFactory::getApplication();
    	$this->setRedirect('index.php?option=com_enewsletter');
	}
	
}
