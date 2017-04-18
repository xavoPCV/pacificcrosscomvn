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
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;


/**
 * Subscription controller class.
 */
class EnewsletterControllerSubscription extends JController
{

	/**
	 * Constructor.
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
	 */
	public function getModel($name = 'Subscription', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	
	/**
	 * Used to save newsletter.
	 *
	 * @return void
	 */
	public function save(){
	
		$app = JFactory::getApplication();
		
		$model = $this->getModel();
		$details = $model->getDetails();
    
    	$api = $details->newsletter_api;
    	$TOKEN = $details->api_key;
				
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		
		// Get all selected group's ids
		$cid =  JRequest::getVar('cid');
		
		// Get all selected group's names
		$cname =  JRequest::getVar('cname');
		
		$errormessage ='';
		// Check for email campaign api and add subscriber
		if($api == 'C'){
			$cc = new ConstantContact(CONSTANT_APIKEY);
			
			$contact = new Contact();
      		$contact->addEmail($data['email']);
			foreach($cid as $c){
				$contact->addList($c);
			}
            
      		$contact->first_name = $data['fname'];
      		$contact->last_name = $data['lname'];
			
			try {
				$returnContact = $cc->addContact($TOKEN, $contact, false);
				
			} catch (CtctException $ex) {
				$ccerror = $ex->getErrors();
				$errormessage .= $ccerror[0]['error_message'];
			}
		}else if($api == 'M'){
			$mm = new MCAPI(trim($TOKEN)); 		
			$merge_vars = array('FNAME'=>$data['fname'], 'LNAME'=>$data['lname']);
			for($i = 0;$i < count($cid); $i++){
				$returnContact = $mm->listSubscribe($cid[$i], $data['email'], $merge_vars );	
				if ($mm->errorCode){
					$errormessage .= $mm->errorMessage.'<br>';
				}		
			}
		}	

		
		if(!empty($returnContact)){
			
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$sender = array( 
				$details->from_email,
				$details->from_name
				 );		 		
				 
			$mailer->setSender($sender);
			$mailer->addRecipient($data['email']);
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			
			// Check for auto weekly update email send option and then send latest weekly update mail to added subscriber
			if($details->send_update == 'Y'){	 	
			  
				$model->setWeeklyUpdateContent($details);
				$cid = array();
				$weeklyupdate = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
				$cid = $app->getUserState("com_enewsletter.Weeklyupdatearticleid");	
				if($weeklyupdate){
						//$path_to_quote = $details['path_quote'];
						//if($path_to_quote == ''){
					  	// $path_to_quote = 'javascript:void(0)';
						//}
						//$articlecontent =preg_replace('/<!--#QUOTES#-->/',$path_to_quote,$weeklyupdate);
						
						
						$articlecontent = $weeklyupdate;
					
						$dom=new SmartDOMDocument();
						$dom->loadHTML($articlecontent);
						$mock = new SmartDOMDocument();
						$body = $dom->getElementsByTagName('body')->item(0);
						foreach ($body->childNodes as $child){
							$mock->appendChild($mock->importNode($child, true));
						}    			
						$articlecontent = $mock->saveHTML();	
		  
				}	else{
						$articlecontent = '';
						$webapierror .= 'Unable to fetch weekly update content.';
				}
				
				
				
				$adata = array();
				$adata['weekly_update_content'] = $articlecontent;
				
				JLoader::import('enewsletter', JPATH_ROOT . '/administrator/components/com_enewsletter/models');
				$emodel = JModel::getInstance('Enewsletter', 'EnewsletterModel');
				
				$advisordetails = $emodel->getAdvisordetails();
				
					
					
					$WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/weeklyupdate.html"); 
	
					$content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $adata, $advisordetails, $WEEKLYUPDATE);
					
						
					$mailer->setSubject($details->update_subject);
					$mailer->setBody($content);
					$send = $mailer->Send();
		
			  }
			
			// Check for newsletter email send option and then send latest newsletter mail to added subscriber
			if($details->send_newsletter == 'Y'){
				$newsletter  = $model->getLatestNewsletter();
				if(!empty($newsletter)){
					$mailer->setSubject($newsletter->subject);
					$mailer->setBody($newsletter->content);
					$send = $mailer->Send();

				}                                                                
			}
			$this->setMessage(JText::_($details->join_list_email));
		}else{	
						
			if($errormessage != ''){
				$this->setMessage(JText::_($errormessage), 'error');
			}else{
			}
		
		}
				
		$current_url = JURI::getInstance();
		$this->setRedirect($current_url);

	}
  
  
}
