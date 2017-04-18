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
use Ctct\Services\ListService;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;
use Ctct\Components\EmailMarketing\MessageFooter;

require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/jsonRPCClient.php');

/**
 * Buildnewsletter controller class.
 */
class EnewsletterControllerBuildnewsletter extends JControllerAdmin
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
	public function getModel($name = 'Buildnewsletter', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	
	/**
	 * Used to save/update/save and new newsletter.
	 *
	 * @return void
	 */
         public function getgroup($tpl = null)
        {
             $APIKEY  = CONSTANT_APIKEY;
             $cc = new ConstantContact($APIKEY);            
             $ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN",'');
             $groups = $cc->getLists($ACCESS_TOKEN);
             return $groups;
        }
	public function apply(){
	
		$app = JFactory::getApplication();
		
		// Get current api (C = constant contact/ M = mailchimp)
		$api=$app->getUserState("com_enewsletter.API");
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'post', 'array');
    
    // Get ids of selected images from article list
		$showimage_id = JRequest::getVar('showimages');
		$showimage_ids = explode(',',$showimage_id);
		
		
		
    
    // Get current task
		$task = JRequest::getVar('tmptask');    

		
		// Get all selected article's ids
		$articles_ids = JRequest::getVar('articles');		
		$cid = explode(',',$articles_ids);
    	$ids = implode('" , "',$cid);
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');				
		     	
	  // Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get buildnewsletter model.
		$model = $this->getModel();
		
		// Get all articles from com_fnc and com_fbc table using artilce ids 
		$articles = $model->getArticle($ids);
		
		$articlecontent = '';
    
    	// Get all templates of newsletter type    
    	$templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
    
    	// Get content from selected  template file
    	$NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
    
    	//  Create and replace content for selected articles start
    	$articleimgarray = array();
    	$j  =1;
		foreach($articles as $ar){
		
				 // Create article content and more link  start
			  $articlelink='';
			  
			  $custom_link_article = $advisordetails->custom_link_article;
			  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
			  
			  if($valid_format) {
				$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
			  }
			  else
			  {
				if($ar->type == 'Featured News') 
				{
				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
				}
				else if($ar->type == 'Financial Briefs')
				{
				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
				}
				else
				{
				  $articlelink='';
				}
				
			  }
			
			  // Set article link for more in description	
			  if ( $articlelink !='')      
			  {
				$titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
			  }
			  else
			  {
				$titlelink = '';
			  }
        		$articlebody = $ar->description.'<br/>'.$titlelink;
        	 // Create article content and more link  end
			  
			  if(trim($articlelink) ==''){
			  	$articlelink = 'javascript:void(0);';	
			  }
			  
			  $ar->articlelink =  $articlelink;
			  
			  
			  //Create article image  path start  
			  if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
				  $path = $this->create_image($ar->slideshowimage);
				  $path =  JURI::base().'components/com_enewsletter/images/article_images/'.$path; 
  				  if($path != ''){
  						$ar->image =  $path;
  					}else{
  						$ar->image = '';
  					}          
				  } else{
					 $ar->image = '';
				}
        //Create article image  path end
				  
				$ar->description = $ar->description.'<br/>'.$titlelink;
				$j++;
			  
			  $j++;
		}//for
		
		// Assigns all articles to $data variable
		$data['articles'] = $articles;
    
    // Replace title, intro, trailer, disclosure placeholders from template start
		$footer = '';
		
		$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
		
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
		
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['trailer']);
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
		$data['trailer'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['trailer'] = $mock->saveHTML();
   		
	
		// Assigns form data in $data variable to save
		$data['title'] = $data['title'];
		$data['subject'] = $data['subject'];
		$data['intro'] = $data['intro'];
		$data['trailer'] = $data['trailer'];
		$data['mass_email_content'] ='';	
		$data['api_type'] = $api;
		$data['email_id'] = '';
		$data['is_active'] = '1';
		$data['type'] = 'enewsletter';
		$data['email_sent_status'] = '0';
		$data['content'] = $content;
		
		
		// Get all selected article's ids
		$articles_ids = JRequest::getVar('articles');		
		$cid = explode(',',$articles_ids);
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
		
		// get Buildnewsletter model
		$model = $this->getModel();
    
		if($task == 'buildnewsletter.saveascopy'){
			$data['id'] = '';
		}
		
		// Save/update enewsletter data
		if($data['id'] != ''){
			$result = $model->updateNewsletter($data, $cid, $gid, $showimage_ids);
		}else{	
			$result = $model->saveNewsletter($data, $cid, $gid, $showimage_ids);
		}
    
   		 $model->updateDefaultTemplate($data['ndefaultemail'],'newsletter');
    
			if($data['id'] != ''){
				$this->setMessage(JText::_('E-Newsletter Updated Successfully.'));
			}else{
				$this->setMessage(JText::_('E-Newsletter Saved Successfully.'));
			}
		
		
		
		#HT - Compliance
		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php')) {
			
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
		
		
			// Set/unset form data in session state start
			if($task == 'buildnewsletter.saveandnew'){
				$app->setUserState("com_enewsletter.data",'');
				$app->setUserState("com_enewsletter.gid",'');
				$app->setUserState("com_enewsletter.cid",'');	
				$app->setUserState("com_enewsletter.showimage_ids",'');
			}else if($task == 'buildnewsletter.saveascopy'){
				$data = $model->getNewsletter($result);
				$data = get_object_vars($data);
			  $app->setUserState("com_enewsletter.data",$data);
				$app->setUserState("com_enewsletter.gid",$gid);
				$app->setUserState("com_enewsletter.cid",$cid); 
				$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids); 
			} else{ 
				$app->setUserState("com_enewsletter.data",$data);
				$app->setUserState("com_enewsletter.gid",$gid);
				$app->setUserState("com_enewsletter.cid",$cid);
				$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);
			}
			// Set/unset form data in session state end  
			
			$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
		
		}//if
	}
	
	/**
	 * Used to save and send newsletter to email campaign system.
	 *
	 * @return void
	 */
	public function send(){
	
		$app = JFactory::getApplication();
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
    
    // Fetch api details
		$api=$app->getUserState("com_enewsletter.API");
		$APIKEY  = CONSTANT_APIKEY;
		$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");
    
    	// Get verified email address to use in email campaign creation 
    	$from_email_address = JRequest::getVar('verified_emails');
    
    	if($api == 'M'){
        	$from_email_address = trim($data['verified_email_name']).'@'.trim($from_email_address);
    	}
		
		// Get all details of logged user
		$loggeduser = JFactory::getUser();
		
		// Get api token key to access web service
		$apitoken = $app->getUserState("com_enewsletter.APIToken");
		
		// Get all selected article's ids
		$articles_ids = JRequest::getVar('articles');
                $cid = explode(',',$articles_ids);
                $ids = implode('" , "',$cid);
    
    // Get ids of selected images from article list 
		$showimage_id = JRequest::getVar('showimages');
		$showimage_ids = explode(',',$showimage_id);	
		
		/*var_dump($cid);
		var_dump($ids);
		var_dump($showimage_ids);
		exit;*/
			
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');		
    
    // Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get the model.
		$model = $this->getModel();
		
		// Get all articles from com_fnc and com_fbc table using artilce ids
		$articles = $model->getArticle($ids);
		
		$articlecontent = '';
    
    // Get all templates of newsletter type
    $templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
    
    // Get content from selected  template file
    $NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
	
	
    
    // Create and replace content for selected articles start
    $articleimgarray = array();
    $j  =1;
	foreach($articles as $ar){		
		 // Create article content and more link  start
		  $articlelink='';
			  
		  $custom_link_article = $advisordetails->custom_link_article;
		  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
		  
		  if($valid_format) {
			$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
		  }
		  else
		  {
			if($ar->type == 'Featured News') 
			{
			  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
			}
			else if($ar->type == 'Financial Briefs')
			{
			  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
			}
			else
			{
			  $articlelink='';
			}
			
		  }
		
		  // Set article link for more in description	
		  if ( $articlelink !='')      
		  {
			$titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
		  }
		  else
		  {
			$titlelink = '';
		  }
			$articlebody = $ar->description.'<br/>'.$titlelink;
		 // Create article content and more link  end
		  
		  if(trim($articlelink) ==''){
			$articlelink = 'javascript:void(0);';	
		  }
		  
		  $ar->articlelink =  $articlelink;
		  
		  
		  //Create article image  path start  
		  if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
			  $path = $this->create_image($ar->slideshowimage);
			  $path =  JURI::base().'components/com_enewsletter/images/article_images/'.$path; 
			  if($path != ''){
					$ar->image =  $path;
				}else{
					$ar->image = '';
				}          
			  } else{
				 $ar->image = '';
			}
	//Create article image  path end
			  
			$ar->description = $ar->description.'<br/>'.$titlelink;
			$j++; 

		}
		
		//echo $NEWSLETTER;
		//exit;
     
    
   	
		// Assigns all articles to $data variable
		$data['articles'] = $articles;
		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');
		
		
		// Set all selected articles id's to state 
		$app->setUserState("com_enewsletter.cid",$cid);	
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
    
   	 	$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
	//	var_dump($app->getUserState("com_enewsletter.data"));die;
		
		// Replace title, intro, trailer, disclosure placeholders from template start
		$footer = '';
    	
		
		$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
		
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
		
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['trailer']);
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
		$data['trailer'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['trailer'] = $mock->saveHTML();

			
			
			//echo $content;
			//exit;
			
		
    // Replace title, intro, trailer, disclosure placeholders from template end	

		// Check for current api mailchimp/constant contact and based on that create campaign
		if($api == 'M'){
			$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
			$type = 'regular';
			$campaign_title = time().'_'.$data['title'];
			$campaign_title =  substr($campaign_title, 0, 78);
			$opts['title'] = $campaign_title;
			$opts['subject'] = $data['subject'];
  		if($from_email_address){
  		  $opts['from_email'] = $from_email_address;
      }else{
        $opts['from_email'] = $app->getUserState("com_enewsletter.advisoremail");
      }
			$opts['from_name'] = $app->getUserState("com_enewsletter.advisorname");				
			$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
			$opts['authenticate'] = true;			
			$email_content = array('html'=>$content, 
									'html_footer' => 'the footer with an *|UNSUB|* message' );
			
			$email_ids = array();
			foreach($gid as $g){			
				$opts['list_id'] = $g;							
				$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
				if ($mailchimp->errorCode){
					$this->setMessage(JText::_($mailchimp->errorMessage). " - (from Mail Chimp)",'error');
					$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');

					return;
				} else 
				{
					$return = $mailchimp->campaignSendNow($retval);
					if ($mailchimp->errorCode){
						$this->setMessage(JText::_($mailchimp->errorMessage),'error');
  					$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
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
			$campaign_title = time().'_'.$data['title'];
			$campaign_title =  substr($campaign_title, 0, 78);	
			$campaign->name = $campaign_title;
			$campaign->from_name = $app->getUserState("com_enewsletter.advisorname");
			  if($from_email_address){
				  $campaign->from_email = $from_email_address;
				  $campaign->reply_to_email = $from_email_address;
			  }else{
				$campaign->from_email = $app->getUserState("com_enewsletter.advisoremail");
				$campaign->reply_to_email = $app->getUserState("com_enewsletter.advisoremail"); 
			  }	    
	         
			$campaign->subject = $data['subject'];
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
					$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
					return;
				}
				
			}catch (CtctException $ex) {
				$ccerror = $ex->getErrors();
				$this->setMessage(JText::_($ccerror[0]['error_message'])." - (from Constant Contact)",'error');
				$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
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
					$this->setMessage('send newsletter error');
				}//if
				
    			$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
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
					$this->setMessage('send newsletter error');
					$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
					return;
				}//if
				
			}//if						
		}//if
		
		
		if(!empty($return)){			
			
			// Assigns form data in $data variable to save/update
			$data['title'] = $data['title'];
			$data['subject'] = $data['subject'];
			$data['intro'] = $data['intro'];
			$data['trailer'] = $data['trailer'];
			$data['mass_email_content'] ='';	
			$data['type'] = 'enewsletter';
			$data['api_type'] = $api;	
			$data['is_active'] = '1';		
			$data['email_sent_status'] = '1';	
			$data['content'] = $content;			
		
      // get Buildnewsletter model
			$model = $this->getModel();	
      
      		// Save/update enewsletter data	
			$db = JFactory::getDBO();			
			if($data['id'] != ''){
				//$result = $model->updateNewsletter($data, $cid, $gid, $showimage_ids);
				$query = $db->getQuery(true);
				$query->update('#__enewsletter');
				$query->set('email_id = '.$db->Quote($data['email_id']));
				
				$query->set('`title` = '.$db->Quote($data['title']));
				$query->set('`subject` = '.$db->Quote($data['subject']));
				$query->set('`intro` = '.$db->Quote($data['intro']));
				$query->set('`trailer` = '.$db->Quote($data['trailer']));
				$query->set('`content` = '.$db->Quote($data['content']));
				
				$query->set('email_sent_status = 1');
				$query->where('id = '.(int)$data['id']);
				$db->setQuery($query);
				$db->execute();
				
				$result = (int)$data['id'];	
			}else{		
				$result = $model->saveNewsletter($data, $cid, $gid, $showimage_ids);
			}
			
			
			// Assigns and store data in history table after creating and sending campaign
			$historydata = array();
			$historydata['title'] = $data['title'];
			$historydata['campaign_title'] = $campaign_title;
			$historydata['subject'] = $data['subject'];
			$historydata['content'] = $content;
			$historydata['e_id'] = $result;
			$historydata['email_id'] = $data['email_id'];
			$historydata['api_type'] = $api;
						
			$historymodel = $this->getModel('History');
			$historyreturn = $historymodel->saveHistory($historydata);		
			
			
			/* Send mail to archive list start */
			if($advisordetails->archive_cc_list != '' && $advisordetails->archive_cc_list != null){
				if($api == 'C'){
					$listcontacts = '<center><br>Newsletter sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
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
					$listcontacts = '<center><br>Newsletter sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
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
					$this->setMessage(JText::_('Mail sent to Archive List Successfully.'));
				}
			}
			/* Send mail to archive list end */		
			$this->setMessage(JText::_('E-Newsletter sent to Email Campaign system successfully.'));
		}else{
			$this->setMessage(JText::_('E-Newsletter Not Sent.'),'error');
		}
		$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
	}
	
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
    
    // Get ids of selected images from article list   
		$showimage_id = JRequest::getVar('showimages');
		$showimage_ids = explode(',',$showimage_id);
    
    // Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
    
    // Get current user's enewsletter details		
		$advisordetails = $emodel->getAdvisordetails();
    
    // Get all selected article's ids
    $articles_ids = JRequest::getVar('articles'); 		
		$cid = explode(',',$articles_ids);
    $ids = implode('" , "',$cid);
		
		// Get all selected group's ids
    $gid =  JRequest::getVar('gid');		
		
		// Get buidnewsletter model.
		$model = $this->getModel();
		
		// Get all articles from com_fnc and com_fbc table using artilce ids
    $articles = $model->getArticle($ids);
    
    // Get all templates of newsletter type
    $templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
    
    // Get content from selected  template file
		$NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
    
    //  Create and replace content for selected articles start
    $articleimgarray = array();
    $j  =1;
		foreach($articles as $ar){
		
			  // Create article content and more link  start
			  $articlelink='';
			  
			  $custom_link_article = $advisordetails->custom_link_article;
			  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
			  
			  if($valid_format) {
				$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
			  }
			  else
			  {
				if($ar->type == 'Featured News') 
				{
				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
				}
				else if($ar->type == 'Financial Briefs')
				{
				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
				}
				else
				{
				  $articlelink='';
				}
				
			  }
			
			  // Set article link for more in description	
			  if ( $articlelink !='')      
			  {
				$titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
			  }
			  else
			  {
				$titlelink = '';
			  }
        		$articlebody = $ar->description.'<br/>'.$titlelink;
        	 // Create article content and more link  end
			  
			  if(trim($articlelink) ==''){
			  	$articlelink = 'javascript:void(0);';	
			  }
			  
			  $ar->articlelink =  $articlelink;
			  
			  
			  //Create article image  path start  
			  if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
				  $path = $this->create_image($ar->slideshowimage);
				  $path =  JURI::base().'components/com_enewsletter/images/article_images/'.$path; 
  				  if($path != ''){
  						$ar->image =  $path;
  					}else{
  						$ar->image = '';
  					}          
				  } else{
					 $ar->image = '';
				}
        //Create article image  path end
				  
				$ar->description = $ar->description.'<br/>'.$titlelink;
				$j++;

		}//for
    
     
		
    // Replace title, intro, trailer, disclosure placeholders from template start
		$footer = '';
		

    
    // Assigns form data in $data variable 
		$data['articles'] = $articles;
	
		
    // Get current task
		$data['task'] =  JRequest::getVar('task');
				
		// Set form data in session state start
		$app->setUserState("com_enewsletter.cid",$cid);			
		$app->setUserState("com_enewsletter.gid",$gid);    
    	$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);		
		$app->setUserState("com_enewsletter.data",$data);
		
		
		$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
		
		
		
		
		
    // Create mailer class object to send test mail
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
    
    // Check from address to send mail if selected
		if($from_email_address == ''){
			 $from_email_address =  $app->getUserState("com_enewsletter.advisoremail");
		  }
			$sender = array( 
				$from_email_address,
				$app->getUserState("com_enewsletter.advisorname")
				 );		
				  
			$mailer->setSender($sender);
			$recipient = $data['emailaddress'];		 
	
			$mailer->addRecipient($recipient);
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->setSubject($data['subject']);
			
			$mailer->setBody($content);
			
			/*
			echo $recipient;
			echo '<hr/>';
			echo $data['subject'];
			echo '<hr/>';
			echo $content;
			exit;
			*/
			
			$send = $mailer->Send();
	
			if ( $send !== true ) {
				$this->setMessage(JText::_('Error sending test email.'),'error');
			} else {
				$this->setMessage(JText::_('Test Mail Sent Successfully.'));
			}
			$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');			
	}
	
	/**
	 * Used to preview newsletter.
	 *
	 * @return void
	 */
	public function preview()
	{
		$app = JFactory::getApplication();
		
    // Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
		
    // Get all selected article's ids
		$articles_ids = JRequest::getVar('articles');
    	$cid = explode(',',$articles_ids);
    	$ids = implode('" , "',$cid);
    
    // Get ids of selected images from article list
    	$showimage_id = JRequest::getVar('showimages');
    	$showimage_ids = explode(',',$showimage_id);	
		
		
		
		
		//echo '<pre>';
		//var_dump($ids);
		//var_dump($showimage_ids);
		//exit;
			
		
    // Get all selected group's ids
		$gid =  JRequest::getVar('gid');		
		
		// Get buildnewsletter model.
		$model = $this->getModel();
		
    // Get all articles from com_fnc and com_fbc table using artilce ids
		$articles = $model->getArticle($ids);
		
    // Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
    // Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		
  
  	//  Create and replace content for selected articles start
   		 $j = 1;
		foreach($articles as $ar){
			  // Create article content and more link  start
			  $articlelink='';
			  
			  $custom_link_article = $advisordetails->custom_link_article;
			  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
			  
			  if($valid_format) {
				$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
			  }
			  else
			  {
				if($ar->type == 'Featured News') 
				{
				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
				}
				else if($ar->type == 'Financial Briefs')
				{
				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
				}
				else
				{
				  $articlelink='';
				}
				
			  }
			
			  // Set article link for more in description	
			  if ( $articlelink !='')      
			  {
				$titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
			  }
			  else
			  {
				$titlelink = '';
			  }
        		$articlebody = $ar->description.'<br/>'.$titlelink;
        	 // Create article content and more link  end
			  
			  if(trim($articlelink) ==''){
			  	$articlelink = 'javascript:void(0);';	
			  }
			  
			  $ar->articlelink =  $articlelink;
			  
			  
			  //Create article image  path start  
			  if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
				  $path = $this->create_image($ar->slideshowimage);
				  $path =  JURI::base().'components/com_enewsletter/images/article_images/'.$path; 
  				  if($path != ''){
  						$ar->image =  $path;
  					}else{
  						$ar->image = '';
  					}          
				  } else{
					 $ar->image = '';
				}
        //Create article image  path end
				  
				$ar->description = $ar->description.'<br/>'.$titlelink;
				$j++;
  		}//for
		
		
		//echo '<pre>';
		//print_r($articles);
		//exit;
    
    	// Store data  for preview
		$data['articles'] = $articles;		
		$data['showimageids']  = $showimage_ids;			
		$data['task'] =  JRequest::getVar('task');			
		
		//echo '<pre>';
		//print_r($data);
		//print_r($advisordetails);
		//exit;
		
		enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails);
		
		
		//echo '<pre>';
		//print_r($data);
		//exit;
		
		
		//Set form data in session state start
		$app->setUserState("com_enewsletter.cid",$cid);			
		$app->setUserState("com_enewsletter.gid",$gid);   
		$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);		
		$app->setUserState("com_enewsletter.data",$data);
				
		$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');

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
		
		// Get all selected article's ids
		$articles_ids = JRequest::getVar('articles');
    $cid = explode(',',$articles_ids);
    $ids = implode('" , "',$cid);
    
    // Get ids of selected images from article list
    $showimage_id = JRequest::getVar('showimages');
    $showimage_ids = explode(',',$showimage_id);		
		
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');
				    
    // Get Enewsletter model
		$emodel = $this->getModel('Enewsletter');
		
		// Get current user's enewsletter details
		$advisordetails = $emodel->getAdvisordetails();
		
		// Get buidnewsletter model.
		$model = $this->getModel();
		
		// Get all articles from com_fnc and com_fbc table using artilce ids
		$articles = $model->getArticle($ids);
		
		// Get all templates of newsletter type
    $templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
    
    // Get content from selected template file
    $NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
    
    //  Create and replace content for selected articles start
    $articleimgarray = array();
    $j  =1;
		foreach($articles as $ar){
      
      		 // Create article content and more link  start
			  $articlelink='';
			  
			  $custom_link_article = $advisordetails->custom_link_article;
			  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
			  
			  if($valid_format) {
				$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
			  }
			  else
			  {
				if($ar->type == 'Featured News') 
				{
				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
				}
				else if($ar->type == 'Financial Briefs')
				{
				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
				}
				else
				{
				  $articlelink='';
				}
				
			  }
			
			  // Set article link for more in description	
			  if ( $articlelink !='')      
			  {
				$titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
			  }
			  else
			  {
				$titlelink = '';
			  }
        		$articlebody = $ar->description.'<br/>'.$titlelink;
        	 // Create article content and more link  end
			  
			  if(trim($articlelink) ==''){
			  	$articlelink = 'javascript:void(0);';	
			  }
			  
			  $ar->articlelink =  $articlelink;
			  
			  
			  //Create article image  path start  
			  if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
				  $path = $this->create_image($ar->slideshowimage);
				  $path =  JURI::base().'components/com_enewsletter/images/article_images/'.$path; 
  				  if($path != ''){
  						$ar->image =  $path;
  					}else{
  						$ar->image = '';
  					}          
				  } else{
					 $ar->image = '';
				}
        //Create article image  path end
				  
				$ar->description = $ar->description.'<br/>'.$titlelink;
				$j++;

		}
    
	
		
		// Assigns all articles to $data variable
		$data['articles'] = $articles;
		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');
		


		

		
		// Set all selected articles id's to state 
		$app->setUserState("com_enewsletter.cid",$cid);	
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
    
    
    	// Set articles's image ids to state
    	$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);
		
		// Fetch api details
		$api=$app->getUserState("com_enewsletter.API");
		$APIKEY  = CONSTANT_APIKEY;
		$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");
		
		
				
		// Set weekly update mail content
		$footer = '';
    	$mailfooter = '<p>&nbsp;</p><p>'.$advisordetails->address1.' '.$advisordetails->address2.', <br>'.$advisordetails->city.', '.$advisordetails->state.' - '.$advisordetails->zip.'</p>' ;
    
	
	
		$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
		
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
		
		
		
		//echo $content;
		//echo '<hr/>';
		//echo $data['intro'];		
		//exit;
	

		$orgid = $app->getUserState("com_enewsletter.orgid");
			
		// Assigns data in $data variable to save
    	$wdata['orgid'] = $orgid;
   		$wdata['title'] = $data['subject'];
		$wdata['subject'] = $data['subject'];
		$wdata['intro'] = $data['intro'];
		$wdata['trailer'] = '';
		$wdata['mass_email_content'] ='';	
		$wdata['type'] = 'enewsletter';
		$wdata['ctype'] = 'Newsletter';
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
			$result = $model->updateNewsletter($wdata, $cid, $gid, $showimage_ids);
		}else{		
			$result = $model->saveNewsletter($wdata, $cid, $gid, $showimage_ids);
		}
		$result= 1;
		
		
		// If record is updated/saved it will send mail for approval notification
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
      $app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);			
			$app->setUserState("com_enewsletter.data",$data);
		
			$this->setMessage(JText::_('Enewsletter sent for approval successfully.'));
		}else{
			$this->setMessage(JText::_('Could not save Enewsletter.'),'error');
		}

		$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');

	}
  
  /**
	 * Used to create  resized image for articles .
	 * @param $name string (filename)
	 * @return string
	 */
  function create_image($name){
        $img = file_get_contents($name);
        $orgname = basename($name); 

        $newname_array = explode('.',$orgname);
        $imagename =    $newname_array[0].'_'.time().'.jpg';
        $path = JPATH_SITE.'/administrator/components/com_enewsletter/images/article_images/'.$imagename;

        $im = imagecreatefromstring($img);
        
        $size = getimagesize($name);

        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $newwidth = 475;
            $newheight = 475/$ratio;
        }
        else {
            $newwidth = 475*$ratio;
            $newheight = 475;
        }
        
        $width = imagesx($im);
        
        $height = imagesy($im);
        
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        
        $result = imagejpeg($thumb,$path, 99); //save image as jpg
        
        imagedestroy($thumb);
        
        imagedestroy($im);
        
        if($result == 1){
            return $imagename;
        }else{
            return '';
        }
        
        
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