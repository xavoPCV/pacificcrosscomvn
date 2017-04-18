<?php
ini_set('max_execution_time', 0);
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Buildnewsletter model.
 */
class EnewsletterModelBuildnewsletter extends JModelList
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
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	 
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.active');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.group_id');
		$id .= ':'.$this->getState('filter.range');

		return parent::getStoreId($id);
	}

	 
	 
	/**
	 * Method to get all selected articles from list.
	 *
	 * @param   string  $ids  All article ids.
	 *
	 * @return  array of articles
	 *
	 */
	public function getArticle($ids = ''){
	
		  $db = JFactory::getDBO();
     		 $query = 'SELECT * FROM (SELECT *,"Featured News" as type FROM #__apifnc union SELECT *,"Financial Briefs" as type FROM #__apifbc)test where test.article_id in("'.$ids.'") order by test.created desc';
     	 $db->setQuery($query);  
     		$article = $db->loadObjectList();
		  return $article;
	}
	
	/**
	 * Method to get all articles.
	 *
	 * @param   string  $con condition for all articles and latest articles.
	 *
	 * @return  array of articles
	 *
	 */
	public function getArticles($con = null){
		
		$db = JFactory::getDBO();
		
		if($con != ''){		
     		$query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test where test.created < CURDATE() and test.created > (CURDATE() - INTERVAL 60 DAY) order by test.created desc";
		}else{
			$query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc";
		}
     	$db->setQuery($query);  
     	$article = $db->loadObjectList();

		return $article;
	}

	/**
	 * Method to save mail data.
	 *
	 * @param   array  $data all mail data.
	 *
	 * @param   array  $cid all selected articles ids.
	 *
	 * @param   array  $gid all selected groups ids.
	 * 
	 * @param array $sid all selected article's image ids.      
	 *
	 * @return  id of inserted mail
	 *
	 */
	public function saveNewsletter($data = array(), $cid = array(), $gid = array(), $sid = array()){

		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$userid = $app->getUserState("com_enewsletter.User_ID");
		
		
		#HT - Compliance
		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php')) {
			
			require_once(JPATH_ADMINISTRATOR.'/components/com_contentmanager/classes/screenmaster/function.php');
			JLoader::register('ContentManagerHelper', JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
			$site_id = ContentManagerHelper::getSiteID();
			
			$this->doComplianceConfig = ContentManagerHelper::getSiteConfig($site_id);
			if ($this->doComplianceConfig->enewsletter_compliance) {
				$data_new = $data;
				$old_data_row = $data_new;
				//force un-published when addNew
				$data['approval_status'] = 'PND';
				$isNew = true;
			} else {
				$site_id = 0;
			}//if
		}//if
		
    
		$insertquery = "INSERT INTO #__enewsletter (id,user_id,edited_by,title,subject,intro,trailer,mass_email_content,type,api_type,email_sent_status,email_id,is_active,approval_status,approval_email_id,content,weekly_update_content,dte_created,dte_modified) 
				   VALUES('',".$userid.",".$userid.",".$db->quote($data['title']).",".$db->quote($data['subject']).",".$db->quote($data['intro']).", ".$db->quote($data['trailer']).", ".$db->quote($data['mass_email_content']).",'".$data['type']."','".$data['api_type']."','".$data['email_sent_status']."','".$data['email_id']."','".$data['is_active']."','".$data['approval_status']."','".$data['approval_email_id']."',".$db->quote($data['content']).",".$db->quote($data['weekly_update_content']).",now(),now())";
    $db->setQuery($insertquery);
		$result =  $db->query();

		$id = $db->insertid();

		if($id){
			foreach($cid as $c){
				if(in_array($c,$sid)){
					$show_image = '1';
				}else{
				   $show_image = '';
				}
				$artcleinsertquery = "INSERT INTO #__enewsletter_article (id,e_id,article_id,show_image) 
				   VALUES('',".$id.",'".$c."', '".$show_image."')";
				$db->setQuery($artcleinsertquery);
				$result =  $db->query();
			}
		}
		
		if($id){
			foreach($gid as $g){
				$groupinsertquery = "INSERT INTO #__enewsletter_groups (id,e_id,group_id) 
				   VALUES('',".$id.",'".$g."')";
				$db->setQuery($groupinsertquery);
				$result =  $db->query();
			}
		}//if
		
		
		#HT - Compliance process
		if ( $id && $site_id ) {
			
			$user = JFactory::getUser();
			
			$params = JComponentHelper::getParams('com_contentmanager');
			
			//centcom process
			$option = array(); //prevent problems
			$option['driver']   = $params->get('centcom_driver');            // Database driver name
			$option['host']     = $params->get('centcom_host');    // Database host name
			$option['user']     = $params->get('centcom_user');       // User for database authentication
			$option['password'] = $params->get('centcom_password');   // Password for database authentication
			$option['database'] = $params->get('centcom_database');      // Database name
			$option['prefix']   = $params->get('centcom_prefix');             // Database prefix (may be empty)
			$db_ex = & JDatabase::getInstance( $option );
				
			$query = $db_ex->getQuery(true);
			$query->select('count(*)');
			$query->from('#__content_client');
			$query->where('site_id = '.$site_id);
			$query->where('object_id = '.$id);
			$query->where('element = "enewsletter"');
			$db_ex->setQuery($query);
			$found = $db_ex->loadResult();
			
			$mod_param = new stdClass;
			$mod_param->isNew = $isNew;
			$mod_param_s = json_encode($mod_param);
					
			if ($found) {
				$query = $db_ex->getQuery(true);
				$query->update('#__content_client');
				$query->set('`title` = '.$db_ex->quote($old_data_row['subject']));
				$query->set('`new_title` = '.$db_ex->quote($data_new['subject']));
				$query->set('`content` = '.$db_ex->quote($old_data_row['content']));
				$query->set('`new_content` = '.$db_ex->quote($data_new['content']));
				$query->set('`new_params` = '.$db_ex->quote($mod_param_s));
				$query->set('`date_approved` = NULL');
				$query->set('`status` = 0');
				$query->set('`request_user_id` = "'.(int)$user->id.'"');
				$query->set('date_submit = now()');
				$query->set('element = "enewsletter"');
				$query->where('site_id = '.$site_id);
				$query->where('object_id = '.$id);
				$db_ex->setQuery($query);
				$db_ex->query();
			} else {
				#echo 'a:'.$query->dump();
				$query = $db_ex->getQuery(true);
				$query->insert('#__content_client');
				$query->set('`title` = '.$db_ex->quote($old_data_row['subject']));
				$query->set('`new_title` = '.$db_ex->quote($data_new['subject']));
				$query->set('`content` = '.$db_ex->quote($old_data_row['content']));
				$query->set('`new_content` = '.$db_ex->quote($data_new['content']));
				$query->set('`new_params` = '.$db_ex->quote($mod_param_s));
				$query->set('`request_user_id` = "'.(int)$user->id.'"');
				$query->set('site_id = '.$site_id);
				$query->set('object_id = '.$id);
				$query->set('date_submit = now()');
				$query->set('element = "enewsletter"');
				$db_ex->setQuery($query);
				$db_ex->query();
				#echo 'a:'.$query->dump();
				#exit;
			}//if found
			
			$query = $db_ex->getQuery(true);
			$query->insert('#__content_client_log');
			$query->set('`element` = "enewsletter"');
			$query->set('`object_id` = '.(int)$id);
			$query->set('`title` = '.$db->Quote($old_data_row['subject']));
			$query->set('`site_id` = '.(int)$site_id);
			$query->set('`status` = 0');
			
			if ($isNew) 
				$query->set('`notes` = "add"');
			else
				$query->set('`notes` = "edit"');
			
			
			$query->set('`user_name` = '.$db->Quote($user->name));
			$query->set('`user_id` = '.(int)$user->id);
			$query->set('`date_created` = '.$db->Quote(JFactory::getDate()->toMySQL()));
			$db_ex->setQuery($query);
			$db_ex->query();
			
			ContentManagerHelper::sendNotification($user, $site_id, $db_ex, $id, 'enewsletter', $old_data_row['subject'], $isNew );
			
		}//if site_id
		
		return $id;
		
	}//func
	
  /**
	 * Method to save selected weklyupdate list 
	 *
	 * @param   array  $cid all selected articles ids.    
	 *
	 * @return  void
	 *
	 */
	public function setWeeklyUpdateGroup($cid){

    $app = JFactory::getApplication();
    $advisorid = $app->getUserState("com_enewsletter.User_ID");
    $api = $app->getUserState("com_enewsletter.API");
		$db = JFactory::getDBO();
		$deletegroupquery = "DELETE FROM #__weeklyupdate_group";    
    
		$db->setQuery($deletegroupquery);
		$result =  $db->query();
    
		foreach($cid as $c){
    
			$groupinsertquery = "INSERT INTO #__weeklyupdate_group (id,user_id,api_type,group_id) 
			   VALUES(NULL,".$advisorid.",'".$api."','".$c."')";
			$db->setQuery($groupinsertquery);
			$result =  $db->query();
		}
	}
	
	/**
	 * Method to update mail data.
	 *
	 * @param   array  $data all mail data.
	 *
	 * @param   array  $cid all selected articles ids.
	 *
	 * @param   array  $gid all selected groups ids.
	 * 
	 * @param array $sid all selected article's image ids.       
	 *
	 * @return  id of inserted mail
	 *
	 */
	public function updateNewsletter($data = array(), $cid = array(), $gid = array(), $sid = array()){
  
      	$app = JFactory::getApplication();
		$userid = $app->getUserState("com_enewsletter.User_ID");
		  
		  
		 // print_r($data);
		  //exit;
		  
		  
		#HT - Compliance
		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php') ) {

			require_once(JPATH_ADMINISTRATOR.'/components/com_contentmanager/classes/screenmaster/function.php');
			JLoader::register('ContentManagerHelper', JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
			$site_id = ContentManagerHelper::getSiteID();
			
			
			$this->doComplianceConfig = ContentManagerHelper::getSiteConfig($site_id);
			if ($this->doComplianceConfig->enewsletter_compliance) {
			
				$data_new = $data;
	
				$old_data_row = '';
				
				$db	=& JFactory::getDBO();
				
				if ( $data['id'] ) {
					//get local Content
					$query = $db->getQuery(true);
					$query->select('*');
					$query->from('#__enewsletter');
					$query->where('id = '.$data['id']);
					$db->setQuery($query);
					$old_data_row = $db->loadAssoc();
					$data['approval_status'] = $old_data_row['approval_status'];
					$isNew = false;
				}//if
				
			} else {
				$site_id = 0;
			}//if	
		}//if
		
		$db = JFactory::getDBO();
		
		
			$updatequery = "UPDATE #__enewsletter set
							edited_by = ".intval($userid).",    
							title = ".$db->quote($data['title']).",
							intro = ".$db->quote($data['intro']).",
							trailer = ".$db->quote($data['trailer']).",
							mass_email_content = ".$db->quote($data['mass_email_content']).",
							email_sent_status  = '".$data['email_sent_status']."',
							email_id  = '".$data['email_id']."',
							approval_status  = '".$data['approval_status']."',
							approval_email_id  = '".$data['approval_email_id']."',
							weekly_update_content = ".$db->quote($data['weekly_update_content']).",
							dte_modified = now() 
							where id = ".(int)$data['id'];	
		
			$db->setQuery($updatequery);
			$result =  $db->query();
			
			$deletearticlequery = 'DELETE from #__enewsletter_article where e_id = '.$data['id'].'';
			$db->setQuery($deletearticlequery);
			$result =  $db->query();
			
			$deletegroupquery = 'DELETE from #__enewsletter_groups where e_id = '.$data['id'].'';
			$db->setQuery($deletegroupquery);
			$result =  $db->query();
			
			if($data['id']){
        
				if(!empty($cid)){
        
					foreach($cid as $c){
						if(in_array($c,$sid)){
                			$show_image = '1';
           				 }else{
               				$show_image = '';
            			}
    					$artcleinsertquery = "INSERT INTO #__enewsletter_article (id,e_id,article_id,show_image) 
    				   				VALUES('',".$data['id'].",'".$c."', '".$show_image."')";
    					$db->setQuery($artcleinsertquery);
    					$result =  $db->query();
					}
				}
			}
			
			if($data['id']){
				if(!empty($gid)){
					foreach($gid as $g){
						$groupinsertquery = "INSERT INTO #__enewsletter_groups (id,e_id,group_id) 
						   VALUES('',".$data['id'].",'".$g."')";
						$db->setQuery($groupinsertquery);
						$result =  $db->query();
					}
				}
			}
			
			#HT - Compliance process
			$id = $data['id'];
			
			//print_r($id);
			//exit;
			
			
			if ( $id && $site_id ) {
				
				$user = JFactory::getUser();
				
				$params = JComponentHelper::getParams('com_contentmanager');
				
				//centcom process
				$option = array(); //prevent problems
				$option['driver']   = $params->get('centcom_driver');            // Database driver name
				$option['host']     = $params->get('centcom_host');    // Database host name
				$option['user']     = $params->get('centcom_user');       // User for database authentication
				$option['password'] = $params->get('centcom_password');   // Password for database authentication
				$option['database'] = $params->get('centcom_database');      // Database name
				$option['prefix']   = $params->get('centcom_prefix');             // Database prefix (may be empty)
				$db_ex = & JDatabase::getInstance( $option );
					
				$query = $db_ex->getQuery(true);
				$query->select('count(*)');
				$query->from('#__content_client');
				$query->where('site_id = '.$site_id);
				$query->where('object_id = '.$id);
				$query->where('element = "enewsletter"');
				$db_ex->setQuery($query);
				$found = $db_ex->loadResult();
				
				$mod_param = new stdClass;
				$mod_param->isNew = $isNew;
				$mod_param_s = json_encode($mod_param);
						
				if ($found) {
					$query = $db_ex->getQuery(true);
					$query->update('#__content_client');
					$query->set('`title` = '.$db_ex->quote($old_data_row['subject']));
					$query->set('`new_title` = '.$db_ex->quote($data_new['subject']));
					$query->set('`content` = '.$db_ex->quote($old_data_row['content']));
					$query->set('`new_content` = '.$db_ex->quote($data_new['content']));
					$query->set('`new_params` = '.$db_ex->quote($mod_param_s));
					$query->set('`date_approved` = NULL');
					$query->set('`status` = 0');
					$query->set('`request_user_id` = "'.(int)$user->id.'"');
					$query->set('date_submit = now()');
					$query->where('element = "enewsletter"');
					$query->where('site_id = '.$site_id);
					$query->where('object_id = '.$id);
					$db_ex->setQuery($query);
					$db_ex->query();
					
				} else {
					#echo 'a:'.$query->dump();
					$query = $db_ex->getQuery(true);
					$query->insert('#__content_client');
					$query->set('`title` = '.$db_ex->quote($old_data_row['subject']));
					$query->set('`new_title` = '.$db_ex->quote($data_new['subject']));
					$query->set('`content` = '.$db_ex->quote($old_data_row['content']));
					$query->set('`new_content` = '.$db_ex->quote($data_new['content']));
					$query->set('`new_params` = '.$db_ex->quote($mod_param_s));
					$query->set('`request_user_id` = "'.(int)$user->id.'"');
					$query->set('site_id = '.$site_id);
					$query->set('object_id = '.$id);
					$query->set('date_submit = now()');
					$query->set('element = "enewsletter"');
					$db_ex->setQuery($query);
					$db_ex->query();
					
				}//if found
				
				$query = $db_ex->getQuery(true);
				$query->insert('#__content_client_log');
				
				$query->set('`element` = "enewsletter"');
				$query->set('`object_id` = '.(int)$id);
				$query->set('`title` = '.$db->Quote($old_data_row['subject']));
				$query->set('`site_id` = '.(int)$site_id);
				$query->set('`status` = 0');
				
				if ($isNew) 
					$query->set('`notes` = "add"');
				else
					$query->set('`notes` = "edit"');
				
				$query->set('`user_name` = '.$db->Quote($user->name));
				$query->set('`user_id` = '.(int)$user->id);
				$query->set('`date_created` = '.$db->Quote(JFactory::getDate()->toMySQL()));
				$db_ex->setQuery($query);
				$db_ex->query();
				
				ContentManagerHelper::sendNotification($user, $site_id, $db_ex, $id, 'enewsletter', $old_data_row['subject'], $isNew );
				
			}//if site_id
		
			
		return $data['id'];
			
	}
	
	/**
	 * Method to get mail data .
	 *
	 * @param   string  $id id of mail.
	 *
	 * @return  array of email
	 *
	 */
	public function getNewsletter($id = null){
	              
		$db = JFactory::getDBO();
     	$query = 'SELECT * FROM #__enewsletter where id = '.$id;
     	$db->setQuery($query);  
     	$email = $db->loadObjectList();  
		return $email[0];
	}
	
	/**
	 * Method to get mail groups .
	 *
	 * @param   string  $id id of mail.
	 *
	 * @return  array of groups
	 *
	 */
	public function getNewsletterGroups($id = null){
	
		$db = JFactory::getDBO();
     	$query = 'SELECT * FROM #__enewsletter_groups where e_id = '.$id;
     	$db->setQuery($query);  
     	$ngroups = $db->loadObjectList();
		return $ngroups;
	}
	
	/**
	 * Method to get mail articles .
	 *
	 * @param   string  $id id of mail.
	 *
	 * @return  array of articles
	 *
	 */
	public function getNewsletterArticles($id){
	
		$db = JFactory::getDBO();
     	$query = 'SELECT * FROM #__enewsletter_article where e_id = '.$id;
     	$db->setQuery($query);  
     	$narticles = $db->loadObjectList();
		return $narticles;
	}
	
	/**
	 * Method to inactivate mail .
	 *
	 * @param   string  $id id of mail.
	 *
	 * @return  void
	 *
	 */
	public function inactiveNewsletter($id = null){
		
		$db = JFactory::getDBO();
		$updatequery = "UPDATE #__enewsletter set
			is_active = '0'
			where id = ".$id."";			
			$db->setQuery($updatequery);
			$result =  $db->query();
	
	}
	
	/**
	 * Method to update mail status.
	 *
	 * @param   string  $id id of mail.
	 *
	 * @param   string  $email_ids id of sent campaign.
	 *
	 * @return  void
	 *
	 */
	public function updateemail($id = null,$email_ids = null){
		$db = JFactory::getDBO();
		$updatequery = "UPDATE #__enewsletter set
			email_id = '".$email_ids."',
			email_sent_status = '1',
			dte_modified = now() where id = ".$id."";			
			$db->setQuery($updatequery);
			$result =  $db->query();
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);

		$query->from($db->quoteName('#__apifnc').' AS a');


		if ($this->getState('filter.search') !== '')
		{
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.article_title LIKE '.$token;

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}	
		
		$query->order($db->escape($this->getState('list.ordering', 'a.created')).' '.$db->escape($this->getState('list.direction', 'DESC')));
		
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_enewsletter');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.article_title', 'asc');


	}
	
  /**
	 * Method to send mail for approval if advisor is under compiance
	 *
	 * @param   string  $gid id of mail.
	 *
	 * @param   string  $data email data.
	 *
	 * @return  void
	 *
	 */
	public function sendToCompliance($data = array(), $gid = array()){
	
		$config = new JConfig();
		$loggeduser = JFactory::getUser();
			
		$sqlserver_user = sqlserver_user;
		$sqlserver_pass = sqlserver_pass;
		$sqlserver_db = sqlserver_db;
		$sqlserver_host = sqlserver_host;
	
		$connectionInfo = array("UID" => $sqlserver_user, "PWD" => $sqlserver_pass, "Database"=>$sqlserver_db);

		$serverName = $sqlserver_host;
		//$connectionInfo = array( "Database"=>"AdventureWorks");
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
		
		$orgid = $data['orgid'];
		$date = date('Y-m-d');
		$status = $data['approval_status'];
		$body =str_replace("'","''",$data['content']);
		$subject = $data['subject'];
		$attach = ' ';
		//$type = $data['type'];
		$type = $data['ctype'];
		$tolist = implode(',',$gid);
		$textbody = str_replace("'","''",$data['content']);
		$erz = (int)0;		
		$baseurl = JURI::base();
		$CompletedURL = str_replace("administrator/", "index.php?option=com_enewsletter&task=mailapproval", $baseurl);
		
		$sql="{ call dbo.insert_Email(".$orgid.",'".$date."','".$status."','".$body."','".$subject."','".$attach."','".$type."','".$tolist."','".$textbody."',".$erz.",'".$CompletedURL."')}";
		
		$stmt3 = sqlsrv_query($conn, $sql);
		
		if( $stmt3 === false )
		{
			 echo "Error in executing.\n";
			 die( print_r( sqlsrv_errors(), true));
		}
		
		sqlsrv_next_result($stmt3);
		$row = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
		$return_id = $row['EmailId'];
		
		return $return_id;

		
	}
  
  /**
	 * Method to update default template (newsletter/weekly update/massemail)
	 *
	 * @param   string  $field identification of email type of mail.
	 *
	 * @param   string  $id email template id.
	 *
	 * @return  void
	 *
	 */
  public function updateDefaultTemplate($id = '' ,$field = ''){
	$db = JFactory::getDBO();
	
	
	$user_edit_id = (int)JFactory::getUser()->id;
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$sql_log = "insert into #__advisorsettings_log select *, $user_edit_id, now(), '$user_ip' from #__advisorsettings";
	$db->setQuery($sql_log);
	$db->execute();
	
	$updatequery = "UPDATE #__advisorsettings set ".$field."_default_template = $id";	
	$db->setQuery($updatequery);
	$result =  $db->query();
  
  }
	
	
}
