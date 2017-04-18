<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Savedemail model.
 *
 */
class EnewsletterModelSavedemail extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 */ 
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'article_id', 'a.article_id',
				'article_name', 'a.article_title',
				'description', 'a.description',
				'created', 'a.created',
			);
		}

		parent::__construct($config);
	}
	

	

	/**
	 * Get all saved active email 
	 *
	 * @return  array  An array of saved emails
	 *
	 */
		public function getEmails(){
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$api=$app->getUserState("com_enewsletter.API");
			$userid = $app->getUserState("com_enewsletter.User_ID");
			$query = "SELECT e.*,u.id as userid,u.name FROM #__enewsletter as e INNER JOIN #__users as u on u.id = e.edited_by where e.is_active = '1' order by e.dte_modified desc";
			$db->setQuery($query);  
			$emails = $db->loadObjectList();
      

      foreach($emails as $e){
        $groupquery = "SELECT * FROM #__enewsletter_groups where e_id =".$e->id."";
        $db->setQuery($groupquery);  
			  $groups = $db->loadObjectList();
        if(empty($groups)){
          $e->group = 'N';
        }else{
          $e->group = 'Y';
        }
      }
			return $emails;
		}

	
}
