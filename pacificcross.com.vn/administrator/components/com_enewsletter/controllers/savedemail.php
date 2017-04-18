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
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;
/**
 * Savedemail controller class.
 */
class EnewsletterControllerSavedemail extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EnewsletterControllerSavedemail
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
	 * Used to load all data based on saved email type and redirect to appropriate screen(page).
	 *
	 * @return void
	 */
	public function edit(){
		
		$app = JFactory::getApplication();
		
		// Get selected mail id
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		// Get Buildnewsletter model	
		$model = $this->getModel('Buildnewsletter');    
		
		// Fetch mail data from newsletter table using selected mail id
		$result = $model->getNewsletter($data['id']);
		
		/*echo '<pre>';
		print_r($result);
		exit;*/

		// Assign all data in $emaildata variable to display in appropriate page
		$emaildata = array();
		$emaildata['id'] = $result->id;
		$emaildata['title'] = $result->title;
		$emaildata['subject'] = $result->subject;
		$emaildata['intro'] = $result->intro;
		$emaildata['trailer'] = $result->trailer;
		$emaildata['mass_email_content'] = $result->mass_email_content;
		$emaildata['type'] = $result->type;
		$emaildata['email_id'] = $result->email_id;
   		$emaildata['content'] =  $result->content;
    	$emaildata['from'] =  'savedemail';
		#HT
		$emaildata['approval_status'] = $result->approval_status;
    
		
		// Get all groups of selected mail
		$ngroups = $model->getNewsletterGroups($data['id']);
		
		// Get all articles of selected mail
		$narticles = $model->getNewsletterArticles($data['id']);
		
		// Assign all groups ids in $groups array variable
		$groups = array();
		foreach($ngroups as $n){
			$groups[]  = $n->group_id;
		}
		
		// Assign all articles ids in $articles array variable
		$articles = array();
    	$show_images = array();
		foreach($narticles as $a){
			$articles[] = $a->article_id;
      		if($a->show_image == 1)
      		$show_images[] = $a->article_id;
		} 
		
		$stask =  JRequest::getVar('stask');
		$app->setUserState("com_enewsletter.opengrouptab",$stask);

		// Set selected mail data in state	
		$app->setUserState("com_enewsletter.data",$emaildata);
		$app->setUserState("com_enewsletter.cid",$articles);
		$app->setUserState("com_enewsletter.gid",$groups);
    	$app->setUserState("com_enewsletter.showimage_ids",$show_images);
    	$app->setUserState("com_enewsletter.sentfrom",'savedemail');
		
		// Redirect to appropriate page by checking mail type
     	if($result->type == 'enewsletter'){
  			$this->setRedirect('index.php?option=com_enewsletter&view=buildnewsletter');
  		}else if($result->type == 'massemail'){
  			$this->setRedirect('index.php?option=com_enewsletter&view=massemail');
  		}else if($result->type == 'weeklyupdate'){
  			$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
  		}
		
	}	
		
	
	/**
	 * Used to delete selected mails from enewsletter table
	 *
	 * @return void
	 */
	public function delete(){
	
		$app = JFactory::getApplication();
		
		// Get all selected email ids
		$email_id =  JRequest::getVar('email_id','post');
		
		// Get Buildnewsletter model
		$model = $this->getModel('Buildnewsletter');

		foreach($email_id as $e){

			$result = $model->inactiveNewsletter($e);			
		
		}							
		
		$this->setMessage(JText::_('Data Removed Successfully.'));
    	$this->setRedirect('index.php?option=com_enewsletter&view=savedemail');


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
