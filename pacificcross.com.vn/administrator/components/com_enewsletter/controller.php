<?php 
//ini_set('display_errors','1');
//error_reporting(E_ALL);
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

 
// import Joomla controller library
jimport('joomla.application.component.controller');

require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');

require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey





use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Services\ListService;
use Ctct\Exceptions\CtctException;
/**
 * E-Newsletter Component Controller
 */
class EnewsletterController extends JController
{
	
	
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	public function display()
	{       
		$app = JFactory::getApplication();
		$loggeduser = JFactory::getUser();  
    
		$app->setUserState("com_enewsletter.User_ID",$loggeduser->id);
    	$app->setUserState("com_enewsletter.testemail",$loggeduser->email);
    
		#HT
		$isAdmin = $loggeduser->get('isRoot'); //Added RIMBA
		

		
    /*  REmoved RIMBA
    if (in_array('8',$loggeduser->groups)) {
       $app->setUserState("com_enewsletter.usertype",'admin');
    }else if(in_array('9',$loggeduser->groups)){
      $app->setUserState("com_enewsletter.usertype",'advisor');
    }   
	*/
	if ($isAdmin) {
		$app->setUserState("com_enewsletter.usertype",'admin');
	}//if
    
		$view = JRequest::getVar('view','','GET');
    $adminviews = array('managetemplate','addtemplate');
    
    if(in_array('9',$loggeduser->groups)) {
       if(in_array($view,$adminviews)) {
          $app->enqueueMessage(JText::_('Not Authorized.'),'warning');       
          $app->redirect('index.php?option=com_enewsletter');
       }
    }
    
    $views = array('massemail','buildnewsletter','weeklyupdate');

		$model = parent::getModel($name = 'Enewsletter', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true));
    
    /* Check for proper advisor id if supplied in url start */
    $errcnt = 0;

    
    $compliancedata = $app->getUserState("com_enewsletter.advisormssqldetails");
    if(empty($compliancedata)){
		#HT
      //$model->getUnderComplianceFlag(); REMOVED RIMBA
    }  
    
    $advisordata =  $model->getAdvisordetails();

    $app->setUserState("com_enewsletter.advisorname",$advisordata->from_name);
    $app->setUserState("com_enewsletter.advisoremail",$advisordata->from_email);
    $app->setUserState("com_enewsletter.testemail",$loggeduser->email);
    $app->setUserState("com_enewsletter.weeklyupdategroup",$wgroups); 
    $app->setUserState("com_enewsletter.ACCESS_TOKEN",$advisordata->api_key);
	  $app->setUserState("com_enewsletter.API",$advisordata->newsletter_api);        
	  $app->setUserState("com_enewsletter.Weekly_subject",$advisordata->update_subject);
	  $app->setUserState("com_enewsletter.Weekly_intro",$advisordata->weekly_update_intro);
    $app->setUserState("com_enewsletter.custom_link_article",$advisordata->custom_link_article);
    $app->setUserState("com_enewsletter.path_quote",$advisordata->path_quote);
    $app->setUserState("com_enewsletter.newsletter_default_template",$advisordata->newsletter_default_template);
    $app->setUserState("com_enewsletter.weeklyupdate_default_template",$advisordata->weeklyupdate_default_template);
    $app->setUserState("com_enewsletter.massemail_default_template",$advisordata->massemail_default_template);
    $template_array = array($advisordata->newsletter_default_template,$advisordata->weeklyupdate_default_template,$advisordata->massemail_default_template);
    $app->setUserState("com_enewsletter.template_array",$template_array);
    //echo $advisordata->newsletter_api;exit;
	//Make sure connection to email campaign system is set up 
    if(in_array($view,$views) && $advisordata->newsletter_api == ''){
      $app->enqueueMessage(JText::_('Connection to Email Campaign system has not yet been set up. Please go to "Setup" and Get API Key.'),'warning');       
      $app->redirect('index.php?option=com_enewsletter');
    } 
	//Make sure that the default email has been selected 
    if(in_array($view,$views) && $advisordata->from_email == ''){
      $app->enqueueMessage(JText::_('Default From email has not yet been selected. Please go to "Setup" and select a Default From Email address that you have verified in Constant Contact.'),'warning');       
      $app->redirect('index.php?option=com_enewsletter');
    } 
    
