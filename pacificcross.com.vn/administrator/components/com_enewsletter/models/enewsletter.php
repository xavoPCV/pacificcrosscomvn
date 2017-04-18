<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.access.access');

require_once JPATH_SITE.'/administrator/components/com_enewsletter/XML2Array.php';

/**
 * Enewsletter model.
 */
class EnewsletterModelEnewsletter extends JModelAdmin
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
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_enewsletter.advisor', 'user', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the detals of advisor.
	 *
	 * @return  array of details
	 *
	 */
	public function getAdvisordetails()
	{		
		$db = JFactory::getDBO();
    $query = 'SELECT * FROM #__advisorsettings';
    $db->setQuery($query);  
    $advisordetails = $db->loadObjectList();
    //echo '<pre>';
    //print_r($advisordetails);   exit;
		return $advisordetails[0];
	} 
  
  /**
	 * Method to get the detals of advisor.
	 *
	 * @return  array of details
	 * 
	 * @param id int (user id)      
	 *
	 */
  public function getAdvisordata($id = null){
		
		  $db = JFactory::getDBO();
      $query = 'SELECT * FROM #__users where id='.$id; 
     	$db->setQuery($query);  
     	$advisordetails = $db->loadObjectList();
		  return $advisordetails[0];
  } 
	
	
	/**
	 * Method to get selected groups for weeklyupdate.
	 *
	 * @return  array of weekly update groups
	 *
	 */
	public function getWeeklyupdateGroups(){
      $app = JFactory::getApplication();
		  $advisorid = $app->getUserState("com_enewsletter.User_ID");
      $api = $app->getUserState("com_enewsletter.API");
		  $db = JFactory::getDBO();
     	$query = "SELECT * FROM #__weeklyupdate_group";
     	$db->setQuery($query);  
     	$weeklyupdateGroups = $db->loadObjectList();
		  return $weeklyupdateGroups;
	}
  
  public function getAllAdvisorByGroupId(){
    $db = JFactory::getDBO();
    $query = "SELECT * FROM #__user_usergroup_map where group_id= 9 ";    
   	$db->setQuery($query);  
   	$advisordetails = $db->loadObjectList();
	  return $advisordetails;
  }
	
	
	public function getUnderComplianceFlag(){
	
		$app = JFactory::getApplication();
		$config =       new JConfig();
		$extoption = array();
		/*$extoption['driver']   = 'mysqli';
		$extoption['host']     = 'hq-sqldev1';
		$extoption['user']     = 'centcomtestdba';    
		$extoption['password'] = 'JoomlaTesting123!@#';     
		$extoption['database'] = 'centcom';
		$extoption['prefix']   = 'api_';
		$Externaldb = & JDatabase::getInstance($extoption );*/
		
		$extoption['driver']   = $config->dbtype;
		$extoption['host']     =  $config->host;
		$extoption['user']     = $config->user;    
		$extoption['password'] = $config->password;   
		$extoption['database'] = 'centcom';
		$extoption['prefix']   = 'api_';
		$Externaldb = & JDatabase::getInstance($extoption );
    
  

		$EXT_query      = "SELECT * FROM ".$extoption['prefix']."apisc WHERE site_database='".trim($config->db)."'";
    //$EXT_query      = "SELECT * FROM ".$extoption['prefix']."apisc WHERE site_database='sgfinancialadvisorproductscom'";
    //$EXT_query = "SELECT now()";
		$Externaldb->setQuery($EXT_query);
		$orgdata  = $Externaldb->loadObjectList();

    $OrGID = $orgdata[0]->site_org_id;

		if(empty($OrGID) || $OrGID==0 || $OrGID==1000000){
			JError::raiseWarning(500, "Could not get orgid :::" . $OrGID. "::: ". $EXT_query);
			return false;
		}else{
    
      $app->setUserState("com_enewsletter.advisorcentcomdetails",$orgdata[0]);
      $app->setUserState("com_enewsletter.orgid",$orgdata[0]->site_org_id);	
		  
    
			$sqlserver_user = sqlserver_user;
			$sqlserver_pass = sqlserver_pass;
			$sqlserver_db =  sqlserver_db;
			$sqlserver_host = sqlserver_host; 
  		
			$connectionInfo = array("UID" => $sqlserver_user, "PWD" => $sqlserver_pass, "Database"=>$sqlserver_db);	
			$serverName = $sqlserver_host;
			$conn = sqlsrv_connect( $serverName, $connectionInfo);
      if( $conn )
      {
        
        $sql="{ call dbo.GetSiteEmailComplianceSettings(".$OrGID.")}";		
  			$sqldata = sqlsrv_query($conn, $sql);
  			$compliancedata = sqlsrv_fetch_array( $sqldata, SQLSRV_FETCH_ASSOC);
        	//echo '<pre>';
        	//print_r($compliancedata);exit;
  			$app->setUserState("com_enewsletter.advisormssqldetails",$compliancedata);
  			$app->setUserState("com_enewsletter.complianceflag",$compliancedata['complianceemail']);
        	//$app->setUserState("com_enewsletter.complianceflag",1);
      	
      }
      else
      {
           echo "Connection could not be established.\n";
           die( print_r( sqlsrv_errors(), true));
      }	      			
			
		}
	}
	
	public function create_image($name){
        $img = file_get_contents($name);
        $orgname = basename($name); 

        $newname_array = explode('.',$orgname);
        $imagename =    $newname_array[0].'_'.time().'.jpg';
        
		
		if ( !file_exists(JPATH_SITE.'/media/com_enewsletter/images/')) {
			mkdir(JPATH_SITE.'/media/com_enewsletter/images/');
		}//if
		
		
		$path = JPATH_SITE.'/media/com_enewsletter/images/'.$imagename;

        $im = imagecreatefromstring($img);
        
        $size = getimagesize($name);

        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $newwidth = 600;
            $newheight = 600/$ratio;
        }
        else {
            $newwidth = 600*$ratio;
            $newheight = 400;
        }
        
        $width = imagesx($im);
        
        $height = imagesy($im);
        
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        
        $result = imagejpeg($thumb,$path, 99); //save image as jpg
        
        imagedestroy($thumb);
        
        imagedestroy($im);
        
        if($result == 1){
            return $imagename;
        }else{
            return '';
        }        
        
  }
  
  	public function setWeeklyUpdateContent(){
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
  				$path_to_quote = $app->getUserState("com_enewsletter.path_quote");
				  if($path_to_quote == ''){
					 $path_to_quote = 'javascript:void(0)';
				  }
				  $weeklyupdatecontent =preg_replace('/<!--#QUOTES#-->/',$path_to_quote,$adata['rss']['channel']['item']['description']);
         
			  
			  
			  $custom_link_article = $app->getUserState("com_enewsletter.custom_link_article");
			  
			  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
				
			  if (!$valid_format) $custom_link_article = JURI::root().'index.php?option=apicontent&view=fnclist&id={articleid}';
			  
			  
			  
			  if ( $custom_link_article  )  {
				  // if custom url is set then 
				  //replace the http://contentsrv.advisorsites.com/showarticle.asp?domain=&article=3022&show=one     
				  //with the correct link to the article in the site
				  
				  $contentenginestr = 'http://contentsrv.advisorsites.com/showarticle.asp?domain=&article=';
				  $lcustom_url = str_replace( '{articleid}','',$app->getUserState("com_enewsletter.custom_link_article") ) ; 
				  $wucontent_tmp = str_replace($contentenginestr ,$lcustom_url,  $weeklyupdatecontent  )  ;
				  $wucontent_tmp = str_replace('&show=one','' , $wucontent_tmp  )  ;
				  
	
				 $weeklyupdatecontent =   $wucontent_tmp ;
			 }//if
			 
		$sqlserver_user = sqlserver_user;
		$sqlserver_pass = sqlserver_pass;
		$sqlserver_db =  "ArticleContent";
		$sqlserver_host = sqlserver_host; 
		
		$connectionInfo = array("UID" => $sqlserver_user, "PWD" => $sqlserver_pass, "Database"=>$sqlserver_db);	
		$serverName = $sqlserver_host;
		$conn = sqlsrv_connect( $serverName, $connectionInfo);		    


          		$dom=new SmartDOMDocument();
    			$dom->loadHTML($weeklyupdatecontent);
    		  
			  
			  $mock = new SmartDOMDocument();
    			$body = $dom->getElementsByTagName('body')->item(0);
    			foreach ($body->childNodes as $child){
    				$mock->appendChild($mock->importNode($child, true));
    			}  
				
				
				// Final HTML Content
    			$weeklyupdatecontent = $mock->saveHTML(); 
				
				$newarr=array();
				$anchor = $dom->getElementsByTagName('a');
		
				// Need article id of all anchors available in WeeklyContent
				foreach($anchor as $a){			
		
					$href = $a->getAttribute('href');							
					$href=parse_url($href);							
					
					parse_str($href['query'], $query);															
					if(!empty($query['id'])){
						array_push($newarr, $query['id']);	
					}										
				}
				
				
				//var_dump($newarr);
				//exit;
		
				for ($h=0; $h < count($newarr) ; $h++) { 
					
					$sql="SELECT * FROM article_featured_image where article_id=".$newarr[$h];		
					$sqldata = sqlsrv_query($conn, $sql);
					$images = sqlsrv_fetch_array( $sqldata, SQLSRV_FETCH_ASSOC);		        	  								
		
					$img = $this->create_image('https://contentengine.advisorproducts.com/'.$images['slideshow_image']);
					$final_img = JURI::root().'media/com_enewsletter/images/'.$img;
						
					// Replace images over commented word
					$weeklyupdatecontent = preg_replace('/<!--#APICONTENTIMAGE#-->/', "<img style='margin:8px;border:1px #000 solid;' width='100%' src='".$final_img."'", $weeklyupdatecontent, 1);
				}	

          
  				$app->setUserState("com_enewsletter.Weeklyupdatedesc",$weeklyupdatecontent);
          		$app->setUserState("com_enewsletter.Weeklyupdatearticleid",$adata['rss']['channel']['item']['articleid']);		
          		
  				curl_close($ch);
  				//================= end get weekly update content ===================//		
  	}

	function genLogo($logo) {
	
		if (!$logo) return '';
		
		$rootURL = JURI::root(false);
		$rootURL = str_replace('https:','http:', $rootURL);
		
		if ( !file_exists(JPATH_ROOT.'/media/com_enewsletter/logo/'.$logo) ) {
			return '';
		} else {
			return '<img src="'.$rootURL.'media/com_enewsletter/logo/'.$logo.'" border="0" />';
		}//if
	 
	}//func
	
	function genBanner($bannerimg) {
	
		if (!$bannerimg) return '';
		
		$rootURL = JURI::root(false);
		$rootURL = str_replace('https:','http:', $rootURL);
		
		if ( !file_exists(JPATH_ROOT.'/media/com_enewsletter/banner/'.$bannerimg) ) {
			return '';
		} else {
			return '<img src="'.$rootURL.'media/com_enewsletter/banner/'.$bannerimg.'" border="0" />';
		}//if
	 
	}//func
	
	function genSocialLinks($social_links) {
	
		if (!$social_links) return '';
		
		$social_links_obj = json_decode($social_links);
		
		$rootURL = JURI::root(false);
		$rootURL = str_replace('https:','http:', $rootURL);
		
		if ( !is_object($social_links_obj) ) {
			return '';
		} else {
			$social = array();
			foreach ($social_links_obj as $keyy => $vv) {
				$vv = trim($vv);
				if ($vv) $social[] = "<span><a href='$vv' target='_blank'><img src='".$rootURL.'media/com_enewsletter/'.$keyy.".png' border='0' /></a></span>";
			}//for
			if (count($social)) {
				$social[] = "<br/>connect with us";
				return implode(' ',$social);
			}
		}//if
		return '';
	}//func
	
	function genFirmText($advisordetails) {
	
		$info = array();
		
		$line1 = array();
	
		if ($advisordetails->firm || $advisordetails->address1) $line1[] = $advisordetails->firm.' '.$advisordetails->address1;
		if ($advisordetails->address2) $line1[] = $advisordetails->address2;
		if ($advisordetails->city) $line1[] = $advisordetails->city;
		if ($advisordetails->state || $advisordetails->zip) $line1[] = $advisordetails->state.' '.$advisordetails->zip;
		if ($advisordetails->country) $line1[] = $advisordetails->country;
		
		if (count($line1)) $info[] = implode(', ', $line1);
		
		//echo '<pre>';
		
		
		$line2 = array();
		
		if ($advisordetails->phone) $line2[] = $advisordetails->phone;
		if ($advisordetails->from_email) $line2[] = '<a href="mailto:'.$advisordetails->from_email.'">'.$advisordetails->from_email.'</a>';
		
		if (count($line2)) $info[] = implode(' ', $line2);
		
		
	
		$line3 = array();
		
		if ($advisordetails->url) $line3[] = '<a href="'.$advisordetails->url.'" target="_blank">'.$advisordetails->url.'</a>';
		
		
		if (count($line3)) $info[] = implode($line3);
		
		
		
		$html = '';
		if (count($info)) {
			$html = '<p>'.implode('<br/>', $info).'</p>';
		}//if
		
		
		//echo $html;
		//exit;
		
		return $html;
	
	}//func



}
