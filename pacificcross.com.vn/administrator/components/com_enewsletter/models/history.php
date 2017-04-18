<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * History model.
 */
class EnewsletterModelHistory extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
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
	 * Method to get all history data of sent mails.
	 *
	 * @return  array of mail history
	 *
	 */
		public function getHistory($ids = null){
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$userid = $app->getUserState("com_enewsletter.User_ID");
			$api=$app->getUserState("com_enewsletter.API");
			
			if($ids != ''){
				$query = "SELECT h.*,u.id as userid,u.name FROM #__enewsletter_history as h INNER JOIN #__users as u on u.id = h.user_id where h.id in (".$ids.") order by h.id desc";
			}else{
				$query = "SELECT h.*,u.id as userid,u.name FROM #__enewsletter_history as h INNER JOIN #__users as u on u.id = h.user_id order by h.id desc";
			}
			
			$db->setQuery($query);  
			$emails = $db->loadObjectList();
			return $emails;
		}
		
		/**
		 * Method to save sent mail data in history
		 *
		 * @param  array $data all data of sent mail
		 *
		 * @return  void
		 *
		 */
		public function saveHistory($data = array()){

			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$userid = $app->getUserState("com_enewsletter.User_ID");
			$insertquery = "INSERT INTO #__enewsletter_history(id,user_id,title,campaign_title,subject,content,e_id,email_id,api_type,dte_send) 
					   VALUES('',".intval($userid).",".$db->quote($data['title']).",'".$data['campaign_title']."',".$db->quote($data['subject']).",".$db->quote($data['content']).", ".$data['e_id'].", '".$data['email_id']."','".$data['api_type']."',now())";
			$db->setQuery($insertquery);
			$result =  $db->query();
			$id = $db->insertid();
		
		}

	
}
