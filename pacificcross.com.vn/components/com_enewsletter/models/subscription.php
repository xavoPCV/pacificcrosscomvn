<?php
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

// Include XML2Array library to convert xml result into array
require_once JPATH_SITE.'/administrator/components/com_enewsletter/XML2Array.php';

/**
 * Subscription model class.
 */
class EnewsletterModelSubscription extends JModel
{
    /**
    * Gets site's advisorsetting details
    * @return array Advisor setting details
    */
    function getDetails()
    {
       // Get a db connection.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = 'Select * from #__advisorsettings order by id desc limit 1';
		$db->setQuery($query);
		$advisordetails = $db->loadObjectList();
		return $advisordetails[0];
    }
	
	/**
    * Gets latest sent enewsletter
    * @return array Details of latest sent enewsletter
    */
	function getLatestNewsletter(){
		// Get a db connection.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = 'Select * from #__enewsletter_history as eh inner join #__enewsletter as en on en.id = eh.e_id where en.type = "enewsletter" order by en.id desc limit 1 ';
		$db->setQuery($query);
		$newsletter = $db->loadObjectList();
		return $newsletter[0];
	}
  
        
        public function getArticle($ids = ''){
	
		  $db = JFactory::getDBO();
     		 $query = 'SELECT * FROM (SELECT *,"Featured News" as type FROM #__apifnc union SELECT *,"Financial Briefs" as type FROM #__apifbc)test where test.article_id in("'.$ids.'") order by test.created desc';
     	 $db->setQuery($query);  
     		$article = $db->loadObjectList();
		  return $article;
	}
        public function saveNewsletter($data = array(), $cid = array(), $gid = array(), $sid = array()){

		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$userid = $app->getUserState("com_enewsletter.User_ID");
		
		
		#HT - Compliance
		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php')) {
			
			require_once(JPATH_ADMINISTRATOR.'/components/com_contentmanager/classes/screenmaster/function.php');
			JLoader::register('ContentManagerHelper', JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
			$site_id = ContentManagerHelper::getSiteID();
			
			$data_new = $data;

			$old_data_row = $data_new;
			//force un-published when addNew
			$data['approval_status'] = 'PND';
			$isNew = true;
			
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
    * Get and set weekly update content usin web API 
    * @return void
    */
  	public function setWeeklyUpdateContent($advisordetails = array()){
        //================= start get weekly update content ===================//
          $app = JFactory::getApplication();
  				$authtoken = $app->getUserState("com_enewsletter.authenticationtoken");
  				if(!$authtoken){
  					$apiuser = API_USERNAME;
  					$apipassword = API_PASSWORD;
  					$api_url = "https://contentengine.advisorproducts.com/service/Authenticate.aspx?userid=".$apiuser."&password=".$apipassword;
  					$ch = curl_init();
  					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  					curl_setopt($ch,CURLOPT_URL,$api_url);
  					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  					curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  					curl_setopt($ch, CURLOPT_POST, false);
  					$apidetails = curl_exec($ch);				
  					$apidetail = XML2Array::createArray($apidetails);
  					if($apidetail['authenticate']['status'] == 0){
  						$app->setUserState("com_enewsletter.authenticationtoken",$apidetail['authenticate']['authtoken']);
  						$authtoken = $apidetail['authenticate']['authtoken'];	
  					}
  				}			
  					
  								
  				$submit_url = "https://contentengine.advisorproducts.com/service/GetWeeklyUpdateList.aspx?authtoken=".$authtoken."&overrideselections=1";
  			
  				$ch = curl_init();
  				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  				curl_setopt($ch,CURLOPT_URL,$submit_url);
  				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  				curl_setopt($ch, CURLOPT_POST, false);
  				$wdata = curl_exec($ch);				
  				$wdata = XML2Array::createArray($wdata);  				
  				$article_url = "https://contentengine.advisorproducts.com/service/".$wdata['rss']['channel']['item'][0]['link'];
  				
  				$ch = curl_init();
  				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  				curl_setopt($ch,CURLOPT_URL,$article_url);
  				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  				curl_setopt($ch, CURLOPT_POST, false);
  				$adata = curl_exec($ch);			
  				$adata = XML2Array::createArray($adata);   	

    			$weklyupdatecontent = $adata['rss']['channel']['item']['description'];    
				
				
				
				$path_to_quote = $advisordetails['path_quote'];
				if ($path_to_quote == ''){
					 $path_to_quote = 'javascript:void(0)';
				}
				$weklyupdatecontent =preg_replace('/<!--#QUOTES#-->/',$path_to_quote,$weklyupdatecontent);
				
				
				
				$custom_link_article = $advisordetails['custom_link_article'];
				
				$valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
				
				if (!$valid_format) $custom_link_article = JURI::root().'index.php?option=com_apicontent&view=fnclist&id={articleid}';
				
				if ($custom_link_article) {
					$contentenginestr = 'http://contentsrv.advisorsites.com/showarticle.asp?domain=&article=';
					
					$lcustom_url = str_replace( '{articleid}','',$custom_link_article ) ; 
					
					$wucontent_tmp = str_replace($contentenginestr ,$lcustom_url,  $weklyupdatecontent  )  ;
					
					$wucontent_tmp = str_replace('&show=one','' , $wucontent_tmp  )  ;
					
					
					
					$weklyupdatecontent =   $wucontent_tmp ;
					
				}//if
				
				
				
  				$app->setUserState("com_enewsletter.Weeklyupdatedesc",$weklyupdatecontent);
				$app->setUserState("com_enewsletter.Weeklyupdatearticleid",$adata['rss']['channel']['item']['articleid']);		
				 
				
				/*echo $weklyupdatecontent;
				echo '<hr/>';
				echo $app->getUserState("com_enewsletter.Weeklyupdatedesc");
				exit;*/
          		
  				curl_close($ch);
  				//================= end get weekly update content ===================//		
  }
}