    // Get and set email templates for Setup, E-Newsletter, Massemail and Weekly update screen start
    $templatemodel = parent::getModel($name = 'Managetemplate', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true));
    $templateviews = array('advisorsetting','massemail','buildnewsletter','weeklyupdate'); 
    $templatearray = array(); 
    $templatefilearray = array();  
    if(in_array($view,$templateviews)){
          if($view == 'advisorsetting' || $view == 'weeklyupdate'){
              $emailtemplatedata = $templatemodel->getAlltemplates('weeklyupdate');
          }else if($view == 'buildnewsletter'){
              $emailtemplatedata = $templatemodel->getAlltemplates('newsletter');
          }else if($view == 'massemail'){
              $emailtemplatedata = $templatemodel->getAlltemplates('massemail');
          }
                    
          foreach($emailtemplatedata as $et){
              $templatearray[$et->id] =  $et->description; 
              $templatefilearray[$et->id] =  $et->filename;
          }
          //echo '<pre>';
          //print_r($templatearray);exit;          
          if($view == 'advisorsetting' || $view == 'weeklyupdate'){
              $app->setUserState("com_enewsletter.weeklyupdatetemplates",$templatearray);
              $app->setUserState("com_enewsletter.weeklyupdatetemplatefiles",$templatefilearray);
          }else if($view == 'buildnewsletter'){
              $app->setUserState("com_enewsletter.newslettertemplates",$templatearray);
              $app->setUserState("com_enewsletter.newslettertemplatefiles",$templatefilearray);
          }else if($view == 'massemail'){
              $app->setUserState("com_enewsletter.massemailtemplates",$templatearray);
              $app->setUserState("com_enewsletter.massemailtemplatefiles",$templatefilearray);
          }
    }
    // Get and set email templates for Setup, E-Newsletter, Massemail and Weekly update screen end
   
    
    $groups = $model->getWeeklyupdateGroups(); 
    $wgroups = array();
    if(!empty($groups)){
      foreach($groups as $g){
         $wgroups[] = $g->group_id;
      }
    }
    $app->setUserState("com_enewsletter.weeklyupdategroup",$wgroups); 
    //$app->redirect('index.php?option=com_enewsletter');   

		parent::display();

		
	}
  
  public function getemaillists(){
      $api = JRequest::getVar('apitype', '','post', '');
      $apikey = JRequest::getVar('apikey', '','post', '');
      $app = JFactory::getApplication();
      $wgroups = $app->getUserState("com_enewsletter.weeklyupdategroup"); 
      $APIKEY  = CONSTANT_APIKEY;
      if($api == 'C'){
           $cc = new ConstantContact($APIKEY);
            try{
            	$groups = $cc->getLists($apikey);
              //echo '<pre>';
              //print_r($groups);exit;
              foreach ($groups as $g){ 
                  //if($g->status == 'ACTIVE')  {
                    if(in_array($g->id,$wgroups)){
                      $checked = 'checked';
                    }else{
                      $checked = '';
                    }
                    echo '<input name="lids[]" type="checkbox" value="'.$g->id.'" '.$checked.' />'.$g->name.'<br>';
                  //}
              }
            }catch (CtctException $ex) {
              echo 'error';
            	die();
            }
      }else if($api == 'M') {

          $mapi = new MCAPI(trim($apikey));
          $groups = $mapi->lists();
          
          if ($mapi->errorCode){
          	//echo $mapi->errorMessage;
            echo 'error';
          	die();
          }else{
              $groups = $groups['data'];
              foreach($groups as $g){
                  if($g['stats']['member_count'] != 0){ 
                      if(in_array($g['id'],$wgroups)){
                        $checked = 'checked';
                      }else{
                        $checked = '';
                      }
                       echo '<input name="lids[]" type="checkbox" value="'.$g["id"].'" '.$checked.' />'.$g["name"].'<br>';
                   }
              }
          } 
      }else if($api == 'G') {
	  	
		require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/jsonRPCClient.php');
		
		//$api_key = '061fd9f5575b47f07c6bbc8a050a8828';
		# API 2.x URL
		$api_url = 'http://api2.getresponse.com';
		$client = new jsonRPCClient($api_url);
		
		$details = $client->get_campaigns($apikey);
		
		
		
		if ($details && !$details['error'] && $details['result']) {
			
			$groups = $details['result'];
            foreach($groups as $gr_id => $g){
				if(in_array($gr_id, $wgroups)){
                   $checked = 'checked';
                }else{
                   $checked = '';
                }
                echo '<input name="lids[]" type="checkbox" value="'.$gr_id.'" '.$checked.' />'.$g["name"].'<br>';
			}//for
			
		}//if
	  }else if($api == 'I') {
	  	
		if ($apikey) {
			
			$gr_id = 'infusionsoft';
			
			if(in_array($gr_id, $wgroups)){
			   $checked = 'checked';
			}else{
			   $checked = '';
			}
			
			echo '<input name="lids[]" type="checkbox" value="'.$gr_id.'" '.$checked.' />infusion soft<br>';
			
		}//if
	  }//if
      exit;
  }
  
  public function getverifiedemaillist(){
      $api = JRequest::getVar('apitype', '','request', '');
      $apikey = JRequest::getVar('apikey', '','request', '');
      $app = JFactory::getApplication();
      
	  $from_email = $app->getUserState("com_enewsletter.advisoremail"); 
      
	  $APIKEY  = CONSTANT_APIKEY;
      if($api == 'C'){
           $cc = new ConstantContact($APIKEY);
            try{
            	$emails = $cc->getVerifiedEmailAddresses($apikey);
              echo '<select id="verified_emails" name="verified_emails" onchange="fill_email(this)" style="width:290px;"  >';  
               if(count($emails) > 1){
                echo '<option value="" >Select List</option>';
              }
              foreach($emails as $e){
                if($e->status == 'CONFIRMED'){
                  if($from_email == $e->email_address){
                    $selected = "selected";
                  }else{
                    $selected = '';
                  }
                   
                    echo '<option value="'.$e->email_address.'" '.$selected.'  >'.$e->email_address.'</option>';
                }
              }
              echo '</select>'; 
            }catch (CtctException $ex) {
              echo 'error';
            	die();
            }
      }else if($api == 'M') {
	  
	  	  $mapi = new MCAPI(trim($apikey));
		  
		  
		   //var_dump( $mapi );
		  
		  
          $emails = $mapi->getVerifiedDomains();
		  
		  
		  //var_dump( $mapi );
           
          if ($mapi->errorCode){
            echo 'error';
          	die();
          }else{
          
              $email_name = '';
              if($from_email){
                  $email_names = explode('@',$from_email);
                  $email_name = $email_names[0];
              }
              
              echo '<input type="text" name="jform[verified_email_name]" value="'.trim($email_name).'" style="float:none;margin:5px 0 0 0;" id="verified_email_name" />@';
              echo '<select id="verified_emails" name="verified_emails" style="float:right;"  onchange="fill_email(this)" >';
              if(count($emails) > 1){
                echo '<option value="" >Select List</option>';
              }
              foreach($emails as $e){    
                         
                if($e['status'] == 'verified') {
                     
                  if(trim($email_names[1]) == $e['domain']){
                      $selected = "selected";
                    }else{
                      $selected = '';
                    }
                    echo '<option value="'.$e['domain'].'" '.$selected.' >'.$e['domain'].'</option>';
                    
                }                
              } 
              echo '</select>';   
          } 
      } else if ($api == 'G') {
	  	
		require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/jsonRPCClient.php');
		
		
		# API 2.x URL
		$api_url = 'http://api2.getresponse.com';
		$client = new jsonRPCClient($api_url);
		
		$details = $client->get_account_info($apikey);
		
		if ($details && !$details['error'] && $details['result']) {
			echo '<select id="verified_emails" name="verified_emails" onchange="fill_email(this)" style="width:290px;"  >';  
                  if($from_email == $details['result']['from_email']){
                    $selected = "selected";
                  }else{
                    $selected = '';
                  }//if
              echo '<option value="'.$details['result']['from_email'].'" '.$selected.'  >'.$details['result']['from_email'].'</option>';
              echo '</select>'; 
		}//if
	} else if ($api == 'I') {	
		
		if ($apikey) {
			$vall = $from_email;
			echo '<input type="text" id="verified_emails" name="verified_emails" onchange="fill_email(this)" style="width:250px;" value="'.$vall.'" />';  
		}//if
			
			
						

	}//if
	  
	  
	  exit;
  }//func
  
  


}