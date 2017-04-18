<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Addtemplate model.
 *
 */
class EnewsletterModelAddtemplate extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 */ 
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	

	/**
	 * Get and set email template in user state  (session)
	 *
	 * @return  void
	 *
	 */
	public function getTemplate($id = ''){
        if($id == ''){
          $id = JRequest::getVar('id');
        }
        $app = JFactory::getApplication();
  			$db = JFactory::getDBO();
  			$query = "SELECT * from #__email_templates where id = $id";
  			$db->setQuery($query);  
  			$email = $db->loadObjectList();
        $email =  get_object_vars($email[0]);
        if ( $email['filename']== 'weekly.html' || $email['filename']== 'weeklyupdateright.html' || $email['filename']== 'apologize.html' || $email['filename']== 'enewsletter_threecol.html' || $email['filename']== 'youtubethem.html' || $email['filename']== 'enewsletter_site2.html' || $email['filename']== 'enewsletter.html'   ) {
            $email['filename'] = str_replace('.html','_defaults.html', $email['filename']);            
        }
        $template = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$email['filename']);
        $email['content'] = $template;
        $app->setUserState("com_enewsletter.data",$email);
     }
     	
}
