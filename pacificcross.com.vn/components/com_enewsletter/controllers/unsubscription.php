<?php
// No direct access.
defined('_JEXEC') or die;

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
 * Unsubscription controller class.
 */
class EnewsletterControllerUnsubscription extends JController
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
	public function getModel($name = 'Unsubscription', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	
  /**
   * Used to get list of subscriber from email campaign system .
   *
   * @return void
   */	
  public function getsubscribedlist(){
    $email = JRequest::getVar('email');
    $app = JFactory::getApplication();	
	$model = $this->getModel();
	$details = $model->getDetails();
    $api = $details->newsletter_api;
    $TOKEN = $details->api_key;

    if($api == 'C'){
			$cc = new ConstantContact(CONSTANT_APIKEY); 
      
      try{
          $contact = $cc->getContactByEmail(trim($TOKEN),$email); 
          echo '<div>Lists</div>';
          $i = 0;
          if(!empty($contact->results)){
            foreach($contact->results[0]->lists as $l){
                $list = $cc->getList($TOKEN,$l->id); 
                if($list->status == 'ACTIVE') {
                    echo '<input type="checkbox" class="checkalls" name="cid[]" value="' . $list->id . '" />'.$list->name.'<br>';
                    $i++;
                }
            }
          }
          
          if($i == 0){
              echo 'No active list.';
          }
      }catch (CtctException $ex) {
				$ccerror = $ex->getErrors();
				echo $ccerror[0]['error_message'];
			}
      exit;   		
			
		}else if($api == 'M'){
			$mm = new MCAPI(trim($TOKEN)); 
			  $mlists = $mm->listsForEmail($email);
			  if ($mm->errorCode){
						echo $mm->errorMessage.'<br>';
					}	else{
				$lists = $mm->lists();
				$lists = $lists['data'];
				echo '<div>Lists</div>';
				foreach($lists as $l){
				  if(in_array($l['id'],$mlists)){
					  echo '<input type="checkbox" class="checkalls" name="cid[]" value="' . $l['id'] . '" />'.$l['name'].'<br>';
				  }
				}
			  }      
      
		}  exit;
  }
  
  
  /**
	 * Used to unsubcribe user from email campaign system .
	 *
	 * @return void
	 */
	public function unsubscribe(){
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
		// Check for email campaign and then unsubscribe user
		if($api == 'C'){
			$cc = new ConstantContact(CONSTANT_APIKEY);      
      $contact = $cc->getContactByEmail($TOKEN, $data['email']);
      $contact = $contact->results[0];
    	foreach($cid as $c){
         try {
    				$returnContact = $cc->deleteContactFromList($TOKEN, $contact, $c);
    				
    			} catch (CtctException $ex) {
    				$ccerror = $ex->getErrors();
    				$errormessage .= $ccerror[0]['error_message'];
    			}
      }			
			
		}else if($api == 'M'){

			$mm = new MCAPI(trim($TOKEN)); 
			foreach($cid as $c){
				$returnContact = $mm->listUnsubscribe($c, $data['email']);	
				if ($mm->errorCode){
					$errormessage .= $mm->errorMessage.'<br>';
				}		
			}

		}
    
    	if(!empty($returnContact)){
			   $this->setMessage(JText::_('Successfully Unsubscribed.'));
  		}
				
		$current_url = JURI::getInstance();
		$this->setRedirect($current_url);

	}
}
