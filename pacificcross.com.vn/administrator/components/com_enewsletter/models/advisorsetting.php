<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.access.access');

/**
 * Advisorsetting model.
 */
class EnewsletterModelAdvisorsetting extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	*/
	
	public function getTable($type = 'User', $prefix = 'JTable', $config = array())
	{
		$table = JTable::getInstance($type, $prefix, $config);

		return $table;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed	Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
    $app = JFactory::getApplication();
		$id = $app->getUserState("com_enewsletter.User_ID");
    if($id){
      $pk = $id;
    }
		$result = parent::getItem($pk);

		// Get the dispatcher and load the users plugins.
		$dispatcher	= JDispatcher::getInstance();
		JPluginHelper::importPlugin('user');

		// Trigger the data preparation event.
		$results = $dispatcher->trigger('onContentPrepareData', array('com_enewsletter.advisor', $result));

		return $result;
	}
	
	/**
	 * Method to get advisor details.
	 *
	 * @return  array of advisor details
	 *
	 */
	public function getAdvisordetails()
	{
	
		  $db = JFactory::getDBO();
     	$query = 'SELECT * FROM #__advisorsettings';
     	$db->setQuery($query);  
     	$advisordetails = $db->loadObjectList();
		  return $advisordetails[0];
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_enewsletter.advisor', 'advisor', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_enewsletter.edit.advisor.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// TODO: Maybe this can go into the parent model somehow?
		// Get the dispatcher and load the users plugins.
		$dispatcher	= JDispatcher::getInstance();
		JPluginHelper::importPlugin('user');

		// Trigger the data preparation event.
		$results = $dispatcher->trigger('onContentPrepareData', array('com_enewsletter.profile', $data));

		// Check for errors encountered while preparing the data.
		if (count($results) && in_array(false, $results, true))
		{
			$this->setError($dispatcher->getError());
		}

		return $data;
	}

	/**
	 * Override JModelAdmin::preprocessForm to ensure the correct plugin group is loaded.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, $group);
	}
  
  /**
   *  Used to remove API details and weekly update selected lists
   *  
   * @return void      
   */     
  public function cleardetails(){
       $db = JFactory::getDBO();
       
		$user_edit_id = (int)JFactory::getUser()->id;
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$sql_log = "insert into #__advisorsettings_log select *, $user_edit_id, now(), '$user_ip' from #__advisorsettings";
		$db->setQuery($sql_log);
		$db->execute();
	   
	   
	   $updatequery = "UPDATE #__advisorsettings set 
  			newsletter_api = '',
  			api_login_name = '',
  			api_key = '',
        	from_email = '',
        	auto_update = '',
        	from_name = '' ";			
			$db->setQuery($updatequery);
			$result =  $db->query();
      
      $deletegroupquery = "DELETE FROM #__weeklyupdate_group";        
		  $db->setQuery($deletegroupquery);
		  $result =  $db->query();
  }
	
	/**
	 * Method to save/update advisor details.
	 *
	 * @param   array    $data      advisor details data.
	 *
	 * @return  void
	 *
	 */
	public function saveall($data) {

	  	//echo '<pre>';
		//print_r($data);
        //exit;
		
		$db = JFactory::getDBO();
   		$query = 'SELECT * FROM #__advisorsettings';
   		$db->setQuery($query);   
   		$advisordetails = $db->loadObject();		
		 // check auto
               
		$sql = "SELECT *
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE 
					TABLE_NAME = '".$db->getPrefix()."advisorsettings'
					AND COLUMN_NAME = 'id'
					AND DATA_TYPE = 'int'
					AND EXTRA like '%auto_increment%'
				";
		$db->setQuery($sql);  
		$checkauto = $db->loadObject();
		if ($checkauto == ''){
			$sql1 = "
				ALTER TABLE `".$db->getPrefix()."advisorsettings` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
				";
				$db->setQuery($sql1);  
				$db->query();
		}//if
         
                
		if(!($data['send_newsletter'])){
			$data['send_newsletter'] = 'N';
		}
		
		if(!($data['send_update'])){
			$data['send_update'] = 'N';
		}
		
		if(!($data['show_address'])){
			$data['show_address'] = 'N';
		}
		
		if(!($data['allow_to_use_name'])){
			$data['allow_to_use_name'] = 'N';
		}
		
		if(!($data['customer_website'])){
			$data['customer_website'] = 'N';
		}
    
    	if(!($data['auto_update'])){
			$data['auto_update'] = 'N';
		}
		
		
		$autoweeklysend = JRequest::getInt('autoweeklysend', 1);
		
		$semiautoemail = JRequest::getVar('semiautoemail', NULL);
		
		if (isset($autoweeklysend)) {
			
			$com_params = JComponentHelper::getParams('com_enewsletter');
			$com_params->set('autoweeklysend', intval($autoweeklysend));
			$semiautoemail = filter_var($semiautoemail, FILTER_VALIDATE_EMAIL)?trim($semiautoemail):'';
			$com_params->set('semiautoemail', $semiautoemail);
			
			$dataparams = $com_params->toString();
			
			$query = $db->getQuery(true);
			$query->update('#__extensions');
			$query->set('`params` = '.$db->quote($dataparams));
			$query->where('`element` = "com_enewsletter"');
			$db->setQuery($query);
			$db->execute();
			
			//echo $query->dump();
			//exit;
			
		}//if
		
		
		//exit;
		

             
		if(empty($advisordetails)){
			$insertquery = "INSERT INTO #__advisorsettings (id,user_id,alternative_domain,newsletter_api,api_login_name,api_key,api_token,firm,url,path_quote,custom_link_article,allow_to_use_name,customer_website,subscribers_limit,send_newsletter,send_update,show_address,update_subject,auto_update,newsletter_disclosure,mass_email_disclosure,weekly_update_newsletter,weekly_update_intro,join_list_instruction,join_list_email,privacy_policy,archive_cc_list,from_email,from_name,address1,address2,phone,city,zip,state,country,logo,social_links,`bannerimg`,second_address1,second_address2,second_phone,second_city,second_zip,second_state,second_country,address_position) 
				   VALUES('0','0','', '".$data['email_campaign_key']."','".$data['api_login_name']."','".$data['api_key']."','".$data['api_token']."','".$data['firm']."','".$data['url']."','".$data['path_quote']."','".$data['custom_link_article']."','".$data['allow_to_use_name']."','".$data['customer_website']."','".$data['subscribers_limit']."','".$data['send_newsletter']."','".$data['send_update']."','".$data['show_address']."',".$db->quote($data['update_subject']).",'".$data['auto_update']."',".$db->quote($data['newsletter_disclosure']).",".$db->quote($data['mass_email_disclosure']).",".$db->quote($data['weekly_update_newsletter']).",".$db->quote($data['weekly_update_intro']).",".$db->quote($data['join_list_instruction']).",".$db->quote($data['join_list_email']).",".$db->quote($data['privacy_policy']).",'".$data['archive_cc_list']."','".$data['from_email']."','".$data['from_name']."','".$data['address1']."','".$data['address2']."','".$data['phone']."','".$data['city']."','".$data['zip']."','".$data['state']."','".$data['country']."','".$data['logo']."','".$data['social_links']."','".$data['bannerimg']."','".$data['second_address1']."','".$data['second_address2']."','".$data['second_phone']."','".$data['second_city']."','".$data['second_zip']."','".$data['second_state']."','".$data['second_country']."','".intval($data['address_position'])."')";

				  $db->setQuery($insertquery);
				  $result =  $db->query();
		}else{
		  
			$user_edit_id = (int)JFactory::getUser()->id;
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$sql_log = "insert into #__advisorsettings_log select *, $user_edit_id, now(), '$user_ip' from #__advisorsettings";
			$db->setQuery($sql_log);
			$db->execute();
			
			//echo $sql_log;
			//exit;
			
			//var_dump($data['iduser']);
			//exit;
			
			if ( isset($data['iduser']) ){
				$query = "SELECT count(id) FROM #__advisorsettings where user_id = '".$data['iduser']."'";
				$db->setQuery($query);  
				$checkid = $db->loadResult();
				
				
				if ( $checkid ) {
					$query = "UPDATE #__advisorsettings set
									newsletter_api = '".$data['email_campaign_key']."',
									api_login_name = '".$data['api_login_name']."',
									api_key = '".$data['api_key']."',
									api_token = '',
									firm = '".$data['firm']."',
									url = '".$data['url']."',
									path_quote= '".$data['path_quote']."',
									custom_link_article= '".$data['custom_link_article']."',
									allow_to_use_name= '".$data['allow_to_use_name']."',
									customer_website= '".$data['customer_website']."',
									subscribers_limit= '',
									send_newsletter= '".$data['send_newsletter']."',
									send_update= '".$data['send_update']."',
									show_address= '".$data['show_address']."',
									update_subject= ".$db->quote($data['update_subject']).",
									auto_update= '".$data['auto_update']."',     
									newsletter_disclosure= ".$db->quote($data['newsletter_disclosure']).",
									mass_email_disclosure= ".$db->quote($data['mass_email_disclosure']).",
									weekly_update_newsletter= ".$db->quote($data['weekly_update_newsletter']).",
									weekly_update_intro= ".$db->quote($data['weekly_update_intro']).",
									join_list_instruction= ".$db->quote($data['join_list_instruction']).",
									join_list_email= ".$db->quote($data['join_list_email']).",
									privacy_policy= ".$db->quote($data['privacy_policy']).",
									archive_cc_list = '".$data['archive_cc_list']."',
									from_email = '".$data['from_email']."',
									from_name = '".$data['from_name']."',
									address1 = '".$data['address1']."',
									address2 = '".$data['address2']."',
									phone = '".$data['phone']."',
									city = '".$data['city']."',
									zip = '".$data['zip']."',
									state = '".$data['state']."',
									country = '".$data['country']."',
									social_links = '".$data['social_links']."',
									second_address1 = '".$data['second_address1']."',
									second_address2 = '".$data['second_address2']."',
									second_phone = '".$data['second_phone']."',
									second_city = '".$data['second_city']."',
									second_zip = '".$data['second_zip']."',
									second_state = '".$data['second_state']."',
									second_country = '".$data['second_country']."',
									address_position = '".intval($data['address_position'])."'";

					if ($data['logo']) $query .= ", `logo` = '".$data['logo']."'";
					if ($data['bannerimg']!==NULL) $query .= ", `bannerimg` = '".$data['bannerimg']."'";
					
					
					
					$query .= " where `user_id` = '".(int)$data['iduser']."'";

					$db->setQuery($query);
					$result =  $db->query();
					
					//echo $updatequery;
					//exit;
					
				}else{
                                
					$query = "INSERT INTO #__advisorsettings (id,user_id,alternative_domain,newsletter_api,api_login_name,api_key,api_token,firm,url,path_quote,custom_link_article,allow_to_use_name,customer_website,subscribers_limit,send_newsletter,send_update,show_address,update_subject,auto_update,newsletter_disclosure,mass_email_disclosure,weekly_update_newsletter,weekly_update_intro,join_list_instruction,join_list_email,privacy_policy,archive_cc_list,from_email,from_name,address1,address2,phone,city,zip,state,country,logo,social_links,`bannerimg`,second_address1,second_address2,second_phone,second_city,second_zip,second_state,second_country,address_position) 
				   VALUES('','".$data['iduser']."','', '".$data['email_campaign_key']."','".$data['api_login_name']."','".$data['api_key']."','".$data['api_token']."','".$data['firm']."','".$data['url']."','".$data['path_quote']."','".$data['custom_link_article']."','".$data['allow_to_use_name']."','".$data['customer_website']."','".$data['subscribers_limit']."','".$data['send_newsletter']."','".$data['send_update']."','".$data['show_address']."',".$db->quote($data['update_subject']).",'".$data['auto_update']."',".$db->quote($data['newsletter_disclosure']).",".$db->quote($data['mass_email_disclosure']).",".$db->quote($data['weekly_update_newsletter']).",".$db->quote($data['weekly_update_intro']).",".$db->quote($data['join_list_instruction']).",".$db->quote($data['join_list_email']).",".$db->quote($data['privacy_policy']).",'".$data['archive_cc_list']."','".$data['from_email']."','".$data['from_name']."','".$data['address1']."','".$data['address2']."','".$data['phone']."','".$data['city']."','".$data['zip']."','".$data['state']."','".$data['country']."','".$data['logo']."','".$data['social_links']."','".$data['bannerimg']."','".$data['second_address1']."','".$data['second_address2']."','".$data['second_phone']."','".$data['second_city']."','".$data['second_zip']."','".$data['second_state']."','".$data['second_country']."','".intval($data['address_position'])."')";
                     $db->setQuery($query);
                     $db->query();
				}//if checkid
			}//if isset
		}//if advisordetails
		
		
		//die($query);
		
	}//func

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		// Initialise variables;
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('user.id');
		$user		= JUser::getInstance($pk);

		$my = JFactory::getUser();

		if ($data['block'] && $pk == $my->id && !$my->block)
		{
			$this->setError(JText::_('COM_ENEWSLETTER_USERS_ERROR_CANNOT_BLOCK_SELF'));
			return false;
		}

		// Make sure that we are not removing ourself from Super Admin group
		$iAmSuperAdmin = $my->authorise('core.admin');
		if ($iAmSuperAdmin && $my->get('id') == $pk)
		{
			// Check that at least one of our new groups is Super Admin
			$stillSuperAdmin = false;
			$myNewGroups = $data['groups'];
			foreach ($myNewGroups as $group)
			{
				$stillSuperAdmin = ($stillSuperAdmin) ? ($stillSuperAdmin) : JAccess::checkGroup($group, 'core.admin');
			}
			if (!$stillSuperAdmin)
			{
				$this->setError(JText::_('COM_ENEWSLETTER_USERS_ERROR_CANNOT_DEMOTE_SELF'));
				return false;
			}
		}
		
		$data['groups'] = array('9');

		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}

		// Store the data.
		if (!$user->save())
		{
			$this->setError($user->getError());
			return false;
		}

		$this->setState('user.id', $user->id);

		return true;
	}  

	

}
