<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

// Include mailchimp/constant contact library files
require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/class.csv.php';

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;

/**
 * History controller class.
 */
class EnewsletterControllerHistory extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EnewsletterControllerHistory
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
	public function getModel($name = 'History', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	

	/**
	 * Used to perform cancel action.
	 *
	 * @return void
	 */
	function cancel(){
		
		$app = JFactory::getApplication();
    $this->setRedirect('index.php?option=com_enewsletter');

	}

}
