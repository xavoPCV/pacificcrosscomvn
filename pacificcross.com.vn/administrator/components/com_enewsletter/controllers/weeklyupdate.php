<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey


use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Services\ListService;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\MessageFooter;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;

require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/jsonRPCClient.php');

/**
 * Weeklyupdate controller class.
 */
class EnewsletterControllerWeeklyupdate extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EnewsletterControllerWeeklyupdate
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
	 */
	public function getModel($name = 'Buildnewsletter', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	
	/**
	 * Used to save weekly update.
	 *
	 * @return void
	 */
	public function apply(){
		$app = JFactory::getApplication();
		
		// Get current api (C = constant contact/ M = mailchimp)
		$api=$app->getUserState("com_enewsletter.API");
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'post', 'array');
    
    	// Get temporary task 
   		$task = JRequest::getVar('tmptask');
    		
		
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// get Buildnewsletter model
		$model = $this->getModel();
		
		// Get all articles for weekly update
    $cid = array(); 
    $sentfrom = $app->getUserState("com_enewsletter.sentfrom"); 
    		
		// Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');		
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
    
	
	
    
		if($sentfrom == 'savedemail' && $data['id'] != '') {
			  $saveddata = $model->getNewsletter($data['id']);
			  $saveddata = get_object_vars($saveddata);
			  $data['weekly_update_content'] = $saveddata['weekly_update_content'];  
			  $cid = $app->getUserState("com_enewsletter.cid"); 
			  $app->setUserState("com_enewsletter.sentfrom",'');
		}else{
		  $emodel->setWeeklyUpdateContent();
		  $cid[] =  $app->getUserState("com_enewsletter.Weeklyupdatearticleid");			
		  $data['weekly_update_content'] =  $app->getUserState("com_enewsletter.Weeklyupdatedesc");
		}
		// Set weekly update mail content
		$footer = '';
		
		
	$templates = $app->getUserState("com_enewsletter.weeklyupdatetemplatefiles");
    $WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['wdefaultemail']]);  
	
	
	$content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails, $WEEKLYUPDATE);
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['intro']);
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
		$data['intro'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['intro'] = $mock->saveHTML();
   		
		// Assigns data in $data variable to save
		$data['title'] = $data['subject'];
		$data['subject'] = $data['subject'];
		$data['intro'] = $data['intro'];
		$data['trailer'] = '';
		$data['mass_email_content'] ='';	
		$data['type'] = 'weeklyupdate';
		$data['api_type'] = $api;
		$data['email_id'] = '';
		$data['is_active'] = '1';
		$data['email_sent_status'] = '0';
		$data['content'] = $content;
		$data['weekly_update_content'] = $data['weekly_update_content'];
			
		if($task == 'weeklyupdate.saveascopy'){
			$data['id'] = '';
		}
    
		// Save update weekly update data
		if($data['id'] != ''){
			$result = $model->updateNewsletter($data, $cid, $gid);
		}else{		
			$result = $model->saveNewsletter($data, $cid, $gid);
		}
    
		#HT - also update advisorsettings
		if ($result) {
			$db = & JFactory::getDBO();
			//no log here because do it on updateDefaultTemplate
		}//if
	
    	// Update default weekly update template
    	$model->updateDefaultTemplate($data['wdefaultemail'],'weeklyupdate');
		
		// get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get weeklyupdate groups and set to state
		$weeklyupdategroups = $emodel->getWeeklyupdateGroups();	
			
		$weeklyupdategroup = array();
		foreach($weeklyupdategroups as $wu){
			$weeklyupdategroup[] = $wu->group_id;
		}
		$app->setUserState("com_enewsletter.UpdateGroups",$weeklyupdategroup);
		
		// remove current form data from state
		$app->setUserState("com_enewsletter.data",'');
		$app->setUserState("com_enewsletter.gid",'');
		$app->setUserState("com_enewsletter.email",'');
		
		if($data['id'] != ''){
			$this->setMessage(JText::_('Weekly Update Updated Successfully.'));
		}else{
			$this->setMessage(JText::_('Weekly Update Saved Successfully.'));
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
			
		} 
		
		if ( !$site_id || !$doComplianceConfig->enewsletter_compliance ) {
			//Set/Unset current form's data in state (Session)
			if($task == 'weeklyupdate.saveandnew'){
				$app->setUserState("com_enewsletter.data",'');
				$app->setUserState("com_enewsletter.gid",'');
				$app->setUserState("com_enewsletter.cid",'');	
			}else if($task == 'weeklyupdate.saveascopy'){
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
			
			$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');

		}//if
    
		
	}
	
	
	/**
	 * Used to save and send weekly update to email campaign system.
	 *
	 * @return void
	 */
	public function send(){
	
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
		
		// Get all details of logged user
		$loggeduser = JFactory::getUser();
		
		// Get api token key to access web service
		$apitoken = $app->getUserState("com_enewsletter.APIToken");
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// Get Buildnewsletter model
		$model = $this->getModel();	
		
		
		// Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');		
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');
		
    // Get weekly update disclosure
		$data['weekly_update_newsletter'] = $advisordetails->weekly_update_newsletter;
		
		// Set all selected articles id's to state 
		$app->setUserState("com_enewsletter.cid",$cid);	
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
    
    	// Set description link in article description
		$cid = array();   
		$sentfrom = $app->getUserState("com_enewsletter.sentfrom");     
		if($sentfrom == 'savedemail' && $data['id'] != '') {
			  $saveddata = $model->getNewsletter($data['id']);
			  $saveddata = get_object_vars($saveddata);
			  $data['weekly_update_content'] = $saveddata['weekly_update_content'];  
			  $cid = $app->getUserState("com_enewsletter.cid"); 
			  $app->setUserState("com_enewsletter.sentfrom",'');
		}else{
		  $emodel->setWeeklyUpdateContent();
		  $cid[] =  $app->getUserState("com_enewsletter.Weeklyupdatearticleid");					
		  $data['weekly_update_content'] =  $app->getUserState("com_enewsletter.Weeklyupdatedesc");
		}
		
		//var_dump($articlecontent);
		//exit;
		
    // Get all templates of newsletter type
		$templates = $app->getUserState("com_enewsletter.weeklyupdatetemplatefiles");
    
    	// Get content from selected template file
    	$WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['wdefaultemail']]);
    
	  $footer = '';
	  
	  $content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails, $WEEKLYUPDATE);
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['intro']);
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
		$data['intro'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['intro'] = $mock->saveHTML();

		
		// Fetch api details
		$api=$app->getUserState("com_enewsletter.API");
		$APIKEY  = CONSTANT_APIKEY;
		$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");
		
		
		// Check for current api mailchimp/constant contact and based on that create campaign
		if($api == 'M'){
			$mailchimp = new MCAPI(trim($ACCESS_TOKEN));

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
			$email_content = array('html'=>$content, 
									'html_footer' => 'the footer with an *|UNSUB|* message' );
			
			$email_ids = array();
			foreach($gid as $g){			
				$opts['list_id'] = $g;							
				$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
				if ($mailchimp->errorCode){
					$this->setMessage(JText::_($mailchimp->errorMessage),'error');
          			$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
					return;
				} else 
				{
					$return = $mailchimp->campaignSendNow($retval);
					if ($mailchimp->errorCode){
						$this->setMessage(JText::_($mailchimp->errorMessage),'error');
        		$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
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
					$this->setMessage(JText::_($ccerror[0]['error_message'])." - ( from Constant Contact)",'error');
      				$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
					return;
				}
				
			}catch (CtctException $ex) {
				$ccerror = $ex->getErrors();
				$this->setMessage(JText::_($ccerror[0]['error_message'])." - (from Constant Contact)",'error');
    			$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
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
					$this->setMessage('send weeklyupdate error');
				}//if
				
    			$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
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
					$this->setMessage('send weeklyupdate error');
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
			$data['title'] = $data['subject'];
			$data['subject'] = $data['subject'];
			$data['intro'] = $data['intro'];
			$data['trailer'] = '';
			$data['mass_email_content'] ='';	
			$data['type'] = 'weeklyupdate';
			$data['api_type'] = $api;		
			$data['is_active'] = '1';		
			$data['email_sent_status'] = '1';
			$data['content'] = $content;
     	 	$data['weekly_update_content'] = $articlecontent;
			
			$model = $this->getModel();
			$db = JFactory::getDBO();
			if($data['id'] != ''){
				//$result = $model->updateNewsletter($data, $cid, $gid);
				$query = $db->getQuery(true);
				$query->update('#__enewsletter');
				$query->set('email_id = '.$db->Quote($data['email_id']));
				
				$query->set('`title` = '.$db->Quote($data['title']));
				$query->set('`subject` = '.$db->Quote($data['subject']));
				$query->set('`intro` = '.$db->Quote($data['intro']));
				$query->set('`weekly_update_content` = '.$db->Quote($data['weekly_update_content']));
				$query->set('`content` = '.$db->Quote($data['content']));
				
				$query->set('email_sent_status = 1');
				$query->where('id = '.(int)$data['id']);
				$db->setQuery($query);
				$db->execute();
				
				$result = (int)$data['id'];				
			}else{		
				$result = $model->saveNewsletter($data, $cid, $gid);
			}
      
      		// Update default weekly update template
      		$model->updateDefaultTemplate($data['wdefaultemail'],'weeklyupdate');
			
			// Get weeklyupdate groups and set to state
			$weeklyupdategroups = $emodel->getWeeklyupdateGroups();				
			$weeklyupdategroup = array();
			foreach($weeklyupdategroups as $wu){
				$weeklyupdategroup[] = $wu->group_id;
			}
			$app->setUserState("com_enewsletter.UpdateGroups",$weeklyupdategroup);

			
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
					$listcontacts = '<br><center><br>Weekly Update sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
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
					$listcontacts = '<br><center><br>Weekly Update sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
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
					$this->setMessage(JText::_('Mail Sent To Archive List Successfully.'));
				}
			}
			/* Send mail to archive list end */	

		
			$this->setMessage(JText::_('Weekly Update sent to Email Campaign system successfully.'));
		}else{
			$this->setMessage(JText::_('Weekly Update Not Sent.'),'error');
		}
		$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
	}
	
	/**
	 * Used to send test weekly update to test email address.
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
		
    // Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
    // Get buildnewsletter model
		$model = $this->getModel();
	
    // Get enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
    // Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
    // Get current task
		$data['task'] =  JRequest::getVar('task');
		
    // Get and set weekly update disclosure
		$data['weekly_update_newsletter'] = $advisordetails->weekly_update_newsletter;
		
    // Set form data in session state start
		$app->setUserState("com_enewsletter.gid",$gid);		
		$app->setUserState("com_enewsletter.cid",$cid);			
		$app->setUserState("com_enewsletter.data",$data);
    
		$cid = array();   
		$sentfrom = $app->getUserState("com_enewsletter.sentfrom");    
		if($sentfrom == 'savedemail' && $data['id'] != '') {
			  $saveddata = $model->getNewsletter($data['id']);
			  $saveddata = get_object_vars($saveddata);

			  $cid = $app->getUserState("com_enewsletter.cid"); 
			  $data['weekly_update_content'] = $saveddata['weekly_update_content']; 
		}else{
			  $emodel->setWeeklyUpdateContent();
			  $cid[] =  $app->getUserState("com_enewsletter.Weeklyupdatearticleid");	
			  $data['weekly_update_content'] = $app->getUserState("com_enewsletter.Weeklyupdatedesc");		
		}
    
		// Get all templates of newsletter type
		$templates = $app->getUserState("com_enewsletter.weeklyupdatetemplatefiles");
    
    // Get content from selected  template file
    $WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['wdefaultemail']]);  
		
		

    // Replace title, intro, articles, disclosure placeholders from template start
	  $footer = '';

		
		$content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails, $WEEKLYUPDATE);		
		
		
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

		if ( $send !== true ) {
			$this->setMessage(JText::_('Error sending test email.'),'error');
		} else {
			$this->setMessage(JText::_('Test Mail Sent Successfully.'));
		}
		$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
		
	}
	
	/**
	 * Used to preview weekly update.
	 *
	 * @return void
	 */
	public function preview()
	{
		$app = JFactory::getApplication();
		
		
		//die('XC');
    
    // Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
	
    // Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
    // Get buildnesletter model
		$model = $this->getModel();
    
    // Get enesletter model
    	$emodel = $this->getModel('Enewsletter');
    
		$cid = array();   
		$sentfrom = $app->getUserState("com_enewsletter.sentfrom");     
		
		
		
		
		if($sentfrom == 'savedemail' && $data['id'] != '') {
			  $saveddata = $model->getNewsletter($data['id']);
			  $saveddata = get_object_vars($saveddata);
			  $articlecontent = $saveddata['weekly_update_content']; 
			  $data['weekly_update_content'] = $saveddata['weekly_update_content'];
			  $cid = $app->getUserState("com_enewsletter.cid"); 
		}else{
		
		  $emodel->setWeeklyUpdateContent();
		  
		  $cid[] =  $app->getUserState("com_enewsletter.Weeklyupdatearticleid");			
		  $articlecontent =  $app->getUserState("com_enewsletter.Weeklyupdatedesc");
		  $data['weekly_update_content'] = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
		}		
		
    // Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		
    // Get and set current task
		$data['task'] =  JRequest::getVar('task');
		
		
		enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails);
		
		
		$footer = '';
    	$data['footer'] = $footer;
		
		
		$app->setUserState("com_enewsletter.gid",$gid);
		
		$app->setUserState("com_enewsletter.cid",$cid);
		
		//echo '<pre>';
		//print_r($data);
		//exit;
		
		$app->setUserState("com_enewsletter.data",$data);
	  	$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');

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
		
		// Get Buildnewsletter model
		$model = $this->getModel();
    // Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');		
		
		// Fetch latest articles
		$cid = array(); 
    	$sentfrom = $app->getUserState("com_enewsletter.sentfrom"); 
    
		if($sentfrom == 'savedemail' && $data['id'] != '') {
			  $saveddata = $model->getNewsletter($data['id']);
			  $saveddata = get_object_vars($saveddata);
			  $articlecontent = $saveddata['weekly_update_content'];  
			  $cid = $app->getUserState("com_enewsletter.cid"); 
			  $app->setUserState("com_enewsletter.sentfrom",'');
		}else{
		  	$emodel->setWeeklyUpdateContent();
		  	$cid[] =  $app->getUserState("com_enewsletter.Weeklyupdatearticleid");			
			$data['weekly_update_content'] = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
		  	$articlecontent  = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
		}  
    
    
		
		
		
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');
		
		$data['weekly_update_newsletter'] = $advisordetails->weekly_update_newsletter;
		
		// Set all selected articles id's to state 
		$app->setUserState("com_enewsletter.cid",$cid);	
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
		
		$api=$app->getUserState("com_enewsletter.API");
		
    // Get all templates of newsletter type
		$templates = $app->getUserState("com_enewsletter.weeklyupdatetemplatefiles");
    
    // Get content from selected  template file
    $WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['wdefaultemail']]);  
	
	
	$content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails, $WEEKLYUPDATE);
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['intro']);
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
		$data['intro'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['intro'] = $mock->saveHTML();
		

    // Replace title, intro, articles, disclosure placeholders from template start
	  $footer = '';
	  
	  
	  $orgid = $app->getUserState("com_enewsletter.orgid");
    // Replace title, intro, articles, disclosure placeholders from template end
			
		// Assigns data in $data variable to save
    	$wdata['orgid'] = $orgid;
		$wdata['title'] = $data['subject'];
		$wdata['subject'] = $data['subject'];
		$wdata['intro'] = $data['intro'];
		$wdata['trailer'] = '';
		$wdata['mass_email_content'] ='';	
		$wdata['type'] = 'weeklyupdate';
		$wdata['ctype'] = 'FANSUPDATE';
		$wdata['api_type'] = $api;
		$wdata['email_id'] = '';
		$wdata['is_active'] = '1';
		$wdata['email_sent_status'] = '0';
		$wdata['approval_status'] = 'PND';
		$wdata['content'] = $content;
    	$wdata['weekly_update_content'] = $articlecontent;
		$wdata['id'] = $data['id'];
		
		$complianceresultid = $model->sendToCompliance($wdata,$gid);
		$wdata['approval_email_id'] = $complianceresultid;

		// Save update weekly update data
		if($data['id'] != ''){
			$result = $model->updateNewsletter($wdata, $cid, $gid);
		}else{		
			$result = $model->saveNewsletter($wdata, $cid, $gid);
		}
    
    	// Update default weekly update template
    	$model->updateDefaultTemplate($data['wdefaultemail'],'weeklyupdate');

		
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
						
      // Set form data in session state start
      $app->setUserState("com_enewsletter.cid",$cid);				
			$app->setUserState("com_enewsletter.gid",$gid);			
			$app->setUserState("com_enewsletter.data",$data);
			
			$this->setMessage(JText::_('Weeklyupdate sent for approval successfully.'));
		}else{			
			$this->setMessage(JText::_('Could not save Weeklyupdate.'),'error');
		}

		$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
	}

	/**
	 * Performs cancel action
	 *
	 * @return void
	 */
	public function cancel(){
		
		$app = JFactory::getApplication();
    $this->setRedirect('index.php?option=com_enewsletter');
	}

}
