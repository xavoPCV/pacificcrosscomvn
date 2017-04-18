<?php
defined('_JEXEC') or die('Restricted access');

// Include XML2Array library to convert xml result into array
require_once JPATH_SITE.'/administrator/components/com_enewsletter/XML2Array.php';
use Joomla\Image\Image;
//ini_set('max_execution_time', 0);


class EnewsletterController extends JControllerLegacy
{
	
        var $max_size_upload = 20971520; // 20MB in bytes
	var $file_exts = array( 'flv', 'mp4',  'mp3' , 'pdf' , 'docx' , 'pptx', 'xlsx' );  
        
        
           public function updateeblog(){
            
		$model = $this->getModel();
		/* Get database details start */
		$config = new JConfig();
		$host = $config->host;
		$user = $config->user;
		$password = $config->password;
		$database = $config->db;
		$dbprefix = $config->dbprefix;
		$error = '';
		$sqlerror = '';
                $webapierror = '';
		$errorcnt = 0;
		$db = JFactory::getDbo();
	  	$shost = sqlserver_host;  
		$suser = sqlserver_user;
		$spassword = sqlserver_pass;
		$sdatabase = sqlserver_db;
		
                
               
                
                
		$connectionInfo = array("UID" => $suser, "PWD" => $spassword, "Database"=>$sdatabase);
	
		$serverName = $shost;
		//$connectionInfo = array( "Database"=>"AdventureWorks");
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
		
		$compliance_flag = '';
		
		if( $conn )
		{
			$extoption = array();
			
			
			$extoption['driver']   = $config->dbtype;
			$extoption['host']     =  $config->host;
			$extoption['user']     = $config->user;    
			$extoption['password'] = $config->password;   
			$extoption['database'] = 'centcom';
			$extoption['prefix']   = 'api_';
			$Externaldb = & JDatabase::getInstance($extoption );
			$EXT_query      = "SELECT * FROM ".$extoption['prefix']."apisc WHERE site_database='".$database."'";   
			$Externaldb->setQuery($EXT_query);
                        $orgdata  = $Externaldb->loadObjectList();
                        $orgdata = $orgdata[0];     
			$orgid  = $Externaldb->loadObject()->site_org_id;   
      		
			//$orgid = 58;		
			$sql="{ call dbo.GetSiteEmailComplianceSettings($orgid) }";
			$result = sqlsrv_query($conn, $sql);
			$ComplianceSettings = sqlsrv_fetch_array($result);
      		//print_r($ComplianceSettings);
			$compliance_flag = $ComplianceSettings['complianceemail'];
			
		}
		else
		{
			 $sqlerrorarr = sqlsrv_errors();
			 if(!empty($sqlerrorarr)){
			 	$errorcnt++;
			 	foreach($sqlerrorarr as $se){
					$sqlerror .= $se['message'].'\n';
				}
			 }
		}
		
		/* connect to database and get advisor details start */
		mysql_connect($host,$user,$password) or die(mysql_error());
		mysql_select_db($database)or die(mysql_error());
		
		$selectquery = "SELECT * FROM ".$dbprefix."advisorsettings limit 1";
		$result = mysql_query($selectquery);
		if (mysql_errno()) {
			$errorcnt++;
			$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectquery."\r\n";
		} 
		$advisordetails = mysql_fetch_assoc($result);

		/* connect to database and get advisor details end */
		
		/* Get weekly update groups start */
		$selectgroupquery = "SELECT * FROM ".$dbprefix."weeklyupdate_group";
		$groupresult = mysql_query($selectgroupquery);
		if (mysql_errno()) {
			$errorcnt++;
			$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectgroupquery."\r\n";
		} 
		
		$numrow = mysql_num_rows($groupresult);
		/* Get weekly update groups end */		
		
		
		// Set weekly update mail content         
          
                $filecontent = '';
		
		
		if ( strpos($_SERVER['HTTP_HOST'], 'localhost')!==false ) $orgid = 58;
		
		
                
                            // update market sumary
                            $app = JFactory::getApplication();
                            $model= JModelLegacy::getInstance('Subscription', 'EnewsletterModel', array('ignore_request' => true));
                            $model->setWeeklyUpdateContent($advisordetails);
                            $cid = array();
                            $datawmarket = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
                            
                            // update plat_easyblog_post
                         
                            $datawmarket = preg_replace("/<img[^>]+\>/i", "", $datawmarket); 
                            $datawmarket = str_replace('<p><!--#BEGIN#--></p>', '', $datawmarket);
                            $datawmarketrayy = explode('<p style="margin-width:1px">', $datawmarket);
                            $datawmarketrayy[1] = str_replace('border-color:#888', 'border: none;', $datawmarketrayy[1]);
                            $datawmarket2 = '<p style="margin-width:1px">'.$datawmarketrayy[1];                                 
                            $datawmarket   = '<div><table><tr><td>'.$datawmarket2.'<div style="    float: left;    text-align: center;">'.$datawmarketrayy[0].'</td></tr></table></div></div>';   
                            $datawmarket = str_replace('<!--#END#--></p>', '', $datawmarket);
                            $datawmarket = str_replace('<p><!--#BEGIN#-->', '', $datawmarket);
                            
                            
                            $titlesss = date('m/d/Y').' Weekly Market Summary';                         
                            $aliss = date('m-d-Y').'-weekly-market-summary';
                            
                            $sql = "select id from plat_easyblog_post where permalink like '".$aliss."'"; 
                            $db->setquery($sql);
                            $check = $db->loadResult();
                            
                            if ($check == ''){
                            
                            $datawmarket = str_replace('rimbatest1.advisorproducts.com/about/financial-briefs?id=','contentsrv.advisorsites.com/showarticle.asp?domain=&show=one&article=',$datawmarket);                  
                            $datawmarket = str_replace('basesitesv1f.advisorproducts.com/resources/stock-quotes','www.cfsonline.co/stock-quotes',$datawmarket);  
                            
                            
                            
                            $sql = "INSERT INTO `plat_easyblog_post` (`id`, `created_by`, `created`, `modified`, `title`, `permalink`, `content`, `intro`, `excerpt`, `category_id`, `published`, `publish_up`, `publish_down`, `ordering`, `vote`, `hits`, `access`, `allowcomment`, `subscription`, `frontpage`, `isnew`, `ispending`, `issitewide`, `blogpassword`, `latitude`, `longitude`, `address`, `system`, `posttype`, `robots`, `copyrights`, `image`, `language`, `send_notification_emails`, `doctype`, `document`, `source_id`, `source_type`, `state`, `locked`, `ip`, `revision_id`) VALUES
(NULL, 699, now(), '0000-00-00 00:00:00' , '".$db->escape($titlesss)."', '".$aliss."', '', '".$db->escape($datawmarket)."', NULL, 1, 1, now(), '0000-00-00 00:00:00', 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, '', '', '', '', 0, '', '', NULL, '', '*', 1, 'legacy', '', 0, 'easyblog.sitewide', 0, 0, '210.245.33.31', '');";
                            
                            $db->setquery($sql);
                            $db->query();
                            $iddd = $db->insertid();   
                             
                            // update plat_easyblog_post_category
                            $sql = "INSERT INTO `plat_easyblog_post_category` (`id`, `post_id`, `category_id`, `primary`) VALUES (NULL, $iddd, 1, 1);
";
                            $db->setquery($sql);
                            $db->query();
                            }
                         echo 'done';
                         die;
        }
        
        
        
        public function uploadf(){
			
            
            header('Content-Type: text/plain; charset=utf-8');
            JRequest::checkToken() or die( 'Invalid Token' );
			jimport('joomla.filesystem.file'); 
             $db = JFactory::getDbo();
             $user = JFactory::getUser();
             $post = JRequest::get('post');
             $session =& JFactory::getSession();               
                $session->set('thongbao','');
                $allowedExts = array("doc", "docx", "pdf", "ppt" , "pttx", "xlsx", "xls", "mp3");
                $temp = explode(".", $_FILES["fileu"]["name"]);
                $extension = end($temp);
             
                if ((($_FILES["fileu"]["type"] == "application/pdf")
                || ($_FILES["fileu"]["type"] == "application/msword")
                || ($_FILES["fileu"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation")
                || ($_FILES["fileu"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
                || ($_FILES["fileu"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                || ($_FILES["fileu"]["type"] == "audio/mp3")
                || ($_FILES["fileu"]["type"] == "audio/mpeg")
                || ($_FILES["fileu"]["type"] == "audio/x-mp3"))
                && ($_FILES["fileu"]["size"] < 20000000)
                && in_array($extension, $allowedExts)) {
                    if ($_FILES["fileu"]["error"] > 0) {
                         $session->set('thongbao','Error(1): Upload False!!! ( Extention file not correct ) ');
                    } else { 
                     
                        $fieldName = 'fileu';                         
                        $fileName = $_FILES[$fieldName]['name'];
                        $uploadedFileNameParts = explode('.',$fileName);
                        $duoifile = $uploadedFileNameParts[1];
                      
                        $uploadedFileExtension = array_pop($uploadedFileNameParts);
                        $validFileExts = explode(',', 'doc,docx,pdf,ppt,pttx,xlsx,xls,mp3');
                        $extOk = false;
                        foreach($validFileExts as $key => $value)
                        {
                                if( preg_match("/$value/i", $uploadedFileExtension ) )
                                {
                                        $extOk = true;
                                }
                        }

                        if ($extOk == false) 
                        {
                            $session->set('thongbao','Error(2): Upload False!!! ( Extention file not correct ) ');                              
                        }

                        $fileTemp = $_FILES[$fieldName]['tmp_name'];
                        
                     
                        $imageinfo = getimagesize($fileTemp);
                        $okMIMETypes = 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,audio/mp3,audio/x-mp3,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,audio/mpeg';
                        $validFileTypes = explode(",", $okMIMETypes);	
                        if( !is_int($imageinfo[0]) || !is_int($imageinfo[1]) ||  !in_array($imageinfo['mime'], $validFileTypes) )
                        {
                                  $session->set('thongbao','Error(3): Upload False!!! ( Extention file not correct ) ');               
                             
                        }
                        
                        
                      //  $fileName = preg_replace("/[^A-Za-z0-9]/i", "-", $fileName);
                      //  $fn = rand(0,10000000000).".".$duoifile;                        
                        $uploadPath = JPATH_SITE.DS.'images'.DS.'files'.DS.$fileName;
                      
						if (file_exists($uploadPath)){
							   $session->set('thongbao','Error(7): files exists');
							  $this->setRedirect('index.php?option=com_enewsletter&view=uploadfile');
							return;
						}
						
                        if(!JFile::upload($fileTemp, $uploadPath)) 
                        {
                                $session->set('thongbao','Error(5): Upload False!!! folder not exit');
                              
                        }else {
                             $session->set('thongbao','Upload Done!!!');
                        }                       
                       
                    }
                 
                } else {
                   $session->set('thongbao','Error(6): Upload False!!! Type - '.$_FILES["fileu"]["type"] );
                }
             
             $this->setRedirect('index.php?option=com_enewsletter&view=uploadfile');
             
        }                    
        public function savecolor(){

                 $db = JFactory::getDbo();
                 $user = JFactory::getUser();
                 $post = JRequest::get('post');
                 
                    if ($post['id_user'] != ''){
                        $userchoie = $post['id_user']; 
                    }else {
                        $userchoie = $user->id;
                    }
             
                 $query = $db->getQuery(true);
                 $filen = $post['idt']."_".$userchoie.'_'.$post['changetemps'].'.html';

                 $query->update(' #__enewsletter_option ')->set(" backgc =  '".$db->escape($post['backgc'])."', backbargc =  '".$db->escape($post['backbargc'])."' , maintextgc =  '".$db->escape($post['maintextgc'])."' , linktextgc =  '".$db->escape($post['linktextgc'])."' ")->where(" filen = '".$db->escape($filen)."'  and user_id = '".$userchoie."' ");
                 $db->setQuery($query);
                 $db->query();

                die;
            }
            public function renamecta(){
                $db = JFactory::getDbo();
                $user = JFactory::getUser();
                $post = JRequest::get('post');
                $query = $db->getQuery(true);
                $query->update(' #__cta_cusitems ')->set(" title = '".$db->escape($post['text'])."' ")->where(" id = '".$db->escape($post['id'])."' ");
                $db->setQuery($query);
                $db->query();

                die;
            }

            public function savesuject(){
            
             $db = JFactory::getDbo();
             $user = JFactory::getUser();
             $post = JRequest::get('post');
             $query = $db->getQuery(true);
             
               if ($post['id_user'] != ''){
                        $userchoie = $post['id_user']; 
                    }else {
                        $userchoie = $user->id;
                    }
             
                    
                    
             $filen = $post['idt']."_".$userchoie.'_'.$post['changetemps'].'.html';
             
             $query->update(' #__enewsletter_option ')->set(" subject =  '".$db->escape($post['sub'])."' ")->where(" filen = '".$db->escape($filen)."'  and user_id = '".$userchoie."' ");
             $db->setQuery($query);
             $db->query();
             
            die;
        }
        
           public function savetemplatename(){
            
             $db = JFactory::getDbo();
             $user = JFactory::getUser();
             $post = JRequest::get('post');
             $query = $db->getQuery(true);
             
            if ($post['id_user'] != ''){
                        $userchoie = $post['id_user']; 
                    }else {
                        $userchoie = $user->id;
                    }
             
                    
                    
             $filen = $post['idt']."_".$userchoie.'_'.$post['changetemps'].'.html';
             
             $query->update(' #__enewsletter_option ')->set(" tplname =  '".$db->escape($post['sub'])."' ")->where(" filen = '".$db->escape($filen)."'  and user_id = '".$userchoie."' ");
             $db->setQuery($query);
             $db->query();
             
            die;
        }

         function testmail (){
            
                $app = JFactory::getApplication();
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
                $post = JRequest::get('post');
                $api=$post['newsletter_api'];
		$APIKEY  = CONSTANT_APIKEY;
		$ACCESS_TOKEN = $post['apikey'];     
               
                if($api == 'M'){
        	$from_email_address = trim($data['verified_email_name']).'@'.trim($from_email_address);
                  }
                $from_email_address = JRequest::getVar('verified_emails');
		// Get all details of logged user
		$loggeduser = JFactory::getUser();
		
		// Get api token key to access web service
		$apitoken = $app->getUserState("com_enewsletter.APIToken");
		
		// Get all selected article's ids
		$cid =  JRequest::getVar('cid');  
                $articles_ids = implode(',', $cid);     
                $ids = implode('" , "',$cid);
  
                // Get ids of selected images from article list 
		$showimage_ids = $showimage_id = JRequest::getVar('sid');
		//$showimage_ids = explode(',',$showimage_id);	
                               
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');		
    
                
                $db = JFactory::getDBO();
                $query = 'SELECT * FROM #__advisorsettings';
                $db->setQuery($query);  
                $advisordetails = $db->loadObject();
                $app->setUserState("com_enewsletter.advisorname",$advisordetails->from_name);
                $app->setUserState("com_enewsletter.advisoremail",$advisordetails->from_email);
                $app->setUserState("com_enewsletter.testemail",$advisordetails->email);
                $app->setUserState("com_enewsletter.weeklyupdategroup",$wgroups); 
                $app->setUserState("com_enewsletter.ACCESS_TOKEN",$advisordetails->api_key);
                $app->setUserState("com_enewsletter.API",$advisordetails->newsletter_api);        
                $app->setUserState("com_enewsletter.Weekly_subject",$advisordetails->update_subject);
                $app->setUserState("com_enewsletter.Weekly_intro",$advisordetails->weekly_update_intro);
                $app->setUserState("com_enewsletter.custom_link_article",$advisordetails->custom_link_article);
                $app->setUserState("com_enewsletter.path_quote",$advisordetails->path_quote);
                $app->setUserState("com_enewsletter.newsletter_default_template",$advisordetails->newsletter_default_template);
                $app->setUserState("com_enewsletter.weeklyupdate_default_template",$advisordetails->weeklyupdate_default_template);
                $app->setUserState("com_enewsletter.massemail_default_template",$advisordetails->massemail_default_template);
                $template_array = array($advisordetails->newsletter_default_template,$advisordetails->weeklyupdate_default_template,$advisordetails->massemail_default_template);
                $app->setUserState("com_enewsletter.template_array",$template_array);
           //     echo  $app->getUserState("com_enewsletter.advisoremail");die;
		// Get the model.
		$model = $this->getModel();
		
		// Get all articles from com_fnc and com_fbc table using artilce ids
		$articles = $model->getArticle($ids);
		
		$articlecontent = '';

                // Get all templates of newsletter type
              
                if ($post['changetemps']){
                    $fn = explode('.', $post['changetemps']);
                     $ca =  file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$post['changetemps'].'.html');                   
                    if (!$ca){
                      $templates = 'enewsletter_defaults.html';
                    }else{
                      $templates =  $post['changetemps'].'.html';
                    }
                }

                
                $NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates);

               // echo $NEWSLETTER;
    
                // Create and replace content for selected articles start
                $articleimgarray = array();
                $j  =1;
                foreach($articles as $ar){		
		 // Create article content and more link  start
		  $articlelink='';
			  
		  $custom_link_article = $advisordetails->custom_link_article;
		  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
		  
		  if($valid_format) {
			$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
		  }
		  else
		  {
			if($ar->type == 'Featured News') 
			{
			  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
			}
			else if($ar->type == 'Financial Briefs')
			{
			  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
			}
			else
			{
			  $articlelink='';
			}
			
		  }
		
                    // Set article link for more in description	
                    if ( $articlelink !='')      
                    {
                          $titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
                    }
                    else
                    {
                          $titlelink = '';
                    }
                          $articlebody = $ar->description.'<br/>'.$titlelink;
                   // Create article content and more link  end

                    if(trim($articlelink) ==''){
                          $articlelink = 'javascript:void(0);';	
                    }

                    $ar->articlelink =  $articlelink;
		  
		  
                    //Create article image  path start  
                    if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
                            $path = $this->create_image($ar->slideshowimage);
                            $path =  JURI::base().'media/com_enewsletter/article_images/'.$path; 
                            if($path != ''){
                                          $ar->image =  $path;
                                  }else{
                                          $ar->image = '';
                                  }          
                            } else{
                                   $ar->image = '';
                          }
          //Create article image  path end

                          $ar->description = $ar->description.'<br/>'.$titlelink;
                          $j++; 

                  }
		
		
		//exit;
                    $data['id'] = '';	
                    $data['email_id'] = '';	

                    // Assigns all articles to $data variable
                    $data['articles'] = $articles;		
                    // Get and assigns current task (send)
                    $data['task'] =  JRequest::getVar('task');		

                    // Set all selected articles id's to state 
                    $app->setUserState("com_enewsletter.cid",$cid);	


                    // Set all selected groups id's to state 
                    $app->setUserState("com_enewsletter.gid",$gid);

                    $app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);

                    // Set current form's data to state 
                    $app->setUserState("com_enewsletter.data",$data);


                    // Replace title, intro, trailer, disclosure placeholders from template start
                    $footer = '';
                    JLoader::register('enewsletterHelper', JPATH_COMPONENT_ADMINISTRATOR . 'com_enewsletter/helpers/template.php');		
                    //$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
                    $content = $NEWSLETTER;

                    $content = str_replace('src="'.JURI::base().'data:image', 'src="data:image', $content);

                    $posss = strpos($content,'Intro Enter Here ...');
                    if( $posss !== false) {
                        $content = str_replace('bgcolor="#ECEBE0"', 'style="display: none;"', $content);   
                        $content = str_replace('padding: 25px;', 'padding: 25px;padding-top:2px;', $content);  
                        $content = str_replace('margin-bottom: 50px;', 'margin-bottom: 10px;', $content); 
                    }
//                    $strcheck = strpos('noconfig="1"', $content);
//                    if ( $strcheck !== false){
//                         $content = str_replace('margin-bottom: 27px;', 'margin-bottom: -10px;', $content); 
//                    }
                    $content = str_replace('noconfig="1"', 'ids="cbvnncb"', $content);                    
                    
                    
               
                      // add css in file
                      //  $addcssscript = file_get_contents(JPATH_SITE."/components/com_enewsletter/assets/newsletter.css");
                      //  $addcssscript = '<style>'.$addcssscript.'</style>';
                    $content = $content;
              
                    $content = str_replace('display: none;', '"ids="cbvnncb""', $content);
               
                    $dom = new SmartDOMDocument();    
                    $dom->loadHTML($content);  
                  
                    $links = $dom->getElementsByTagName('div');
                    $i=1;
                    foreach ($links as $link){ 
                        
                        if ($link->getAttribute('ids') == 'cbvnncb'){
                             $link->removeAttribute('id');
                             $link->setAttribute("id", 'aabbcc'.$i);
                            //  $link->removeChild($link);
                              $i++;
                        }                       
                    }
                    for ($j = ( $i - 1 ) ; $j >= 1  ;$j-- ){
                        
                        $a3 = $dom->getElementById('aabbcc'.$j);
                     
                        if( $a3 != NULL ){
                             $a3->parentNode->removeChild($a3);
                        }                       
                    }
                    
                    $linkas = $dom->getElementsByTagName('a');
                    $k=1;
                    foreach ($linkas as $linka){ 
                        
                        if ($linka->getAttribute('ids') == 'cbvnncb'){
                             $linka->removeAttribute('id');
                             $linka->setAttribute("id", 'aabbccss'.$k);
                        
                              $k++;
                        }                       
                    }
                     
                    for ($j = 1 ; $j< $k ;$j++ ){
                        $a4 = $dom->getElementById('aabbccss'.$j); 
                         if($a4 != NULL){
                            $a4->parentNode->removeChild($a4);
                         }
                    }
                  
                    $sub = explode('_', $post['changetemps']);
                    $content =  $dom->saveHTML();
                    $content = "<div style='color:#fff;  background: #fff;  opacity: 0;  visibility: hidden;'  >".'Mail test from template : '.$post['templatename']."</div>".$content;        
                    $mailer = JFactory::getMailer();
                    $config = JFactory::getConfig();		
                    $mailer->addRecipient($post['emailtest']);
                    $mailer->isHTML(true);
                    $mailer->Encoding = 'base64';
                    $mailer->setSubject('Mail test from template : '.$post['templatename']);
                    $mailer->setBody($content);
                    $send = $mailer->Send();
               
                    $app->setUserState("com_enewsletter.meess",'Send email test complete');	
                    $this->setRedirect('index.php?option=com_enewsletter&view=editletter');

        }
        
          public function deletectavideo(){
             $db = JFactory::getDbo();
             $user = JFactory::getUser();
             $session =& JFactory::getSession();
             $post = JRequest::get('post');
             $sql = " select * from #__cta_cusitems where id = '".$db->escape($post['id'])."'  "; 
             $db->setQuery($sql);
             $data = $db->loadObject();
             if ($data->id != ''){
//                if (file_exists(JPATH_SITE.'/media/com_cta/'.$data->file_name)){ 
//                    unlink(JPATH_SITE.'/media/com_cta/'.$data->file_name);
//                }
                // clear database
                $query = $db->getQuery(true);
//                $query->delete('#__cta_cusitems')->where(" id = '".$db->escape($post['id'])."'");
                $query->update('#__cta_cusitems')->set("published = 0")->where(" id = '".$db->escape($post['id'])."'");
                $db->setQuery($query);
                $db->query();
                
                echo 'done';
             }
             print_r($post);
             die;
          }
          
          public function deleteit(){
             $db = JFactory::getDbo();
             $user = JFactory::getUser();
             $session =& JFactory::getSession();
             $post = JRequest::get('post');
             
             
             if ($post['id_user'] != ''){
                 $userchoie = $post['id_user']; 
             }else {
                 $userchoie = $user->id;
             }
             
             
             
             
             $filen = $post['idt']."_".$userchoie.'_'.$post['changetemps'].'.html';
             $check_f1= JPATH_SITE."/administrator/components/com_enewsletter/templates/".$filen;
              if (file_exists($check_f1)) {         
               
                // clear file
                unlink($check_f1);
                // clear database
                $query = $db->getQuery(true);
                $query->delete('#__enewsletter_option')->where(" filen = '".$db->escape($filen)."' and user_id = '".$userchoie."'  ");
                $db->setQuery($query);
                $db->query();
                
                // clear session
                 
                $session->clear('idt');
                $session->clear('changetemps');
                $this->setRedirect('index.php?option=com_enewsletter&view=editletter');
            }else {
                  $this->setRedirect('index.php',"can't delete template");
                
            }              
            
        }
        
        
        // upload video cta
        public function uploadvideocta(){            
            
		define('PHOTOS_DIR', JPATH_ROOT."/media/com_cta/");		
		if (!file_exists(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
		
		#HT
		$getraw = JRequest::getInt('getraw');
		
		if (!$getraw)
			JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		
		$file = JRequest::getVar('file_name', '', 'files');
		if ( $file['error'] ) {			
                        echo '<div class="upload_error" > Upload Error! </div>';
                        die;			
		}		
		if ($file['name']) {		
             
			$info = pathinfo($file['name']);
			$extension  = strtolower($info['extension']);
			$valid_extension = in_array($extension, $this->file_exts);
			if ( !$valid_extension ) {
				echo '<div class="upload_error" > Invalid featured file extension. Allow: '.(implode(', ', $this->file_exts)).'</div>';
				die;
			} else if ( $file['size'] > $this->max_size_upload ) {
				echo '<div class="upload_error" > Over max_size_upload. Allow: '.($this->max_size_upload/1048576).'MB </div>';
				die;
			}//if			
			$filename = $info['filename'].'_'.rand(1,1000);
			if ( move_uploaded_file($file['tmp_name'], PHOTOS_DIR.$filename.".".$extension)) {
				$file_name = $filename.".".$extension;
				$data['file_name'] = $file_name;
				$data['file_type'] = $extension;
				
			}//if
		}//if
		
		
                $db = JFactory::getDbo();
                $name = str_replace('_', " ", $data['file_name']);
                $name = str_replace('-', " ", $name);              
                $name = str_replace($this->file_exts, " ", $name);
                $name = str_replace('.', " ", $name);
                $date =  date('Y-m-d H:i:s');
                $sql =  "INSERT INTO #__cta_cusitems (`id`, `title`, `file_name`, `file_type`, `date_created`, `published`) "
                        ."VALUES (NULL, '".$name."', '".$data['file_name']."', '".$info['extension']."', '$date', '1');";
                $db->setQuery($sql);
                $db->query();
                $id = $db->insertid();
				
				#HT
				if ($getraw) {
					
					$result = array('status' => 1);
					$result['id'] = $id;
					$result['title'] = $name;
					echo json_encode($result);
					
					
				} else {
                ?>
                <br>
                    <input onClick="$('#cus_or_video').val('cusitem_id[]');$('#extend_video').val('');$('#textctatit').val('<?php echo $name; ?>')" type="radio" id="idcta<?php echo $id ?>" name="nidcta" value="<?php echo $id; ?>">
                    <label class="ctaicon_<?php echo $info['extension']; ?>" for="0" ><a id="atagcta_clion" class="nameof<?php echo $id; ?>" onClick="previewcta('<?php echo $id ?>','2')" href="#" ref="<?php echo JURI::base() ?>index.php?option=com_cta&tmpl=component&preview=fsdf8sf987s98w7e8sd9f4s98f489sd4f8sd4fsd6&view=video&cusitem_id[]=<?php echo $id ?>" ><?php echo $name; ?></a><span onClick="deletecta('<?php echo $id ?>')" style="color:red;cursor: pointer;    margin-left: 10px;    font-weight: bold;    font-size: 17px;" >x</span></label>
		<?php
				}//if raw
		die;
        }
        
        
        
        
        // chuyen bai viet thanh array
        public static function objectsIntoArray($arrObjData, $arrSkipIndices = array())
	{
		$arrData = array();
	   
	    // if input is object, convert into array
	    if (is_object($arrObjData))
		{
	        $arrObjData = get_object_vars($arrObjData);
	    }
	   
	    if (is_array($arrObjData))
		{
	        $find = array('’','…','–','—','“','”','•','‘','‡','™','®');
			$replace = array('\'','…','-','-','"','"','-','\'','c','™','®');
			
			foreach ($arrObjData as $index => $value)
			{
	            if (is_object($value) || is_array($value))
				{
	                $value = self::objectsIntoArray($value, $arrSkipIndices); // recursive call
	            }
	            if (in_array($index, $arrSkipIndices))
				{
	                continue;
	            }
				//convert special character
				$value = str_replace($find, $replace, $value);
				
	            $arrData[$index] = $value;
	        }
	    }
	    return $arrData;
	}
        
        // get bai viet  tu api
        public static function getArticle($token, $articleid)
	{
                    $params			= JComponentHelper::getParams('com_apicontent');
                    $webServiceUrl 		= $params->get('webservice_url');

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $webServiceUrl . 'GetArticle.aspx?authtoken=' . $token . '&articleid=' . $articleid);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
                    $xml = curl_exec($curl);
                    $xmlObj = simplexml_load_string( $xml );
                    $status = $xmlObj->channel->status;
                    if ($xml === false || !is_object($xmlObj) || $status != 0 || (curl_errno($curl) > 0) )
            {
                    $error = '';
                    if(!is_object($xmlObj)){$error = 'There is some error related to the XML response';}
                    if($status != 0){$error = $xmlObj->channel->statusmessage;}
                    return "Error: ".$error;
            } 
            else 
            {
                    curl_close($curl);
                    return $xmlObj;
            }
	}
        
        //get token api
	public static function advisorAuthenticate(){
            
		$params				= JComponentHelper::getParams('com_apicontent');
		$username 			= $params->get('username');
		$password 			= $params->get('password');
		$webServiceUrl 		= $params->get('webservice_url');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,  $webServiceUrl . 'Authenticate.aspx?userid='.$username.'&password='.$password);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
		$xml = curl_exec($curl);

                if ($xml === false || (curl_errno($curl) > 0) )
                {
                        return "Error: ".curl_error($curl);
                }  
                else 
                {
		curl_close($curl);
		$xmlObj = simplexml_load_string( $xml );
		$status = $xmlObj->status;
		switch ($status)
			{
				case 0:
				return $xmlObj->authtoken;
				case 1:
				return 'Error: Invalid userid or password';
				case 2:
				return 'Error: Password expired';
				case 3:
				return 'Error: Account locked out';
				case 9:
				return 'Error: Unknown error';
				default:
				return 'Error: Unknown error';
			}
               }
		
	}
        
        public function getcontent3(){
              
               $db = JFactory::getDbo();
               $get =  JRequest::get('get');
               
               $adata =  explode(',', $get['id']);
               $adataimage =  explode(',', $get['sid']);
               $adataintro =  explode(',', $get['getin']);
               
               $fruit  = array_pop($adata);
               $ids = implode('" , "',$adata);
               $model = $this->getModel();		
               $articles = $model->getArticle($ids);
                  
               $query = 'SELECT * FROM #__advisorsettings';
               $db->setQuery($query);  
               $advisordetails = $db->loadObjectList();                   
               $custome_url = $advisordetails[0]->custom_link_article;
          
                   ?>
                    
                   <div id="intro" bgcolor="#ECEBE0"  style="background: rgb(236, 235, 224);     width: 98%;   float: left;" >                                   
                             <table width="98%" background="0"   cellspacing="10"  >
                                <tr>
									<td>
                                        <strong style="font-size: 16px;    margin-left: 12px;" class="intro">Intro Enter Here ...</strong>     
                                                                        </td>
                                </tr>
                             </table>
                            
                 </div>
                    
                        <div id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="mceItemTable">
                             
                                  
                                  <?php 
                                    $num_i=0;
                                    $session = JFactory::getSession();
		
                                    if(!$session->has('com_apicontent.front.token'))
                                    {
                                            $token 		= EnewsletterController::advisorAuthenticate();
                                            if(substr($token,0,5) == 'Error')
                                            {
                                                    JError::raiseWarning(500, $token );
                                                    return false;
                                            }
                                            else
                                            {
                                                    $session->set('com_apicontent.front.token', (string)$token);	
                                            }

                                    }
                                    else
                                    {
                                            $token = $session->get('com_apicontent.front.token');
                                    }
                                    
                                    if (count($adata) > 1) {
                                        $checks = 1;
                                    }
                                 foreach ($adata as $ad){
                                     $num_i++;
                                  foreach ($articles as $r ) {
                                           if( $ad == $r->article_id ) {                                          
                                               
                                               
                                        if ($r->fullcontent == '') {     
                                                $xmlObj	= EnewsletterController::getArticle($token, $r->article_id);
                                                $arrXml      = EnewsletterController::objectsIntoArray($xmlObj);
                                                $r->description =                 $arrXml["channel"]["item"]["description"];
                                                $r->description = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $r->description);
                                                $r->description = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $r->description);
                                                $r->description = preg_replace('#<embed(.*?)>(.*?)</embed>#is', '', $r->description);
                                                $r->description = str_replace('Loading the player ...', '', $r->description);
                                                $r->description = str_replace('Copyrighted material.', '', $r->description);
                                                
                                                $query = $db->getquery(true);
                                                $query->update("#__apifbc")->set("fullcontent = '".$db->escape($r->description)."' ")->where("article_id = '".$r->article_id."' ");
                                                $db->setquery($query);
                                                $db->query();
                                                
                                                $query = $db->getquery(true);
                                                $query->update("#__apifnc")->set("fullcontent = '".$db->escape($r->description)."' ")->where("article_id = '".$r->article_id."' ");
                                                $db->setquery($query);
                                                $db->query();
                                                
                                                
                                          }else {
                                              
                                                $r->description = $r->fullcontent;
                                              
                                          }
                                          
                                          $articlelink='';			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;	// save image
                                          $url = $r->slideshowimagewide;
                                          $filen = rand(0, 10000000000000).".jpg";
                                          
										  //$img = JPATH_COMPONENT.DS.'assets'.DS.'upload'.DS.$filen;
										  
										  $img = JPATH_ROOT.'/media/com_enewsletter/upload/'.$filen;
                                       
                                            if (is_array($img)) {
                                             $images1 = JURI::base().'media/com_enewsletter/upload'.$filen;
                                            } else {
                                             $images1 = $r->slideshowimagewide;
                                            }
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$r->article_id, trim($custome_url));
					  }
          			  else
          			  {
              				if($r->type == 'Featured News') 
              				{
              				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$r->article_id ;
              				}
              				else if($r->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$r->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }
               
                                      
                                      
                                      ?>
                            
                                  <table align="left" width="380"  id-api="<?php if($get['getonlyintro'] != 1){  echo $r->article_id; }  ?>"  id-cont="article_content_<?php echo $num_i; ?>" class="edit_content" id="article_<?php echo $num_i; ?>" style="background-color: #f4f4f4;<?php if($checks){echo 'width:48.5%;Float:left;margin-right:1%;';} ?>" data-mce-style="background-color: #f4f4f4;">
                                      <tr><td>
                                      <div style="padding: <?php if($checks){echo '7';}else {echo '25';} ?>px;" data-mce-style="padding: 25px;">
                                          <table width="100%" class="mceItemTable">
                                              <tr><td>
                                                 
                                                      <div style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" >
                                                          
                                                          <br>
                                                          <div  finra="<?php echo $r->finra_status; ?>" info="<?php if ( isset($get['sid']) && isset($get['getin']) &&  !in_array($ad, $adataimage) ||  in_array($ad, $adataintro)){echo 'not';} ?>" id="article_content_<?php echo $num_i; ?>" style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> 
                                                              <strong style="font-size: 20px;" data-mce-style="font-size: 20px;">
                                                                  <a href="<?php echo $articlelink; ?>" style="color: #000000; text-decoration: none;" data-mce-href="<?php echo $articlelink; ?>" data-mce-style="color: #000000; text-decoration: none;" id="api-title" ><?php echo $r->article_title; ?></a>
                                                              </strong> 
                                                              <br> <?php 
                                                                  
                                                                  
                                                                  if ( strlen( $r->description) > 2000000 ||  ( !in_array($ad, $adataimage) && isset($get['sid'])) ){                      
                                                                        $r->description =  preg_replace("/<img[^>]+\>/i", " ",  $r->description);
                                                                     }
                                                                  $r->description =  str_replace('460', '100%', $r->description) ; 
                                                                   $r->description =  str_replace('<img', '<img width="350"', $r->description) ; 
                                                                  
                                                                 if($get['getonlyintro'] == 1 || in_array($ad, $adataintro)  ){
                                                                       preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $r->description , $img);
                                                                       $aimg = explode(' ', $img[1]);
                                                                       if ($aimg[0] != '' ){
                                                                       echo '<p style="text-align:center;"><img src="'.$aimg[0].'" width="350" style="width:90%;  height: 188px;" /></p>';
                                                                       }
                                                                      $r->description = str_replace('<p> </p>', '', $r->description) ; 
                                                                      $r->description = strip_tags ($r->description,"<p>");
                                                                      $aadr = explode('</p>', $r->description );
                                                                      foreach ($aadr as $lm){
                                                                          if (strlen($lm) > 100){
                                                                                echo $lm.'</p>';
                                                                                break;
                                                                          }
                                                                      }
                                                                   
                                                                  }else {
                                                                      
                                                                     echo  $r->description =  str_replace('259', 'auto', $r->description) ;
                                                                  
                                                                  }
                                                                  
                                                                  
                                                                  ?> </div> 
                                                                  <a style=" <?php  if($get['getonlyintro'] != 1 && !in_array($ad, $adataintro) ){ ?> display: none; <?php } ?>   font-family: sans-serif;    font-size: 19px;    margin-top: 17px;    text-align: right;    text-decoration: none;" id="link_article_content_<?php echo $num_i; ?>" target="_blank" href="<?php echo $articlelink;  ?>" ><span style="    background-color: #000;    padding: 10px;    border-radius: 10px;    color: #fff;   ">Read More</span></a>
                                                      </div>
                                                     
                                                    </td> </tr>
                                              
                                          </table>
                                      </div>
                                 </td> </tr>
                                  </table>  
                            
                            
                            <?php if ($num_i%2 == 0) { ?>
                            <div class="clear" style="width:100%;clear: both;float: none;margin-top: 10px;margin-bottom: 10px;"></div>
                            <?php } ?>
                                           <?php }}} ?>  
    
    </div>
<?php
               
               die;
           }
           
        function limittext(){
                $db = JFactory::getDbo();
                $user = JFactory::getUser();
                $session =& JFactory::getSession();
                $post = JRequest::get('post');              
                
                $dom = new SmartDOMDocument();
    		$dom->loadHTML($_POST['content']); 
                $a1 = $dom->getElementById('api-title');
                if($a1){
                   $title =  $a1->nodeValue;        
                   $href = $a1->getAttribute('href');
                }          
                 
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $_POST['content'] , $img);
                $aimg = explode(' ', $img[1]);
                if ($aimg[0] != '' ) {
                   echo '<p style="text-align:center;"><img src="'.$aimg[0].'" style="max-width: 450px;" width="450" /></p>';
                }            
                
                echo '<strong style="font-size: 20px;" data-mce-style="font-size: 20px;"><a href="'.$href.'" style="color: #000000; text-decoration: none;" data-mce-style="color: #000000; text-decoration: none;" id="api-title">'.$title.'</a></strong>';
                $con = $this->limit_words($_POST['content'],$_GET['limit'] );
                $con =  str_replace($title, '', $con);
                $con =  str_replace('<p>&nbsp;</p>', '', $con);
                
                echo $con;
                die;
                
        }  
        
        // #HT
        function limit_words($text, $limit_word) {

                $content = strip_tags($text, '<p>');            
                $content_p_a = explode('</p>',$content);
                array_filter($content_p_a);
                $content_p_a2 = array();
                foreach ($content_p_a as $content_p) {
                        $content_p = trim($content_p);
                        if (!$content_p) continue;
                        //is this have p
                        if (substr($content_p, 0, 3)!=='<p>') {
                                $p_pos = strpos($content_p, '<p>');
                                if ($p_pos===false) {
                                        continue;
                                } else {	
                                        $content_p = substr($content_p, $p_pos);
                                }//if
                        }//if
                        //is this p
                        $content_p2 = substr($content_p, 3);
                        $content_p2 = strip_tags($content_p2);
                        if (!trim($content_p2)) continue;
                        $content_p_a2[] = trim($content_p2);
                }//for           

                $thelimit = array();
                foreach ($content_p_a2 as $content_p){
                        $text_array = explode(" ", $content_p);
                        $text_count = count($text_array);
                        if ( $text_count == $limit_word ) {
                                $thelimit[] = '<p>'.$content_p.'</p>';
                                break;
                        } else if ( $text_count > $limit_word ) {
                                $thelimit[] = '<p>'.implode(' ',array_slice($text_array, 0, $limit_word)).'</p>';
                                break;
                        } else {
                                $thelimit[] = '<p>'.$content_p.'</p>';
                                $limit_word -= $text_count;
                        }//if
                }//for

                return implode($thelimit);

        }//func

        // get bai viet tu enewletter
        public function getcontent(){
              
               $db = JFactory::getDbo();
               $get =  JRequest::get('get');
               $adata =  explode(',', $get['id']);
               $adataimage =  explode(',', $get['sid']);
               $adataintro =  explode(',', $get['getin']);
               $fruit  = array_pop($adata);
               $ids = implode('" , "',$adata);
               $model = $this->getModel();		
               $articles = $model->getArticle($ids);
               
               $query = 'SELECT * FROM #__advisorsettings';
               $db->setQuery($query);  
               $advisordetails = $db->loadObjectList(); 
                  
                  $custome_url = $advisordetails[0]->custom_link_article;
           
                   ?>
                     <?php  if($get['senweekly'] != 1){ ?>
                        <div id="intro" bgcolor="#ECEBE0"  style="background: rgb(236, 235, 224);     width: 100%;   float: left;" >                                   
                             <table width="100%" background="0"   cellspacing="10"  >
                                <tr>
									<td>
                                        <strong style="font-size: 16px;    margin-left: 12px;" class="intro">Intro Enter Here ...</strong>     
                                                                        </td>
                                </tr>
                             </table>
                            
                        </div>
                     <?php } ?> 
                        <table id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="mceItemTable">
                            <tbody>
                                
                                   
                                       
                                  <?php 
                                    $num_i=0;
                                    $session = JFactory::getSession();
		
                                    if(!$session->has('com_apicontent.front.token'))
                                    {
                                            $token 		= EnewsletterController::advisorAuthenticate();
                                            if(substr($token,0,5) == 'Error')
                                            {
                                                    JError::raiseWarning(500, $token );
                                                    return false;
                                            }
                                            else
                                            {
                                                    $session->set('com_apicontent.front.token', (string)$token);	
                                            }

                                    }
                                    else
                                    {
                                            $token = $session->get('com_apicontent.front.token');
                                    }
                                    
                                    if($get['senweekly'] != 1){  
                                 foreach ($adata as $ad){
                                     $num_i++;
                                  foreach ($articles as $r ) {
                                          if( $ad == $r->article_id ) {
                                           
                                          if ($r->fullcontent == '') {     
                                                $xmlObj	= EnewsletterController::getArticle($token, $r->article_id);
                                                $arrXml      = EnewsletterController::objectsIntoArray($xmlObj);
                                                $r->description =                 $arrXml["channel"]["item"]["description"];
                                                $r->description = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $r->description);
                                                $r->description = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $r->description);
                                                $r->description = preg_replace('#<embed(.*?)>(.*?)</embed>#is', '', $r->description);
                                                $r->description = str_replace('Loading the player ...', '', $r->description);
                                                $r->description = str_replace('Copyrighted material.', '', $r->description);
                                                
                                                $query = $db->getquery(true);
                                                $query->update("#__apifbc")->set("fullcontent = '".$db->escape($r->description)."' ")->where("article_id = '".$r->article_id."' ");
                                                $db->setquery($query);                                                
                                                $db->query();
                                                
                                                
                                                
                                                $query = $db->getquery(true);
                                                $query->update("#__apifnc")->set("fullcontent = '".$db->escape($r->description)."' ")->where("article_id = '".$r->article_id."' ");
                                                $db->setquery($query);
                                                $db->query();
                                                
                                                
                                          }else {
                                              
                                                $r->description = $r->fullcontent;
                                              
                                          }
                                          
                                          $articlelink='';			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;					   // save image
                                          $url = $r->slideshowimagewide;
                                          $filen = rand(0, 10000000000000).".jpg";
                                          $img = JPATH_ROOT.'/media/com_enewsletter/upload/'.$filen;
                                       
                                            if (is_array($img)) {
                                             $images1 = JURI::base().'media/com_enewsletter/upload'.$filen;
                                            } else {
                                             $images1 = $r->slideshowimagewide;
                                            }
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$r->article_id, trim($custome_url));
					  }
          			  else
          			  {
              				if($r->type == 'Featured News') 
              				{
              				  $articlelink= JURI::root().'index.php?option=com_apicontent&view=fnclist&id='.$r->article_id ;
              				}
              				else if($r->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::root().'index.php?option=com_apicontent&view=fbclist&id='.$r->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }
               
                                      
                                      
                                      ?>
                                  <tr id-api="<?php  if($get['getonlyintro'] != 1){ echo $r->article_id; } ?>"  id-cont="article_content_<?php echo $num_i; ?>" class="edit_content" id="article_<?php echo $num_i; ?>" style="background-color: #f4f4f4;" data-mce-style="background-color: #f4f4f4;"><td style="padding: 25px;" data-mce-style="padding: 25px;">
                                          <table width="100%" class="mceItemTable"><tbody><tr><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; text-align: justify;">
                                                          
                                                          <br>
                                                          <div finra="<?php echo $r->finra_status; ?>" info="<?php if ( isset($get['sid']) && isset($get['getin']) && !in_array($ad, $adataimage) ||  in_array($ad, $adataintro) ){echo 'not';} ?>" id="article_content_<?php echo $num_i; ?>" style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> 
                                                              <strong  style="font-size: 20px;" data-mce-style="font-size: 20px;">
                                                                  <a href="<?php echo $articlelink; ?>" style="color: #000000; text-decoration: none;" data-mce-href="<?php echo $articlelink; ?>" data-mce-style="color: #000000; text-decoration: none;" id="api-title" ><?php echo $r->article_title; ?></a>
                                                              </strong>
                                                                  
                                                                  <br><?php
                                                                  
                                                                  if ( strlen( $r->description) > 2000000 ||  ( !in_array($ad, $adataimage) && isset($get['sid'])) ){
                                  
                                                                         $r->description =  preg_replace("/<img[^>]+\>/i", " ",  $r->description);
                                                                  }
                                                                  
                                                                
                                                                 if( $get['getonlyintro'] == 1 || in_array($ad, $adataintro)  ){
                                                                       preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $r->description , $img);
                                                                        $aimg = explode(' ', $img[1]);
                                                                       if ($aimg[0] != '' ) {
                                                                       echo '<p style="text-align:center;"><img src="'.$aimg[0].'" width="450" style="max-width: 600px;" /></p>';
                                                                       }
                                                                      $r->description = str_replace('<p> </p>', '', $r->description) ; 
                                                                      $r->description = strip_tags ($r->description,"<p>");
                                                                      $aadr = explode('</p>', $r->description );
                                                                        foreach ($aadr as $lm){
                                                                             if (strlen($lm) > 100){
                                                                                     echo $lm.'</p>';
                                                                                     break;
                                                                             }
                                                                         }
                                                                  }else {
                                                                         
                                                                        echo $r->description;
                                                                  
                                                                  }
                                                                  
                                                                     
                                                                  ?> 
                                                          </div> 
                                                          <a style="  <?php  if($get['getonlyintro'] != 1 && !in_array($ad, $adataintro) ){ ?> display: none; <?php } ?>  font-family: sans-serif;    font-size: 19px;    margin-top: 17px;    text-align: right;    text-decoration: none;" id="link_article_content_<?php echo $num_i; ?>" target="_blank" href="<?php echo $articlelink; ?>" > <span style="    background-color: #000;    padding: 10px;    border-radius: 10px;    color: #fff;   ">Read More</span></a>
                                                      </td>
                                                     
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </td>
                                  </tr>  
                                    <?php }}}}else{
                                     foreach ($adata as $ad){
                                     $num_i++;
                                
                                        
                                           
                                         
                                                $xmlObj	= EnewsletterController::getArticle($token, $ad);
                                                $arrXml      = EnewsletterController::objectsIntoArray($xmlObj);
                                                $r->description =                 $arrXml["channel"]["item"]["description"];
                                                $r->description = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $r->description);
                                                $r->description = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $r->description);
                                                $r->description = preg_replace('#<embed(.*?)>(.*?)</embed>#is', '', $r->description);
                                                $r->description = str_replace('Loading the player ...', '', $r->description);
                                                $r->description = str_replace('Copyrighted material.', '', $r->description);
                                                $r->article_title =                 $arrXml["channel"]["item"]["title"];
                                                $articlelink= JURI::root().'index.php?option=com_apicontent&view=fnclist&id='.$ad ;
                                      
                                      ?>
                                  <tr id-api="<?php  if($get['getonlyintro'] != 1){ echo $r->article_id; } ?>"  id-cont="article_content_<?php echo $num_i; ?>" class="edit_content" id="article_<?php echo $num_i; ?>" style="background-color: #f4f4f4;" data-mce-style="background-color: #f4f4f4;"><td style="padding: 25px;" data-mce-style="padding: 25px;">
                                          <table width="100%" class="mceItemTable"><tbody><tr><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; text-align: justify;">
                                                          
                                                          <br>
                                                          <div info="<?php if ( isset($get['sid']) && isset($get['getin']) && !in_array($ad, $adataimage) ||  in_array($ad, $adataintro) ){echo 'not';} ?>" id="article_content_<?php echo $num_i; ?>" style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> 
                                                              <strong  style="font-size: 20px;" data-mce-style="font-size: 20px;">
                                                                  <a href="<?php echo $articlelink; ?>" style="color: #000000; text-decoration: none;" data-mce-href="<?php echo $articlelink; ?>" data-mce-style="color: #000000; text-decoration: none;" id="api-title" ><?php echo $r->article_title; ?></a>
                                                              </strong>
                                                                  
                                                                  <br><?php
                                                                  
                                                                  if ( strlen( $r->description) > 2000000 ||  ( !in_array($ad, $adataimage) && isset($get['sid'])) ){
                                  
                                                                         $r->description =  preg_replace("/<img[^>]+\>/i", " ",  $r->description);
                                                                  }
                                                                  
                                                                
                                                                 if( $get['getonlyintro'] == 1 || in_array($ad, $adataintro)  ){
                                                                       preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $r->description , $img);
                                                                        $aimg = explode(' ', $img[1]);
                                                                       if ($aimg[0] != '' ) {
                                                                       echo '<p style="text-align:center;"><img src="'.$aimg[0].'" width="600" style="max-width: 600px;" /></p>';
                                                                       }
                                                                      $r->description = str_replace('<p> </p>', '', $r->description) ; 
                                                                      $r->description = strip_tags ($r->description,"<p>");
                                                                      $aadr = explode('</p>', $r->description );
                                                                        foreach ($aadr as $lm){
                                                                             if (strlen($lm) > 100){
                                                                                     echo $lm.'</p>';
                                                                                     break;
                                                                             }
                                                                         }
                                                                  }else {
                                                                         
                                                                        echo $r->description;
                                                                  
                                                                  }
                                                                  
                                                                     
                                                                  ?> 
                                                          </div> 
                                                          <a style="  <?php  if($get['getonlyintro'] != 1 || in_array($ad, $adataintro) ){ ?> display: none; <?php } ?>  font-family: sans-serif;    font-size: 19px;    margin-top: 17px;    text-align: right;    text-decoration: none;" id="link_article_content_<?php echo $num_i; ?>" target="_blank" href="<?php echo $articlelink; ?>" > <span style="    background-color: #000;    padding: 10px;    border-radius: 10px;    color: #fff;   ">Read More</span></a>
                                                      </td>
                                                     
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </td>
                                  </tr>  
                                  <?php
                                     }} ?>  
    
    </tbody></table>
<?php
               
             
               die;
           }
           
           
           public function saveapicontent(){
                $db = JFactory::getDbo();
                $post =  JRequest::get('post');
              
                $dom = new SmartDOMDocument();
    		$dom->loadHTML($_POST['content']); 
                $a1 = $dom->getElementById('api-title');
                 if($a1){
                   $title =  $a1->nodeValue;  
                   $a1->parentNode->removeChild($a1);
                 }          
                 $content =  $dom->saveHTML();
                
                 if( $title != ''){
                     $aat = " , article_title = '".$db->escape($title)."'";
                 }else {
                     $aat = '';
                 }
               
                 $query = $db->getquery(true);
                 $query->update("#__apifbc")->set("fullcontent = '".$db->escape($content)."'  $aat  ")->where("article_id = '".$db->escape($post['idcontent'])."' ");
                 file_put_contents(__DIR__.'/test.html', $query);
                 $db->setquery($query);                                                
                 $db->query();                  
                                                
                 $query = $db->getquery(true);
                 $query->update("#__apifnc")->set("fullcontent = '".$db->escape($content)."' $aat ")->where("article_id = '".$db->escape($post['idcontent'])."' ");
                 $db->setquery($query);
                 $db->query();
                                                
                die;
           }
           
           public function deletepoll(){
               
                $db = JFactory::getDbo();
                $post =  JRequest::get('post');
                $config = new JConfig();
                $extoption['driver']   = $config->dbtype;
                $extoption['host']     = $config->host;
                $extoption['user']     = $config->user;    
                $extoption['password'] = $config->password;   
                $extoption['database'] = 'centcom';
                $extoption['prefix']   = 'api_';
                $Externaldb = & JDatabase::getInstance($extoption );
                $database = $config->db;

                $EXT_query      = "SELECT * FROM ".$extoption['prefix']."apisc WHERE site_database='".$database."'";   
                $Externaldb->setQuery($EXT_query);                      
                $id_site  = $Externaldb->loadObject()->id; 
                
                $EXT_query=" Update ".$extoption['prefix']."acepolls_polls Set published = 0 where id = '".$post['id']."' and id_site = '$id_site' ";
                $Externaldb->setQuery($EXT_query);            
                $Externaldb->query();
                
                
           }


           public function uploadpoll(){
               
               
                $db = JFactory::getDbo();
                $post =  JRequest::get('post');
                $config = new JConfig();
                $extoption['driver']   = $config->dbtype;
                $extoption['host']     = $config->host;
                $extoption['user']     = $config->user;    
                $extoption['password'] = $config->password;   
                $extoption['database'] = 'centcom';
                $extoption['prefix']   = 'api_';
                $Externaldb = & JDatabase::getInstance($extoption );
                $database = $config->db;

                $EXT_query      = "SELECT * FROM ".$extoption['prefix']."apisc WHERE site_database='".$database."'";   
                $Externaldb->setQuery($EXT_query);                      
                $id_site  = $Externaldb->loadObject()->id; 


                $pas = '{"only_registered":"0","one_vote_per_user":"1","ip_check":"0","show_component_msg":"1","allow_voting":"1","show_what":"1","show_hits":"1","show_voters":"1","show_times":"1","show_dropdown":"1","show_title":"1","opacity":"90","bg_color":"ffffff","circle_color":"505050","pieX":"100%","pieY":"400","start_angle":"55","radius":"150","gradient":"1","no_labels":"0","show_zero_votes":"1","animation_type":"bounce","bounce_dinstance":"30","bg_image":"-1","bg_image_x":"left","bg_image_y":"top","font_size":"11","font_color":"404040","title_lenght":"30","chartX":"100%","optionsFontSize":"12","barHeight":"15","barBorder":"1px solid #000000","bgBarColor":"f5f5f5","bgBarBorder":"1px solid #cccccc"}';

                $ailias=JFilterOutput::stringURLSafe($post['quest']);

                $EXT_query=" INSERT INTO ".$extoption['prefix']."acepolls_polls (`id`, `title`, `alias`, `checked_out`, `checked_out_time`, `published`, `publish_up`, `publish_down`, `params`, `access`, `lag`, `id_site`) VALUES (NULL, '".$db->escape($post['quest'])."', '$ailias', '0', '0000-00-00 00:00:00', '1', now(), '0000-00-00 00:00:00', '".$db->escape($pas)."', '1', '1440', '".$id_site."') ";
                $Externaldb->setQuery($EXT_query);            
                $Externaldb->query();
                $idpoll =  $Externaldb->insertid();

                $EXT_query=" INSERT INTO ".$extoption['prefix']."acepolls_options (`id`, `poll_id`, `text`, `link`, `color`, `ordering`) VALUES (NULL, $idpoll, 'true', NULL, 'ff0000', 0), (NULL, $idpoll, 'false', NULL, 'ffff99', 1); ";
                $Externaldb->setQuery($EXT_query);            
                $Externaldb->query();

                #HT
				$result = array('status' => 1);
				$result['id'] = $Externaldb->insertid();
				$result['title'] = $post['quest'];
				echo json_encode($result);
                die;
               
           }

           public function uploadfile(){
               
                if ($_POST["label"]) {
                    $label = $_POST["label"];
                }
             
                $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $_FILES["file"]["name"]);
                $extension = end($temp);
                if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/x-png")
                || ($_FILES["file"]["type"] == "image/png"))
                && ($_FILES["file"]["size"] < 200000)
                && in_array($extension, $allowedExts)) {
                    if ($_FILES["file"]["error"] > 0) {
                        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
                    } else {
                        jimport('joomla.filesystem.file');
                        jimport('joomla.filesystem.folder');
                        $fieldName = 'file';

                          //check the file extension is ok
                        $fileName = $_FILES[$fieldName]['name'];
                        $uploadedFileNameParts = explode('.',$fileName);
                        $duoifile = $uploadedFileNameParts[1];
                        $uploadedFileExtension = array_pop($uploadedFileNameParts);

                        $validFileExts = explode(',', 'jpeg,jpg,png,gif');

                        //assume the extension is false until we know its ok
                        $extOk = false;

                        //go through every ok extension, if the ok extension matches the file extension (case insensitive)
                        //then the file extension is ok
                        foreach($validFileExts as $key => $value)
                        {
                                if( preg_match("/$value/i", $uploadedFileExtension ) )
                                {
                                        $extOk = true;
                                }
                        }

                        if ($extOk == false) 
                        {
                                echo JText::_( 'INVALID EXTENSION' );
                                return;
                        }

                        //the name of the file in PHP's temp directory that we are going to move to our folder
                        $fileTemp = $_FILES[$fieldName]['tmp_name'];

                        //for security purposes, we will also do a getimagesize on the temp file (before we have moved it 
                        //to the folder) to check the MIME type of the file, and whether it has a width and height
                        $imageinfo = getimagesize($fileTemp);

                        //we are going to define what file extensions/MIMEs are ok, and only let these ones in (whitelisting), rather than try to scan for bad
                        //types, where we might miss one (whitelisting is always better than blacklisting) 
                        $okMIMETypes = 'image/jpeg,image/pjpeg,image/png,image/x-png,image/gif';
                        $validFileTypes = explode(",", $okMIMETypes);		

                        //if the temp file does not have a width or a height, or it has a non ok MIME, return
                        if( !is_int($imageinfo[0]) || !is_int($imageinfo[1]) ||  !in_array($imageinfo['mime'], $validFileTypes) )
                        {
                                echo JText::_( 'INVALID FILETYPE' );
                                return;
                        }

                        //lose any special characters in the filename
                        $fileName = preg_replace("/[^A-Za-z0-9]/i", "-", $fileName);
                        $fn = rand(0,10000000000).".".$duoifile;
                        //always use constants when making file paths, to avoid the possibilty of remote file inclusion
                        $uploadPath = JPATH_ROOT.'/media/com_enewsletter/upload/'.$fn;

                        if(!JFile::upload($fileTemp, $uploadPath)) 
                        {
                                echo JText::_( 'ERROR MOVING FILE' );
                                return;
                        }
                        else
                        {
                            echo JURI::base().'media/com_enewsletter/upload/'.$fn;
                           // success, exit with code 0 for Mac users, otherwise they receive an IO Error
                           exit(0);
                        }
                    }
                } else {
                    echo "Invalid file";
                }
                die;
     }
    public function getverifiedemaillist(){
      $api = JRequest::getVar('apitype', '','post', '');
      $apikey = JRequest::getVar('apikey', '','post', '');
      $app = JFactory::getApplication();
      $from_email = $app->getUserState("com_enewsletter.advisoremail"); 
      $APIKEY  = CONSTANT_APIKEY;
      if($api == 'C'){
           $cc = new ConstantContact($APIKEY);
            try{
            	$emails = $cc->getVerifiedEmailAddresses($apikey);
              echo '<select id="verified_emails" name="verified_emails" onChange="fill_email(this)" style="width:290px;"  >';  
               if(count($emails) > 1){
                echo '<option value="" >Select List</option>';
              }
              foreach($emails as $e){
                if($e->status == 'CONFIRMED'){
                  if($from_email == $e->email_address){
                    $selected = "selected";
                  }else{
                    $selected = '';
                  }
                   
                    echo '<option value="'.$e->email_address.'" '.$selected.'  >'.$e->email_address.'</option>';
                }
              }
              echo '</select>'; 
            }catch (CtctException $ex) {
              echo 'error';
            	die();
            }
      }else if($api == 'M') {

          $mapi = new MCAPI(trim($apikey));
          $emails = $mapi->getVerifiedDomains();
           
          if ($mapi->errorCode){
            echo 'error';
          	die();
          }else{
          
              $email_name = '';
              if($from_email){
                  $email_names = explode('@',$from_email);
                  $email_name = $email_names[0];
              }
              
              echo '<input type="text" name="jform[verified_email_name]" value="'.trim($email_name).'" style="float:none;margin:5px 0 0 0;" id="verified_email_name" />@';
              echo '<select id="verified_emails" name="verified_emails" style="float:right;"  onchange="fill_email(this)" >';
              if(count($emails) > 1){
                echo '<option value="" >Select List</option>';
              }
              foreach($emails as $e){    
                         
                if($e['status'] == 'verified') {
                     
                  if(trim($email_names[1]) == $e['domain']){
                      $selected = "selected";
                    }else{
                      $selected = '';
                    }
                    echo '<option value="'.$e['domain'].'" '.$selected.' >'.$e['domain'].'</option>';
                    
                }                
              } 
              echo '</select>';   
          } 
      }exit;
  }


	public function display()
	{
            	$input = JFactory::getApplication()->input;
                $viewName = $input->get('view', 'editletter');
		$input->set('view', $viewName);
                
		parent::display();
	}
        function send (){
            
                $app = JFactory::getApplication();
		
		// Get all data of current page
		$data = JRequest::getVar('jform', array(), 'request', 'array');
                $post = JRequest::get('post');
                $api=$post['newsletter_api'];
		$APIKEY  = CONSTANT_APIKEY;
		$ACCESS_TOKEN = $post['apikey'];     
               
                if($api == 'M'){
        	$from_email_address = trim($data['verified_email_name']).'@'.trim($from_email_address);
                  }
                $from_email_address = JRequest::getVar('verified_emails');
		// Get all details of logged user
		$loggeduser = JFactory::getUser();
		
		// Get api token key to access web service
		$apitoken = $app->getUserState("com_enewsletter.APIToken");
		
		// Get all selected article's ids
		$cid =  JRequest::getVar('cid');  
                $articles_ids = implode(',', $cid);     
                $ids = implode('" , "',$cid);
  
                // Get ids of selected images from article list 
		$showimage_ids = $showimage_id = JRequest::getVar('sid');
		//$showimage_ids = explode(',',$showimage_id);	
                               
		// Get all selected group's ids
		$gid =  JRequest::getVar('gid');		
    
                
		
                // Get Enewsletter model
		//$emodel = $this->getModel('Enewsletter');
                
		// Get current user's enewsletter details
		//$advisordetails = $emodel->getAdvisordetails();
		
                $db = JFactory::getDBO();
                $query = 'SELECT * FROM #__advisorsettings';
                $db->setQuery($query);  
                $advisordetails = $db->loadObject();
                $app->setUserState("com_enewsletter.advisorname",$advisordetails->from_name);
                $app->setUserState("com_enewsletter.advisoremail",$advisordetails->from_email);
                $app->setUserState("com_enewsletter.testemail",$advisordetails->email);
                $app->setUserState("com_enewsletter.weeklyupdategroup",$wgroups); 
                $app->setUserState("com_enewsletter.ACCESS_TOKEN",$advisordetails->api_key);
                $app->setUserState("com_enewsletter.API",$advisordetails->newsletter_api);        
                $app->setUserState("com_enewsletter.Weekly_subject",$advisordetails->update_subject);
                $app->setUserState("com_enewsletter.Weekly_intro",$advisordetails->weekly_update_intro);
                $app->setUserState("com_enewsletter.custom_link_article",$advisordetails->custom_link_article);
                $app->setUserState("com_enewsletter.path_quote",$advisordetails->path_quote);
                $app->setUserState("com_enewsletter.newsletter_default_template",$advisordetails->newsletter_default_template);
                $app->setUserState("com_enewsletter.weeklyupdate_default_template",$advisordetails->weeklyupdate_default_template);
                $app->setUserState("com_enewsletter.massemail_default_template",$advisordetails->massemail_default_template);
                $template_array = array($advisordetails->newsletter_default_template,$advisordetails->weeklyupdate_default_template,$advisordetails->massemail_default_template);
                $app->setUserState("com_enewsletter.template_array",$template_array);
           //     echo  $app->getUserState("com_enewsletter.advisoremail");die;
		// Get the model.
		$model = $this->getModel();
		
		// Get all articles from com_fnc and com_fbc table using artilce ids
		$articles = $model->getArticle($ids);
		
		$articlecontent = '';

                // Get all templates of newsletter type
              
                if ($post['changetemps']){
                    $fn = explode('.', $post['changetemps']);
                     $ca =  file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$post['changetemps'].'.html');                   
                    if (!$ca){
                      $templates = 'enewsletter_defaults.html';
                    }else{
                      $templates =  $post['changetemps'].'.html';
                    }
                }

                
                $NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates);

               // echo $NEWSLETTER;
    
    // Create and replace content for selected articles start
                $articleimgarray = array();
                $j  =1;
                foreach($articles as $ar){		
		 // Create article content and more link  start
		  $articlelink='';
			  
		  $custom_link_article = $advisordetails->custom_link_article;
		  $valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
		  
		  if($valid_format) {
			$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($advisordetails->custom_link_article));
		  }
		  else
		  {
			if($ar->type == 'Featured News') 
			{
			  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$ar->article_id ;
			}
			else if($ar->type == 'Financial Briefs')
			{
			  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$ar->article_id;
			}
			else
			{
			  $articlelink='';
			}
			
		  }
		
		  // Set article link for more in description	
		  if ( $articlelink !='')      
		  {
			$titlelink =  '<a href="'.$articlelink .'" target="_blank" > more...</a>';
		  }
		  else
		  {
			$titlelink = '';
		  }
			$articlebody = $ar->description.'<br/>'.$titlelink;
		 // Create article content and more link  end
		  
		  if(trim($articlelink) ==''){
			$articlelink = 'javascript:void(0);';	
		  }
		  
		  $ar->articlelink =  $articlelink;
		  
		  
		  //Create article image  path start  
		  if(in_array($ar->article_id,$showimage_ids) && $ar->slideshowimage != '' && $ar->slideshowimage != '0000-00-00 00:00:00') {       
			  $path = $this->create_image($ar->slideshowimage);
			  $path =  JURI::base().'media/com_enewsletter/article_images/'.$path; 
			  if($path != ''){
					$ar->image =  $path;
				}else{
					$ar->image = '';
				}          
			  } else{
				 $ar->image = '';
			}
	//Create article image  path end
			  
			$ar->description = $ar->description.'<br/>'.$titlelink;
			$j++; 

		}
		
		
		//exit;
                $data['id'] = '';	
                $data['email_id'] = '';	
   	
		// Assigns all articles to $data variable
		$data['articles'] = $articles;		
		// Get and assigns current task (send)
		$data['task'] =  JRequest::getVar('task');		
		
		// Set all selected articles id's to state 
		$app->setUserState("com_enewsletter.cid",$cid);	
                
		
		// Set all selected groups id's to state 
		$app->setUserState("com_enewsletter.gid",$gid);
    
   	 	$app->setUserState("com_enewsletter.showimage_ids",$showimage_ids);
		
		// Set current form's data to state 
		$app->setUserState("com_enewsletter.data",$data);
	
		
		// Replace title, intro, trailer, disclosure placeholders from template start
		$footer = '';
                JLoader::register('enewsletterHelper', JPATH_COMPONENT_ADMINISTRATOR . 'com_enewsletter/helpers/template.php');		
		//$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
                $content = $NEWSLETTER;
                
                $content = str_replace('src="'.JURI::base().'data:image', 'src="data:image', $content);                
                $posss = strpos($content,'Intro Enter Here ...');
                if( $posss !== false) {
                    $content = str_replace('bgcolor="#ECEBE0"', 'style="display: none;"', $content);  
                     $content = str_replace('padding: 25px;', 'padding: 25px;padding-top:2px;', $content); 
                     $content = str_replace('margin-bottom: 50px;', ' margin-bottom: 10px;', $content); 
                    
                }
               
                // add css in file
                $addcssscript = file_get_contents(JPATH_SITE."/components/com_enewsletter/assets/newsletter.css");
                $addcssscript = '<style>'.$addcssscript.'</style>';
                $content = $addcssscript.$content;
                $content = str_replace('noconfig="1"', 'ids="cbvnncb"', $content);     
                $content = str_replace('display: none;', '"ids="cbvnncb""', $content);
               
                  $dom = new SmartDOMDocument();    
                  $dom->loadHTML($content);  
                  
                  $links = $dom->getElementsByTagName('div');
                  
                 // print_r($content);die;
                    $i=1;
                    foreach ($links as $link){ 
                        
                        if ($link->getAttribute('ids') == 'cbvnncb'){
                             $link->removeAttribute('id');
                             $link->setAttribute("id", 'aabbcc'.$i);
                            //  $link->removeChild($link);
                              $i++;
                        }                       
                    }
                    for ($j = ( $i - 1 ) ; $j >= 1  ;$j-- ){
                        
                        $a3 = $dom->getElementById('aabbcc'.$j);
                     
                        if( $a3 != NULL ){
                             $a3->parentNode->removeChild($a3);
                        }
                       
                    }
                   
                    
                    
                    $linkas = $dom->getElementsByTagName('a');
                    $k=1;
                    foreach ($linkas as $linka){ 
                        
                        if ($linka->getAttribute('ids') == 'cbvnncb'){
                             $linka->removeAttribute('id');
                             $linka->setAttribute("id", 'aabbccss'.$k);
                        
                              $k++;
                        }                       
                    }
                     
                    for ($j = 1 ; $j< $k ;$j++ ){
                        $a4 = $dom->getElementById('aabbccss'.$j); 
                         if($a4 != NULL){
                            $a4->parentNode->removeChild($a4);
                         }
                    }
                    
                     
                    
                    
                     $content =  $dom->saveHTML();                     
                     $content = "<div style='color:#fff;  background: #fff;  opacity: 0;  visibility: hidden;'  >".$data['subject']."</div>".$content;   
                //    print_r($content);die;
                 
                //  echo $content;die;
           
               
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['intro']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
			}
		}//for
		$data['intro'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['intro'] = $mock->saveHTML();
		
		
		$dom = new SmartDOMDocument();
		$dom->loadHTML($data['trailer']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
			}
		}//for
		$data['trailer'] = $dom->saveHTML();
		
		$mock = new SmartDOMDocument;
		$body = $dom->getElementsByTagName('body')->item(0);
		foreach ($body->childNodes as $child){
			$mock->appendChild($mock->importNode($child, true));
		}
		$data['trailer'] = $mock->saveHTML();

                if ($api == 'G') {
			
                                        $api_url = 'http://api2.getresponse.com';
                                        $client = new jsonRPCClient($api_url);

                                        $details = $client->send_newsletter(
                                                       $ACCESS_TOKEN,
                                                       array (
                                                            'campaign' => $gid[0],
                                                            'subject'  => $data['update_subject'],
                                                            'name'     => date('Ymd-h:i:s'),
                                                            "contents" => array('html' => $content),
                                                            'get_contacts' => array('campaigns' => $gid)
                                                       )
                                        );

                                        if ($details && !$details['error'] && $details['result']) {
                                                $return = $details['result'];
                                        } else {
                                                if ($details['error']) {
                                                        $this->setMessage($details['error']['message']." - (from GetResponse)",'error');
                                                } else {
                                                        $this->setMessage('send weeklyupdate error');
                                                }//if

                                                $this->setRedirect('index.php');
                                                return;

                                        }//if

                       } else if ($api == 'I') {                    
		
			$infusionsoft_host = $advisordetails->api_login_name;
				
			$infusionsoft_api_key = $ACCESS_TOKEN;
			
			require JPATH_SITE.'/administrator/components/com_enewsletter/libraries/infusionsoft/infusionsoft.php';
			
			$contacts = Infusionsoft_DataService::query(new Infusionsoft_Contact(), array('Company' => 'api'));
			$email_a = array();
			foreach ($contacts as $contact) {
				$email_a[$contact->Id] = $contact->Email;
			}//for
			
			if (count($email_a)) {
				
				$contactFromApp1 = array_shift($contacts);
				
				$fromAddress = $advisordetails->from_email;
				$toAddress = $contactFromApp1->Email;
				$ccAddresses = $advisordetails->from_email;
				$bccAddresses = $advisordetails->from_email;
				$contentType = 'HTML';
				$subject = $data['subject'];
				$htmlBody = $content;
				$textBody = strip_tags($content);
				
			
				$return = Infusionsoft_EmailService::sendEmail(array_keys($email_a), $fromAddress, $toAddress, $ccAddresses, $bccAddresses, $contentType, $subject, $htmlBody, $textBody);
				
				if (!$return) {
                                        
                                        $app->setUserState("com_enewsletter.meess",'send enewsletter error');	
                                        $this->setRedirect('index.php?option=com_enewsletter&view=editletter');
					return;
				}//if
				
			}//if	
                    // get mailing list    
		} else if($api == 'M'){
                    
			$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
			$type = 'regular';
			$campaign_title = JFactory::getConfig()->get( 'sitename' ).'_'.time();
			$campaign_title =  substr($campaign_title, 0, 78);
			$opts['title'] = $campaign_title;
			$opts['subject'] = $data['subject'];
                        if($from_email_address){
                          $opts['from_email'] = $from_email_address;
                        }else{
                              $opts['from_email'] = $app->getUserState("com_enewsletter.advisoremail");
                        }
			$opts['from_name'] = $app->getUserState("com_enewsletter.advisorname");				
			$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
			$opts['authenticate'] = true;			
			$email_content = array('html'=>$content, 
									'html_footer' => 'the footer with an *|UNSUB|* message' );
			
			$email_ids = array();
			foreach($gid as $g){			
				$opts['list_id'] = $g;							
				$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
				if ($mailchimp->errorCode){
					
                                        $app->setUserState("com_enewsletter.meess",JText::_($mailchimp->errorMessage). " - (from Mail Chimp)");	
                                        $this->setRedirect('index.php?option=com_enewsletter&view=editletter');
					return;
				} else 
				{
					$return = $mailchimp->campaignSendNow($retval);
					if ($mailchimp->errorCode){
                                               $app->setUserState("com_enewsletter.meess",JText::_($mailchimp->errorMessage). " - (from Mail Chimp)");	
                                               $this->setRedirect('index.php?option=com_enewsletter&view=editletter');
                                               return;
					} else {			
                                               $email_ids[] = $retval;	
					} 
				}	
				
			} 
			
			$data['email_id'] = implode(',',$email_ids);			
                        // get mailing list
                        
		}else if($api == 'C'){

			$cc = new ConstantContact($APIKEY);	
			$campaign = new Campaign();			
			$campaign_title = JFactory::getConfig()->get( 'sitename' ).'_'.time();
			$campaign_title = substr($campaign_title, 0, 78);	
			$campaign->name = $campaign_title;
			$campaign->from_name = $app->getUserState("com_enewsletter.advisorname");
                      
			  if($from_email_address){
				  $campaign->from_email = $from_email_address;
				  $campaign->reply_to_email = $from_email_address;
			  }else{
				$campaign->from_email = $app->getUserState("com_enewsletter.advisoremail");
				$campaign->reply_to_email = $app->getUserState("com_enewsletter.advisoremail"); 
			  }	    
	         
			$campaign->subject = $data['subject'];
			$campaign->greeting_string = $data['subject'];			
			$campaign->text_content = $data['subject'];			
			$campaign->email_content_format = 'HTML';
			$campaign->email_content = $content;

			
			foreach($gid as $g){
				$campaign->addList($g);
			}  
			try{
				$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
				try{
					$schedule = new Schedule();
					$cc->addEmailCampaignSchedule($ACCESS_TOKEN, $return->id, $schedule);
					$data['email_id'] = $return->id;
				}catch (CtctException $ex) {
					$ccerror = $ex->getErrors();
					
                                        $app->setUserState("com_enewsletter.meess",JText::_($ccerror[0]['error_message'])." - (from Constant Contact)");	
                                        $this->setRedirect('index.php?option=com_enewsletter&view=editletter');
					return;
				}
				
			}catch (CtctException $ex) {
				$ccerror = $ex->getErrors();
                                $app->setUserState("com_enewsletter.meess",JText::_($ccerror[0]['error_message'])." - (from Constant Contact)");	
                                $this->setRedirect('index.php?option=com_enewsletter&view=editletter');
                                return;
			}
                        // get mailing list
		}
		
		
		if(!empty($return)){			
			
			// Assigns form data in $data variable to save/update
			$data['title'] = $data['title'];
			$data['subject'] = $data['subject'];
			$data['intro'] = $data['intro'];
			$data['trailer'] = $data['trailer'];
			$data['mass_email_content'] ='';	
			$data['type'] = 'enewsletter';
			$data['api_type'] = $api;	
			$data['is_active'] = '1';		
			$data['email_sent_status'] = '1';	
			$data['content'] = $content;			
		
      // get Buildnewsletter model
			$model = $this->getModel();	
      
      		// Save/update enewsletter data	
			$db = JFactory::getDBO();			
			if($data['id'] != ''){
				//$result = $model->updateNewsletter($data, $cid, $gid, $showimage_ids);
				$query = $db->getQuery(true);
				$query->update('#__enewsletter');
				$query->set('email_id = '.$db->Quote($data['email_id']));				
				$query->set('`title` = '.$db->Quote($data['title']));
				$query->set('`subject` = '.$db->Quote($data['subject']));
				$query->set('`intro` = '.$db->Quote($data['intro']));
				$query->set('`trailer` = '.$db->Quote($data['trailer']));
				$query->set('`content` = '.$db->Quote($data['content']));
				
				$query->set('email_sent_status = 1');
				$query->where('id = '.(int)$data['id']);
				$db->setQuery($query);
				$db->execute();
				
				$result = (int)$data['id'];	
			}else{		
				$result = $model->saveNewsletter($data, $cid, $gid, $showimage_ids);
			}
			
			
			// Assigns and store data in history table after creating and sending campaign
			$historydata = array();
			$historydata['title'] = $data['title'];
			$historydata['campaign_title'] = $campaign_title;
			$historydata['subject'] = $data['subject'];
			$historydata['content'] = $content;
			$historydata['e_id'] = $result;
			$historydata['email_id'] = $data['email_id'];
			$historydata['api_type'] = $api;
						
			//$historymodel = $this->getModel('History');
			//$historyreturn = $historymodel->saveHistory($historydata);	
                        
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$userid = JFactory::getUser()->id;
			$insertquery = "INSERT INTO #__enewsletter_history(id,user_id,title,campaign_title,subject,content,e_id,email_id,api_type,dte_send,mailinglist) 
					   VALUES('',".intval($userid).",".$db->quote($historydata['title']).",'".$historydata['campaign_title']."',".$db->quote($historydata['subject']).",".$db->quote($historydata['content']).", ".$historydata['e_id'].", '".$historydata['email_id']."','".$historydata['api_type']."',now(),'')";
			$db->setQuery($insertquery);
			$result =  $db->query();
			$id = $db->insertid();
                        
			
			/* Send mail to archive list start */
			if($advisordetails->archive_cc_list != '' && $advisordetails->archive_cc_list != null){
				if($api == 'C'){
					$listcontacts = '<center><br>Newsletter sent to :<br><br><table border="1"><th> </th><th>Name</th><th>Time</th>';
					$curtime = date('m-d-Y h:m:i');
					$i = 1;
					foreach($gid as $g){
						$contacts = $cc->getContactsFromList($ACCESS_TOKEN,$g);
						
						foreach($contacts->results as $con){
							$listcontacts .= '<tr>';
							if($con->status == 'ACTIVE'){
								$listcontacts .= '<td>'.$i.'</td>';
								$listcontacts .= '<td>';
								$listcontacts .= $con->email_addresses[0]->email_address.'</td>';
								$listcontacts .= '<td>'.$curtime.'</td>';
								$i++;
							}
							$listcontacts .= '</tr>';
						}
						
					}
					$listcontacts .= '</table></center>';
				}else if($api == 'M'){
					$listcontacts = '<center><br>Newsletter sent to :<br><br><table border="1"><th> </th><th>Name</th><th>Time</th>';
					$curtime = date('m-d-Y h:m:i');
					$i = 1;
					foreach($gid as $g){
						$contacts = $mailchimp->listMembers($g);
						foreach($contacts['data'] as $con){
							$listcontacts .= '<tr>';
							$listcontacts .= '<td>'.$i.'</td>';
							$listcontacts .= '<td>';
							$listcontacts .= $con['email'].'</td>';
							$listcontacts .= '<td>'.$curtime.'</td>';
							$listcontacts .= '</tr>';
							$i++;
						}
					}
					$listcontacts .= '</table></center>';
				}
				
				$mailer = JFactory::getMailer();
				$config = JFactory::getConfig();
                                if($from_email_address == ''){
                                   $from_email_address =  $app->getUserState("com_enewsletter.advisoremail");
                                }
                                        $sender = array( 
					$from_email_address,
					$app->getUserState("com_enewsletter.advisorname")
					 );	
					 
				$mailer->setSender($sender);
				$recipient = explode(',',$advisordetails->archive_cc_list);	
		
				$mailer->addRecipient($recipient);
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';
				$mailer->setSubject($data['subject']);
				$mailer->setBody($content.$listcontacts);
				$send = $mailer->Send();

				if ( $send !== true ) {
					$this->setMessage(JText::_('Error sending test email.'),'error');
				} else {
					$this->setMessage(JText::_('Mail sent to Archive List Successfully.'));
				}
			}
			/* Send mail to archive list end */		
                   
                     $app->setUserState("com_enewsletter.meess",JText::_('E-Newsletter sent to Email Campaign system successfully.'));
		}else{
                     $app->setUserState("com_enewsletter.meess",JText::_('E-Newsletter Not Sent.'));
		}
                           
		$this->setRedirect('index.php?option=com_enewsletter&view=editletter');
                //$this->setRedirect('index.php');
        }
        
        function savehtml(){
                                
            $post = JRequest::get('post');
         
            $html=JRequest::getVar( 'htmlcode', '', 'post', 'string', JREQUEST_ALLOWHTML );
            if($html == ''){
                 $html=JRequest::getVar( 'htmlcode', '', 'get', 'string', JREQUEST_ALLOWHTML );
            }
                
            $html = '<div id="isdatahtml">'.$html;
            $html = '<link rel="stylesheet" href="'.$post['csslink'].'">'.$html;
            $html = $html.'</div>';
            $file = $post['filen'].'_'.$post['changetemps'].'.html'; 
            $filename = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$file;
            $fp = fopen($filename,"wb");
            fwrite($fp,$html);
            fclose($fp);
            $this->setRedirect(JRoute::_( JURI::base().'index.php?option=com_enewsletter&view=editletter&tmpl=component&idt='.$post['idt'],TRUE));
         
             
        }
        function base64_to_jpeg($base64_string, $output_file) {
            $img = $base64_string;
         
             $img = str_replace('data:image/png;base64,', '', $img);
             $img = str_replace(' ', '+', $img);
          
             $data = base64_decode($img); 
             $success = file_put_contents($output_file, $data);
        

            return true; 
        }
        function savepng(){
         
             $html=JRequest::getVar( 'imgcode', '', 'post', 'string', JREQUEST_ALLOWHTML );
            if($html == ''){
                 $html=JRequest::getVar( 'imgcode', '', 'get', 'string', JREQUEST_ALLOWHTML );
            }
         
           $r =  rand(1, 100000000);
           $id = JFactory::getUser();
           $id_user = $id->id;
           $url = JPATH_SITE.'/media/com_enewsletter/upload/'.$id_user.'_'.$r.'.png';
		   
		   if (!is_dir(JPATH_SITE.'/media/com_enewsletter/upload/')) mkdir(JPATH_SITE.'/media/com_enewsletter/upload');
        
           $this->base64_to_jpeg($html,$url);
           echo juri::base().'media/com_enewsletter/upload/'.$id_user.'_'.$r.'.png';
           die;
        }
        

        public function getModel($name = 'Subscription', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	/**
	 * Used to end weekly update mail whne cron task runs.
	 *
	 * @return void
	 */
    // #khoa
	public function sendweeklytest(){
            
		$get = JRequest::get('get');
		if ( $get['emailsendout'] != '' ) {
			
			$model = $this->getModel();	
			$config = new JConfig();
			$host = $config->host;
			$user = $config->user;
			$password = $config->password;
			$database = $config->db;
			$dbprefix = $config->dbprefix;
			$error = '';
			$sqlerror = '';
			$webapierror = '';
			$errorcnt = 0;
		
		
			/* connect to database and get advisor details start */
			mysql_connect($host,$user,$password) or die(mysql_error());
			mysql_select_db($database)or die(mysql_error());
		
			$selectquery = "SELECT * FROM ".$dbprefix."advisorsettings limit 1";
			$result = mysql_query($selectquery);
			if (mysql_errno()) {
				$errorcnt++;
				$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectquery."\r\n";
			} 
			$advisordetails = mysql_fetch_assoc($result);

			/* connect to database and get advisor details end */
		
			/* Get weekly update groups start */
			$selectgroupquery = "SELECT * FROM ".$dbprefix."weeklyupdate_group";
			$groupresult = mysql_query($selectgroupquery);
			if (mysql_errno()) {
				$errorcnt++;
				$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectgroupquery."\r\n";
			} 
		
			$numrow = mysql_num_rows($groupresult);		
			// Set weekly update mail content      
                        $filecontent = '';
		
		
		if ( strpos($_SERVER['HTTP_HOST'], 'localhost')!==false ) $orgid = 58;
		
		
		if( $advisordetails && $numrow && $advisordetails['auto_update'] == 'Y' 
			&& $advisordetails['firm'] && $advisordetails['url'] && $advisordetails['path_quote'] && $advisordetails['custom_link_article'] 
			&& $advisordetails['address1'] && $advisordetails['city'] && $advisordetails['zip'] && $advisordetails['state'] ){
		
			  $filecontent .= "=======================================================\r\n";
			  $filecontent .= "Output of site : ".$orgdata->primary_domain."\r\r\n";
			  $filecontent .= "=======================================================\r\n";
			  
			  $app = JFactory::getApplication();
			  $model->setWeeklyUpdateContent($advisordetails);
			  $cid = array();
			  $weeklyupdate = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
			  $cid = $app->getUserState("com_enewsletter.Weeklyupdatearticleid");	
			  
			 
			  JLoader::import('enewsletter', JPATH_ROOT . '/administrator/components/com_enewsletter/models');
			  $emodel = JModel::getInstance('Enewsletter', 'EnewsletterModel');
			  
			  if($weeklyupdate){
				
				$articlecontent = $weeklyupdate;				 
				$sqlserver_user = sqlserver_user;
				$sqlserver_pass = sqlserver_pass;
				$sqlserver_db =  "ArticleContent";
				$sqlserver_host = sqlserver_host; 
				
				$connectionInfo = array("UID" => $sqlserver_user, "PWD" => $sqlserver_pass, "Database"=>$sqlserver_db);	
				$serverName = $sqlserver_host;
				$conn = sqlsrv_connect( $serverName, $connectionInfo);	
				
				
				
				$dom=new SmartDOMDocument();
				$dom->loadHTML($articlecontent);
				$mock = new SmartDOMDocument();
				$body = $dom->getElementsByTagName('body')->item(0);
				foreach ($body->childNodes as $child){
					$mock->appendChild($mock->importNode($child, true));
                        }  
                                $articlecontent = $mock->saveHTML(); 
				
				
				$newarr=array();
				$anchor = $dom->getElementsByTagName('a');
		
				// Need article id of all anchors available in WeeklyContent
				foreach($anchor as $a){			
					$href = $a->getAttribute('href');							
					$href = parse_url($href);							
					parse_str($href['query'], $query);															
					if(!empty($query['id'])){
						array_push($newarr, $query['id']);	
					}//if
				}//for
				
				for ($h=0; $h < count($newarr) ; $h++) { 
					
					$sql="SELECT * FROM article_featured_image where article_id=".$newarr[$h];		
					$sqldata = sqlsrv_query($conn, $sql);
					$images = sqlsrv_fetch_array( $sqldata, SQLSRV_FETCH_ASSOC);		
					$img = $this->create_image('https://contentengine.advisorproducts.com/'.$images['slideshow_image']);
					$final_img = JURI::root().'media/com_enewsletter/images/'.$img;
						
					// Replace images over commented word
					$articlecontent = preg_replace('/<!--#APICONTENTIMAGE#-->/', "<img style='margin:8px;border:1px #000 solid;' width='100%' src='".$final_img."'", $articlecontent, 1);
				}//for
				

			}	else{
				  $articlecontent = '';
				  $webapierror .= 'Unable to fetch weekly update content.';
			  }
            
			
		 	
			$footer = '';
			
			
			
			$data['weekly_update_content'] = $articlecontent;
			
			
			JLoader::import('managetemplate', JPATH_ROOT . '/administrator/components/com_enewsletter/models');
			$templatemodel = JModel::getInstance('Managetemplate', 'EnewsletterModel');
			$templates = $templatemodel->getAlltemplates('weeklyupdate');
			foreach($templates as $et){
                        	$templatefilearray[$et->id] =  $et->filename;
			}
			$template_name = $templatefilearray[$advisordetails['weeklyupdate_default_template']];
                        
                        
                        
                        if($advisordetails['template_weekly'] == ''){
                            $data['intro'] = $advisordetails['weekly_update_intro'];
                            $WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$template_name); 
                            $content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails, $WEEKLYUPDATE);
			
                        }else {                             
                            $WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$advisordetails['template_weekly']); 
                             $settingssss =  JComponentHelper::getParams('com_enewsletter');
                                                 $app = JFactory::getApplication();
						 $db = JFactory::getDbo();
					         $this->optionf  = 2;
                                                 // get content popup
                                                 $query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc limit 0,150 ";
                                                 $db->setQuery($query);  
                                                 $this->article = $db->loadObjectList();

                                                         foreach ($this->article as $i => $item) {
                                                          
                                                                    if ( strpos($item->keywords, 'weekly update')!== false ) {
                                                                                $weekly_items[] = $item;
                                                                                continue;
                                                                    }else {
                                                                        if($item->type == 'Financial Briefs'){
                                                                                $final_items[] = $item;
                                                                                continue;
                                                                        }        
                                                                    }   
                                                        } 
											/*					
                                            if ($settingssss->get('typecontentchoice') == 'tab2'){      
                                                 $id_content_week = $weekly_items[0]->article_id.',';
                                            }else if ($settingssss->get('typecontentchoice') == 'tab3'){    
                                                // $id_content_week = $final_items[0]->article_id.',';
                                                 $id_content_week = $weekly_items[0]->article_id.',';
                                            }
											*/
											
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
                                            
                                            $checkid1 =     $wdata['rss']['channel']['item'][0]['articleid'];   
                                            $checkid2 =     $wdata['rss']['channel']['item'][0]['slideshowimage'];   
                                            $checkid2 = explode('/',$checkid2);
                                            $checkid2 = $checkid2[count($checkid2)-1];
                                            $checkid2 = explode('_',$checkid2);
                                            $checkid2 = $checkid2[0];
                                            
                                           
                                            
                                            if ( ($checkid1 - $checkid2) == 1 ){
                                                 $id_content_week = $checkid2.',';
                                                 
                                                 if ($settingssss->get('contetnt_resouce') == '2') {
                                                  $id_content_week = $checkid2.','.$final_items[0]->article_id.',';;
                                                  }
                                            }else{
                                                    $article_url = "https://contentengine.advisorproducts.com/service/".$wdata['rss']['channel']['item'][0]['link'];                                                 
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                    curl_setopt($ch,CURLOPT_URL,$article_url);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                                                    curl_setopt($ch, CURLOPT_POST, false);
                                                    $adata = curl_exec($ch);			
                                                    $adata = XML2Array::createArray($adata);   	

                                                    $weklyupdatecontenttest = $adata['rss']['channel']['item']['description'];   
                                                    
                                                    $weklyupdatecontenttest = explode('&show=one',$weklyupdatecontenttest);
                                                    $weklyupdatecontenttest = $weklyupdatecontenttest[0];
                                                    $weklyupdatecontenttest = explode('http://contentsrv.advisorsites.com/showarticle.asp?domain=&article=',$weklyupdatecontenttest);
                                                    $weklyupdatecontenttest = $weklyupdatecontenttest[1];
                                                    
                                                    $id_content_week = $weklyupdatecontenttest.',';
                                                    if ($settingssss->get('contetnt_resouce') == '2') {
                                                         $id_content_week = $weklyupdatecontenttest.','.$final_items[0]->article_id.',';;
                                                     }
                                                  
                                            }
                                          
                                        
                                         
							 
                                               
                                             if ($id_content_week == '' ) {
                                                 $id_content_week = $weekly_items[0]->article_id.',';
                                             }

                                             if ($id_content_week != '' ) {
                                               
                                                     if ($settingssss->get('contetnfull') == "N"){
                                                         $showintro= "&getonlyintro=1";
                                                     }
                                                     $url = JURI::base().'index.php?option=com_enewsletter&senweekly=1&task=getcontent&id='.$id_content_week.$showintro;
                                                   
                                                     $options = array(
                                                           CURLOPT_RETURNTRANSFER => true,    
                                                           CURLOPT_HEADER         => false,   
                                                           CURLOPT_FOLLOWLOCATION => true,     
                                                           CURLOPT_ENCODING       => "",      
                                                           CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0", 
                                                           CURLOPT_AUTOREFERER    => true,   
                                                           CURLOPT_CONNECTTIMEOUT => 120,    
                                                           CURLOPT_TIMEOUT        => 120,     
                                                           CURLOPT_MAXREDIRS      => 10,      
                                                           CURLOPT_SSL_VERIFYPEER => false     
                                                       );

                                                       $ch      = curl_init( $url );
                                                       curl_setopt_array( $ch, $options );
                                                       $content11 = curl_exec( $ch );
                                                       $err     = curl_errno( $ch );
                                                       $errmsg  = curl_error( $ch );
                                                       $header  = curl_getinfo( $ch );
                                                       curl_close( $ch );
                                                       if (strlen($content11) > 2000000 ){
                                                          $content11 =  preg_replace("/<img[^>]+\>/i", " ", $content11);
                                                       }                                                       
                                                       $weeklyupdate = str_replace('id="intro"', 'style="display:none;"', $content11);
                                             }
                           
                                            
				
                                            $dom = new SmartDOMDocument();
                                            $dom->loadHTML($WEEKLYUPDATE); 
                                            $a3 = $dom->getElementById('cta');    
                                            if($a3){   
                                                        $a4 = $dom->getElementById('settingintro'); 

                                                        if($a4){
                                                            $a4->nodeValue = $advisordetails['weekly_update_intro'];
                                                        }
                                                        $a5 = $dom->getElementById('settingdeclo');  

                                                        if($a5){
                                                            $a5->nodeValue = $advisordetails['weekly_update_intro'];
                                                        }	

                                                
                                                         $spo = strpos($WEEKLYUPDATE,'settingintro');
                                                         $spo2 = strpos($WEEKLYUPDATE,'settingdeclo');
                                                         if($spo === false ){
                                                            $weekly_update_intro =  $advisordetails['weekly_update_intro'];
                                                         }
                                                         if($spo2 === false ){
                                                             $weekly_update_newsletter =  $advisordetails['weekly_update_newsletter'];                                                                   
                                                         }
                                                         $weeklyupdate = str_replace('#ffffff','transparent',$weeklyupdate);
							 $weeklyupdate = str_replace('#f4f4f4','transparent',$weeklyupdate);
                                                         $a3->nodeValue = $weekly_update_intro.'<br>'.$weeklyupdate.'<br>'. $weekly_update_newsletter;                                       
                                                    
                                            } 
                                            
                                            $WEEKLYUPDATE = $dom->saveHTML();	
                                            $WEEKLYUPDATE  = htmlspecialchars_decode($WEEKLYUPDATE);
                                            $content =  $WEEKLYUPDATE;
                                            $content = str_replace('display: none;', '"ids="cbvnncb""', $content);
               
                                  $dom = new SmartDOMDocument();    
                                  $dom->loadHTML($content);  

                                  $links = $dom->getElementsByTagName('div');
                                  $i=1;
                                  foreach ($links as $link){ 
                                      if ($link->getAttribute('ids') == 'cbvnncb'){
                                           $link->removeAttribute('id');
                                           $link->setAttribute("id", 'aabbcc'.$i);
                                           $i++;
                                      }                       
                                  }
                                  for ($j = ( $i - 1 ) ; $j >= 1  ;$j-- ){

                                      $a3 = $dom->getElementById('aabbcc'.$j);

                                      if( $a3 != NULL ){
                                           $a3->parentNode->removeChild($a3);
                                      }

                                  }



                                  $linkas = $dom->getElementsByTagName('a');
                                  $k=1;
                                  foreach ($linkas as $linka){ 

                                      if ($linka->getAttribute('ids') == 'cbvnncb'){
                                           $linka->removeAttribute('id');
                                           $linka->setAttribute("id", 'aabbccss'.$k);

                                            $k++;
                                      }                       
                                  }

                                  for ($j = 1 ; $j< $k ;$j++ ){
                                      $a4 = $dom->getElementById('aabbccss'.$j); 
                                       if($a4 != NULL){
                                          $a4->parentNode->removeChild($a4);
                                       }
                                  }
                                $content =  $dom->saveHTML();   
                                
                               }
                               
                                $mailer = JFactory::getMailer();
				$config = JFactory::getConfig();		
				$mailer->addRecipient($get['emailsendout']);
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';
				$mailer->setSubject('Test weekly update from website : '.$config->get( 'sitename' ));
				$mailer->setBody($content);
				$send = $mailer->Send();
                                echo 'done';
                  }
                }
                die;
        }
  
	
         public function sendweeklyvideo(){
		
		$model = $this->getModel();
		/* Get database details start */
		$config = new JConfig();
		$host = $config->host;
		$user = $config->user;
		$password = $config->password;
		$database = $config->db;
		$dbprefix = $config->dbprefix;
		$error = '';
		$sqlerror = '';
                $webapierror = '';
		$errorcnt = 0;		
		 $db = JFactory::getDBO();
		/* connect to database and get advisor details start */
		mysql_connect($host,$user,$password) or die(mysql_error());
		mysql_select_db($database)or die(mysql_error());
		
		$selectquery = "SELECT * FROM ".$dbprefix."advisorsettings limit 1";
		$result = mysql_query($selectquery);
		if (mysql_errno()) {
			$errorcnt++;
			$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectquery."\r\n";
		} 
		$advisordetails = mysql_fetch_assoc($result);

		
		$selectgroupquery = "SELECT * FROM #__weeklyupdate_group";
		$db->setQuery($selectgroupquery);
		$groupRows = $db->loadObjectList();
		$numrow = count($groupRows);
                
                $app = JFactory::getApplication();
		$settingssss =  JComponentHelper::getParams('com_enewsletter');       
		
               
                // check on off
                if( $settingssss->get('youtube_mail') < 2 ){ 
                    echo 'Weekly video is off';
                    die;
                }
              
               
        
               
		if(  $advisordetails && ( $numrow || $advisordetails['newsletter_api'] == 'I' ) ){                    
                    
                                $idu = NULL;
                                $channel_id = $settingssss->get('inputyoutube');

                                $xml = simplexml_load_file(sprintf('https://www.youtube.com/feeds/videos.xml?channel_id=%s', $channel_id));
                                if (empty($xml)){
                                    $xml = simplexml_load_file(sprintf('https://www.youtube.com/feeds/videos.xml?user=%s', $channel_id));
                                }
                                if (!empty($xml->entry[0]->children('yt', true)->videoId[0])){
                                    $id = $xml->entry[0]->children('yt', true)->videoId[0];
                                    $link = $xml->entry[0]->children('link', true)->videoId[0];
                                }
                                
                                $linkimage = "http://img.youtube.com/vi/$id/0.jpg";
                                $hhhtt = "<div style='  width:100%; float:left; text-align:center;padding-bottom: 55px  ;  position: relative;' >".' 

                                <table  style="    background-repeat: no-repeat;       background-size: 590px 433px;   background-position:162px 57px;" background="'.$linkimage.'" width="800" height="500">
                                        <tr>
                                            <td>
                                             '."<a href='http://www.youtube.com/watch?v=$id' target='_blank' ><img src='".juri::base().'components/com_enewsletter/assets/images/videoplayer.png'."' style='width:505px;height:435px;' /></a>".'
                                            </td>
                                        </tr>
                                    </table>
                                '."</div>";
                                
                                $content  = '<html><body><div style="  color: #'.$settingssss->get('maintextgc','000000').';   background-color:#'.$settingssss->get('backgc','ffffff').';  padding: 20px;  width: 800px;    margin: 0 auto;" ><div style="text-align:center;    margin-bottom: 30px;" > '."<img style=  '  max-width: 500px;    min-width: 300px;' border=0 src='".JURI::base(false)."media/com_enewsletter/logo/".$advisordetails['logo']."' />".'<br></div>'.base64_decode($settingssss->get('youtube_intro')).$hhhtt.'<br>'.base64_decode($settingssss->get('youtubedescription')).'</div></body></html>';
                                $content = str_replace('<a', "<a style='color:#".$settingssss->get('linktextgc','2366bd').";' " , $content); 
                             //   print_r($content);die;
                                $advisordetails['update_subject'] = $settingssss->get('youtube_subject');                                
                                  
                                $APIKEY = CONSTANT_APIKEY;
                                $ACCESS_TOKEN = $advisordetails['api_key'];

                                $gid = array();
                                $cid = array();

                                $subject = mysql_real_escape_string($advisordetails['update_subject']);

                                $weeklyupdategroup = array();
                               foreach ($groupRows as $group) {
                                        $gid[] = $group->group_id;
                                        $weeklyupdategroup[] = $group->group_id;
                                }//for
			

                                $listmembers = '';
                                $ccontacts = '';

                                //we send to Advisor Group users for Approved

                                $com_enews_config = JComponentHelper::getParams('com_enewsletter');                                 
                                
                               
			if (  $settingssss->get('youtube_mail') == 3  && !JRequest::getInt('dosendnow', 0) && $settingssss->get('youtube_semiautoemail') != ''  ) {
				      
				$db = JFactory::getDBO();
                                $subject = "Approval For Weekly Video";
                                $mail = JFactory::getMailer();
                                $config	= JFactory::getConfig();
                                $mailfrom = $config->get('mailfrom');
                                $fromname = $config->get('fromname');                                
                                $advisor_email_a = $settingssss->get('youtube_semiautoemail');
                                //more detail
                                $message = "<p>Your content is ready to go!</p>
                                            <p>Please review the content below and click Approve or Deny</p>
                                            <p><a style='font-size:16px;' href='".JURI::root(false)."index.php?option=com_enewsletter&task=sendweeklyvideo&dosendnow=1'>Approve</a> </p>
                                ";
                                $message .= $content;
                                
                                $mail->setSender(array($mailfrom, $fromname));
                                $mail->setSubject($subject);
                                $mail->setBody($message);
                                $mail->IsHTML(true);
                                $mail->addRecipient($advisor_email_a);
                                $sent = $mail->Send();
                                
                                echo "<p>sent to: $advisor_email_a for approved!</p>";                               
                                exit;				
			}//if
                       // echo 'dddd';
                        
                        /* Check for constant contact/mailchimp and send email start */
                        if ($advisordetails['newsletter_api']  == 'G') {
                            
                                        $api_url = 'http://api2.getresponse.com';
                                        $client = new jsonRPCClient($api_url);

                                        $details = $client->send_newsletter(
					$ACCESS_TOKEN,array (
                                            'campaign' => $gid[0],
                                            'subject'  => $advisordetails['update_subject'],
                                            'name'     => date('Ymd-h:i:s'),
                                            "contents" => array('html' => $content),
                                            'get_contacts' => array('campaigns' => $gid)
					)
                                        );

                                        if ($details && !$details['error'] && $details['result']) {
                                                $return = $details['result'];
                                        } else {
                                                if ($details['error']) {
                                                        $this->setMessage($details['error']['message']." - (from GetResponse)",'error');
                                                } else {
                                                        $this->setMessage('send weeklyupdate error');
                                                }//if

                                                $this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
                                                return;

                                        }//if
                                   
                            } else  if ($advisordetails['newsletter_api'] == 'I') {

		
                            	    $infusionsoft_host = $advisordetails['api_login_name'];
                                    $infusionsoft_api_key = $ACCESS_TOKEN;
                                    require JPATH_SITE.'/administrator/components/com_enewsletter/libraries/infusionsoft/infusionsoft.php';
                                    $contacts = Infusionsoft_DataService::query(new Infusionsoft_Contact(), array('Company' => 'api'));
                                    $email_a = array();
                                    foreach ($contacts as $contact) {
                                            $email_a[$contact->Id] = $contact->Email;
                                    }//for

                                    if (count($email_a)) {

                                            $contactFromApp1 = array_shift($contacts);
                                            $fromAddress = $advisordetails['from_email'];
                                            $toAddress = $contactFromApp1->Email;
                                            $ccAddresses = $advisordetails['from_email'];
                                            $bccAddresses = $advisordetails['from_email'];
                                            $contentType = 'HTML';
                                            $subject = $advisordetails['update_subject'];
                                            $htmlBody = $content;
                                            $textBody = strip_tags($content);

                                            $return = Infusionsoft_EmailService::sendEmail(array_keys($email_a), $fromAddress, $toAddress, $ccAddresses, $bccAddresses, $contentType, $subject, $htmlBody, $textBody);

                                            if (!$return) {
                                                    $this->setMessage('send newsletter error');
                                                    $this->setRedirect('index.php');
                                                    return;
                                            }//if

                                }//if	
								
                                        
				} else if($advisordetails['newsletter_api'] == 'C'){
                                   
                                            $cc = new ConstantContact(trim($APIKEY));	                                           
                                            $campaign = new Campaign();

                                            $lists = $cc->getLists($ACCESS_TOKEN);


                                            $campaign_title = time().'_'.$advisordetails['update_subject'];
                                            $campaign_title =  substr($campaign_title, 0, 78);
                                            $campaign->name = $campaign_title;
                                            $campaign->subject = $advisordetails['update_subject'];
                                            $campaign->from_name = $advisordetails['from_name'];
                                            $campaign->from_email = $advisordetails['from_email'];
                                            $campaign->greeting_string = $advisordetails['update_subject'];
                                            $campaign->reply_to_email = $advisordetails['from_email'];
                                            $campaign->text_content = $advisordetails['update_subject'];
                                            $campaign->email_content = $content;
                                            $campaign->email_content_format = 'HTML';

                                            $i = 1;
                                    
                                            foreach($weeklyupdategroup as $g){

                                                            $campaign->addList($g);
                                                            $groupcontact = $cc->getContactsFromList($ACCESS_TOKEN, $g);

                                                            foreach($groupcontact->results as $re){
                                                                    $ccontacts .= $i.". ".$re->email_addresses[0]->email_address."\t".date('m/d/Y')."\r\n";
                                                                    $i++;
                                                            }
                                                            $ccontacts .= '';
                                                            $gid[] = $g;
                                            }

  
                                            try {
                                                    $return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
                                                   
                                                    try {
                                                            $schedule = new Schedule();
                                                          //  $schedule->scheduled_date = $timeSchedule;
                                                            $cc->addEmailCampaignSchedule($ACCESS_TOKEN,$return->id, $schedule);
                                                            $listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
                                                            $listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
                                                            $listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
                                                            $listmembers .= $ccontacts;
                                                            $data['email_id'] = $return->id;

                                                    } catch (CtctException $ex) {
                                                            $errorcnt++;
                                                            $listmembers .= "Error in Scheduling Campaign ".$campaign_title."\r\n<br>";
                                                            $ccerror = $ex->getErrors();
                                                            $listmembers .= $ccerror[0]['error_message'];
                                                              echo $listmembers;
                                                            die;
                                                          
                                                    }
                                            }catch (CtctException $ex) {
                                              
                                                    $errorcnt++;
                                                    $listmembers .= "Error in Creating Campaign".$campaign_title."\r\n<br>";
                                                    $ccerror = $ex->getErrors();
                                                    $listmembers .= $ccerror[0]['error_message'];
                                                    echo $listmembers;
                                                    die;
                                            } 
                                        
                                       
                                      
                            } else if($advisordetails['newsletter_api'] == 'M'){
                                
						$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
						$type = 'regular';
						$campaign_title = time().'_'.$advisordetails['update_subject'];
						$campaign_title =  substr($campaign_title, 0, 78);
						$opts['title'] = $campaign_title;
						$opts['subject'] = $advisordetails['update_subject'];
						$opts['from_email'] = $advisordetails['from_email'];
						$opts['from_name'] = $advisordetails['from_name'];			
						$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
						$opts['authenticate'] = true;
						$email_content = array('html'=>$content);	
					
						$mgroups = $mailchimp->lists();
						$mgroups = $mgroups['data'];
						
						$email_ids = array();
						foreach($weeklyupdategroup as $g){	
							$opts['list_id'] = $g;
							$gid[] = $g;
							$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
							if ($mailchimp->errorCode){
								$errorcnt++;
								$listmembers .= "Unable to Create New Campaign ".$campaign_title."\r\n";
								$listmembers .= $mailchimp->errorMessage."\r\n";
							} else {
								$return = $mailchimp->campaignSendNow($retval);
								if ($mailchimp->errorCode){
									$errorcnt++;
									$listmembers .= "Unable to Send Campaign ".$campaign_title."\r\n";
									$listmembers .= "".$mailchimp->errorMessage."\r\n";
								} else {
									$listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
									$listcontacts = $mailchimp->listMembers($g);			
									$email_ids[] = $retval;	
									$i = 1;
									if(!empty($listcontacts['data'])){
										$listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
										$listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
										foreach($listcontacts['data'] as $lc){
											$listmembers .= $i.". ".$lc['email']."\t".date('m/d/Y')."\r\n";
											$i++;
										}
										
										$listmembers .= "\r\n\r\n";
									}
								} 
								$data['email_id'] = implode(',',$email_ids);
				
							}					
						}//for
				}//if newsletter_api
                            
			if(!empty($data['email_id'])){				
			
			}else{
				echo "Weekly video not sent! \r\n";
                                exit;
			}			
		} else {
			$filecontent .= 'Missing data: '.print_r($advisordetails,true)."\r\n\r\n";
                                echo $filecontent;
                        exit;
		}//if
                
		echo '<div style="font-size:40px;" >Your Weekly Video Has Been Sent !!</div>';
		exit;
	}//func
        
        public function sendplanningupdate(){
		
		$model = $this->getModel();
		/* Get database details start */
		$config = new JConfig();
		$host = $config->host;
		$user = $config->user;
		$password = $config->password;
		$database = $config->db;
		$dbprefix = $config->dbprefix;
		$error = '';
		$sqlerror = '';
                $webapierror = '';
		$errorcnt = 0;		
		
		/* connect to database and get advisor details start */
		mysql_connect($host,$user,$password) or die(mysql_error());
		mysql_select_db($database)or die(mysql_error());
		
		$selectquery = "SELECT * FROM ".$dbprefix."advisorsettings limit 1";
		$result = mysql_query($selectquery);
		if (mysql_errno()) {
			$errorcnt++;
			$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectquery."\r\n";
		} 
		$advisordetails = mysql_fetch_assoc($result);

		
		$selectgroupquery = "SELECT * FROM ".$dbprefix."weeklyupdate_group";
		$groupresult = mysql_query($selectgroupquery);
		if (mysql_errno()) {
			$errorcnt++;
			$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectgroupquery."\r\n";
		} 
		
		$numrow = mysql_num_rows($groupresult);    
                $app = JFactory::getApplication();
		$settingssss =  JComponentHelper::getParams('com_enewsletter');       
		
                $part = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$settingssss->get('templatechioce_plan').'.html';
                if(!file_exists($part)){
                    echo 'Please Setting first';
                    die;
                }
                $WEEKLYUPDATE = file_get_contents($part); 
                
                // check on off
                if( $settingssss->get('planing_option') < 2 ){ 
                    echo 'Schedule off';
                    die;
                }
                // check day apply
                
                $dayapp =  explode(',', $settingssss->get('day_send') );
                if(!in_array(date('N'),$dayapp)){ 
                    echo 'Not Schedule';
                    die;
                }
                
                // set Schedule Time
                if ( $settingssss->get('ti_send') == "AM"  ) {
                     $time =   $settingssss->get('hour_send') ; 
                }else{
                     $time =   $settingssss->get('hour_send')+12 ; 
                }

                $time_mu =   $settingssss->get('mu_send') ; 
                
                $timeSchedule =  strtotime( date('Y-m-d').' '.$time.':'.$time_mu.':00');
                $timenow = strtotime('now');
              
                if (  (($timeSchedule - $timenow ) > 3600 || ( $timeSchedule - $timenow )  < 0 ) && !JRequest::getInt('dosendnow', 0) ){
                     echo 'Time Schedule Not Begin';
                     die;
                }
                
              
                
		if(  $advisordetails && ( $numrow || $advisordetails['newsletter_api'] == 'I' ) ){                    
                    
                
                                    $db = JFactory::getDbo();
                                    $this->optionf  = 2;
                                        // get content popup
                                    $query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc limit 0,150 ";
                                    $db->setQuery($query);  
                                    $this->article = $db->loadObjectList();

                                    foreach ($this->article as $i => $item) {

                                                if ( strpos($item->keywords, 'weekly update')!== false ) {
                                                            $weekly_items[] = $item;
                                                            continue;
                                                }else {
                                                    if($item->type == 'Financial Briefs'){
                                                            $final_items[] = $item;
                                                            continue;
                                                    }        
                                                }   
                                    }    
                                    
                                     $id_content_week = $final_items[0]->article_id.',';

                                     if ($id_content_week != '' ) {                                                 

                                             $url = JURI::base().'index.php?option=com_enewsletter&senweekly=1&task=getcontent&id='.$id_content_week.$showintro;
                                             $options = array(
                                                   CURLOPT_RETURNTRANSFER => true,    
                                                   CURLOPT_HEADER         => false,   
                                                   CURLOPT_FOLLOWLOCATION => true,     
                                                   CURLOPT_ENCODING       => "",      
                                                   CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0", 
                                                   CURLOPT_AUTOREFERER    => true,   
                                                   CURLOPT_CONNECTTIMEOUT => 120,    
                                                   CURLOPT_TIMEOUT        => 120,     
                                                   CURLOPT_MAXREDIRS      => 10,      
                                                   CURLOPT_SSL_VERIFYPEER => false     
                                               );

                                               $ch      = curl_init( $url );
                                               curl_setopt_array( $ch, $options );
                                               $content11 = curl_exec( $ch );
                                               $err     = curl_errno( $ch );
                                               $errmsg  = curl_error( $ch );
                                               $header  = curl_getinfo( $ch );
                                               curl_close( $ch );
                                               if (strlen($content11) > 2000000 ){
                                                  $content11 =  preg_replace("/<img[^>]+\>/i", " ", $content11);
                                               }                                                       
                                               $weeklyupdate = str_replace('id="intro"', 'style="display:none;"', $content11);
                                     }


                                    $dom = new SmartDOMDocument();
                                    $dom->loadHTML($WEEKLYUPDATE); 
                                    $a3 = $dom->getElementById('cta');    
                                    if($a3){  
                                          $a4 = $dom->getElementById('settingintro'); 
                                          if($a4){
                                              $a4->nodeValue = $advisordetails['weekly_update_intro'];
                                          }
                                          $a5 = $dom->getElementById('settingdeclo');  

                                          if($a5){
                                              $a5->nodeValue = $advisordetails['weekly_update_newsletter'];      
                                          }
                                         $spo = strpos($WEEKLYUPDATE,'settingintro');
                                         $spo2 = strpos($WEEKLYUPDATE,'settingdeclo');
                                         if($spo === false ){
                                            $weekly_update_intro =  $advisordetails['weekly_update_intro'];
                                         }
                                         if($spo2 === false ){
                                             $weekly_update_newsletter =  $advisordetails['weekly_update_newsletter'];   
                                         }
                                         $weeklyupdate = str_replace('#ffffff','transparent',$weeklyupdate);
                                         $weeklyupdate = str_replace('#f4f4f4','transparent',$weeklyupdate);
                                         $a3->nodeValue = $weekly_update_intro.'<br>'.$weeklyupdate.'<br>'. $weekly_update_newsletter;   
                                    } 

                                    $WEEKLYUPDATE = $dom->saveHTML();	
                                    $WEEKLYUPDATE  = htmlspecialchars_decode($WEEKLYUPDATE);                                            
                                    $content =  $WEEKLYUPDATE;                                            

                                    $content = str_replace('display: none;', '"ids="cbvnncb""', $content);               
                                    $dom = new SmartDOMDocument();    
                                    $dom->loadHTML($content);  
                                    $links = $dom->getElementsByTagName('div');

                                    $i=1;
                                    foreach ($links as $link){ 
                                        if ($link->getAttribute('ids') == 'cbvnncb'){
                                             $link->removeAttribute('id');
                                             $link->setAttribute("id", 'aabbcc'.$i);
                                             $i++;
                                        }                       
                                    }
                                    for ($j = ( $i - 1 ) ; $j >= 1  ;$j-- ){
                                        $a3 = $dom->getElementById('aabbcc'.$j);
                                        if( $a3 != NULL ){
                                             $a3->parentNode->removeChild($a3);
                                        }
                                    }



                                  $linkas = $dom->getElementsByTagName('a');
                                  $k=1;
                                  foreach ($linkas as $linka){ 

                                      if ($linka->getAttribute('ids') == 'cbvnncb'){
                                           $linka->removeAttribute('id');
                                           $linka->setAttribute("id", 'aabbccss'.$k);
                                           $k++;
                                      }                       
                                  }

                                  for ($j = 1 ; $j< $k ;$j++ ){
                                      $a4 = $dom->getElementById('aabbccss'.$j); 
                                       if($a4 != NULL){
                                          $a4->parentNode->removeChild($a4);
                                       }
                                  }

                                $WEEKLYUPDATE =  $dom->saveHTML();    
                                $content = $WEEKLYUPDATE;
                                  
                                $APIKEY = CONSTANT_APIKEY;
                                $ACCESS_TOKEN = $advisordetails['api_key'];

                                $gid = array();
                                $cid = array();

                                $subject = mysql_real_escape_string($advisordetails['update_subject']);

                                $weeklyupdategroup = array();
                                while($group = mysql_fetch_assoc($groupresult)){
                                        $gid[] =$group['group_id'];
                                $weeklyupdategroup[] = $group['group_id'];
                                }	

                                $listmembers = '';
                                $ccontacts = '';

                                //we send to Advisor Group users for Approved

                                $com_enews_config = JComponentHelper::getParams('com_enewsletter');                                 
                                
                               
			if (  $settingssss->get('planing_option') == 3  && !JRequest::getInt('dosendnow', 0) && $settingssss->get('plan_semiautoemail') != ''  ) {
				      
				$db = JFactory::getDBO();
                                $subject = "Approval For Weekly Financial Planning Update";
                                $mail = JFactory::getMailer();
                                $config	= JFactory::getConfig();
                                $mailfrom = $config->get('mailfrom');
                                $fromname = $config->get('fromname');                                
                                $advisor_email_a = $settingssss->get('plan_semiautoemail');
                                //more detail
                                $message = "<p>Your content is ready to go!</p>
                                            <p>Please review the content below and click Approve or Deny</p>
                                            <p><a style='font-size:16px;' href='".JURI::root(false)."index.php?option=com_enewsletter&task=sendplanningupdate&dosendnow=1'>Approve</a> </p>
                                ";
                                $message .= $content;
                                
                                $mail->setSender(array($mailfrom, $fromname));
                                $mail->setSubject($subject);
                                $mail->setBody($message);
                                $mail->IsHTML(true);
                                $mail->addRecipient($advisor_email_a);
                                $sent = $mail->Send();
                                
                                echo "<p>sent to: $advisor_email_a for approved!</p>";                               
                                exit;				
			}//if
                       // echo 'dddd';
                        
                        /* Check for constant contact/mailchimp and send email start */
                        if ($advisordetails['newsletter_api']  == 'G') {
                            
                                        $api_url = 'http://api2.getresponse.com';
                                        $client = new jsonRPCClient($api_url);

                                        $details = $client->send_newsletter(
					$ACCESS_TOKEN,array (
                                            'campaign' => $gid[0],
                                            'subject'  => $data['update_subject'],
                                            'name'     => date('Ymd-h:i:s'),
                                            "contents" => array('html' => $content),
                                            'get_contacts' => array('campaigns' => $gid)
					)
                                        );

                                        if ($details && !$details['error'] && $details['result']) {
                                                $return = $details['result'];
                                        } else {
                                                if ($details['error']) {
                                                        $this->setMessage($details['error']['message']." - (from GetResponse)",'error');
                                                } else {
                                                        $this->setMessage('send weeklyupdate error');
                                                }//if

                                                $this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
                                                return;

                                        }//if
                                   
                            } else  if ($advisordetails['newsletter_api'] == 'I') {

		
                            	    $infusionsoft_host = $advisordetails['api_login_name'];
                                    $infusionsoft_api_key = $ACCESS_TOKEN;
                                    require JPATH_SITE.'/administrator/components/com_enewsletter/libraries/infusionsoft/infusionsoft.php';
                                    $contacts = Infusionsoft_DataService::query(new Infusionsoft_Contact(), array('Company' => 'api'));
                                    $email_a = array();
                                    foreach ($contacts as $contact) {
                                            $email_a[$contact->Id] = $contact->Email;
                                    }//for

                                    if (count($email_a)) {

                                            $contactFromApp1 = array_shift($contacts);
                                            $fromAddress = $advisordetails['from_email'];
                                            $toAddress = $contactFromApp1->Email;
                                            $ccAddresses = $advisordetails['from_email'];
                                            $bccAddresses = $advisordetails['from_email'];
                                            $contentType = 'HTML';
                                            $subject = $advisordetails['update_subject'];
                                            $htmlBody = $content;
                                            $textBody = strip_tags($content);

                                            $return = Infusionsoft_EmailService::sendEmail(array_keys($email_a), $fromAddress, $toAddress, $ccAddresses, $bccAddresses, $contentType, $subject, $htmlBody, $textBody);

                                            if (!$return) {
                                                    $this->setMessage('send newsletter error');
                                                    $this->setRedirect('index.php');
                                                    return;
                                            }//if

                                }//if	
								
                                        
				} else if($advisordetails['newsletter_api'] == 'C'){
                                       
                                            $cc = new ConstantContact(trim($APIKEY));	
                                            $campaign = new Campaign();

                                            $lists = $cc->getLists($ACCESS_TOKEN);


                                            $campaign_title = time().'_'.$advisordetails['update_subject'];
                                            $campaign_title =  substr($campaign_title, 0, 78);
                                            $campaign->name = $campaign_title;
                                            $campaign->subject = $advisordetails['update_subject'];
                                            $campaign->from_name = $advisordetails['from_name'];
                                            $campaign->from_email = $advisordetails['from_email'];
                                            $campaign->greeting_string = $advisordetails['update_subject'];
                                            $campaign->reply_to_email = $advisordetails['from_email'];
                                            $campaign->text_content = $advisordetails['update_subject'];
                                            $campaign->email_content = $content;
                                            $campaign->email_content_format = 'HTML';

                                            $i = 1;
                                            foreach($weeklyupdategroup as $g){

                                                            $campaign->addList($g);
                                                            $groupcontact = $cc->getContactsFromList($ACCESS_TOKEN, $g);

                                                            foreach($groupcontact->results as $re){
                                                                    $ccontacts .= $i.". ".$re->email_addresses[0]->email_address."\t".date('m/d/Y')."\r\n";
                                                                    $i++;
                                                            }
                                                            $ccontacts .= '';
                                                            $gid[] = $g;
                                            }


                                            try {
                                                    $return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
                                                    try {
                                                            $schedule = new Schedule();
                                                          //  $schedule->scheduled_date = $timeSchedule;
                                                            $cc->addEmailCampaignSchedule($ACCESS_TOKEN,$return->id, $schedule);
                                                            $listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
                                                            $listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
                                                            $listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
                                                            $listmembers .= $ccontacts;
                                                            $data['email_id'] = $return->id;

                                                    } catch (CtctException $ex) {
                                                            $errorcnt++;
                                                            $listmembers .= "Error in Scheduling Campaign ".$campaign_title."\r\n<br>";
                                                            $ccerror = $ex->getErrors();
                                                            $listmembers .= $ccerror[0]['error_message'];
                                                    }
                                            }catch (CtctException $ex) {
                                                    $errorcnt++;
                                                    $listmembers .= "Error in Creating Campaign".$campaign_title."\r\n<br>";
                                                    $ccerror = $ex->getErrors();
                                                    $listmembers .= $ccerror[0]['error_message'];
                                            } 
                                        
                                       
                                      
                            } else if($advisordetails['newsletter_api'] == 'M'){
                                
						$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
						$type = 'regular';
						$campaign_title = time().'_'.$advisordetails['update_subject'];
						$campaign_title =  substr($campaign_title, 0, 78);
						$opts['title'] = $campaign_title;
						$opts['subject'] = $advisordetails['update_subject'];
						$opts['from_email'] = $advisordetails['from_email'];
						$opts['from_name'] = $advisordetails['from_name'];			
						$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
						$opts['authenticate'] = true;
						$email_content = array('html'=>$content);	
					
						$mgroups = $mailchimp->lists();
						$mgroups = $mgroups['data'];
						
						$email_ids = array();
						foreach($weeklyupdategroup as $g){	
							$opts['list_id'] = $g;
							$gid[] = $g;
							$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
							if ($mailchimp->errorCode){
								$errorcnt++;
								$listmembers .= "Unable to Create New Campaign ".$campaign_title."\r\n";
								$listmembers .= $mailchimp->errorMessage."\r\n";
							} else {
								$return = $mailchimp->campaignSendNow($retval);
								if ($mailchimp->errorCode){
									$errorcnt++;
									$listmembers .= "Unable to Send Campaign ".$campaign_title."\r\n";
									$listmembers .= "".$mailchimp->errorMessage."\r\n";
								} else {
									$listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
									$listcontacts = $mailchimp->listMembers($g);			
									$email_ids[] = $retval;	
									$i = 1;
									if(!empty($listcontacts['data'])){
										$listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
										$listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
										foreach($listcontacts['data'] as $lc){
											$listmembers .= $i.". ".$lc['email']."\t".date('m/d/Y')."\r\n";
											$i++;
										}
										
										$listmembers .= "\r\n\r\n";
									}
								} 
								$data['email_id'] = implode(',',$email_ids);
				
							}					
						}//for
				}//if newsletter_api
                                
			if(!empty($data['email_id'])){				
			
			}else{
				echo "Weekly update not sent! \r\n";
                                exit;
			}			
		} else {
			$filecontent .= 'Missing data: '.print_r($advisordetails,true)."\r\n\r\n";
                        exit;
		}//if
                
		echo '<div style="font-size:40px;" >Your Weekly Financial Planning Update Has Been Sent !!</div>';
		exit;
	}//func
        
        
	public function sendweeklyupdate(){
		
		$model = $this->getModel();
		
		/* Get database details start */
		$config = new JConfig();
		
		$error = '';
		$sqlerror = '';
		$webapierror = '';
		$errorcnt = 0;
		
		
		$db = JFactory::getDBO();
                
                $last_friday = date('Y-m-d', strtotime('last friday'));		
		$today = date('Y-m-d', strtotime('today'));		
		
		
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from('#__enewsletter');
		$query->where('`email_id` != ""');
		$query->where('`type` = "weeklyupdate"');
		$query->where('DATE_FORMAT(dte_created,"%Y-%m-%d") BETWEEN "'.$last_friday.'" AND "'.$today.'"');
		$db->setQuery($query);
		$found = $db->loadResult();
		
		//echo $query->dump();
		//exit;<br />

		if ( $found ) {
			die("This Weeklyupdate enewsletter had sent already!");
		}//if
                
                
                
		
		$selectquery = "SELECT * FROM #__advisorsettings limit 1";
		$db->setQuery($selectquery);
		$advisordetails = $db->loadAssoc();

		/* connect to database and get advisor details end */
		
		/* Get weekly update groups start */
		$selectgroupquery = "SELECT * FROM #__weeklyupdate_group";
		$db->setQuery($selectgroupquery);
		$groupRows = $db->loadObjectList();
		$numrow = count($groupRows);
		
		
		
		// Set weekly update mail content         
          
		$filecontent = '';
		
		
		//if ( strpos($_SERVER['HTTP_HOST'], 'localhost')!==false ) $orgid = 58;
		
		if ( !$_GET['testapidefault'] && (!$advisordetails || $advisordetails['auto_update'] != 'Y' || !$advisordetails['firm'] || !$advisordetails['url'] || !$advisordetails['path_quote'] || !$advisordetails['custom_link_article'] || !$advisordetails['address1'] || !$advisordetails['city'] || !$advisordetails['zip'] || !$advisordetails['state']) ) {
		
			$webapierror = 'Missing setting data: '.print_r($advisordetails,true)."\r\n\r\n";
			die($webapierror);
		
		} else {
			
			//die('zxczxczxc');
		            
			$filecontent .= "=======================================================\r\n";
			$filecontent .= "Output of site : ".$orgdata->primary_domain."\r\r\n";
			$filecontent .= "=======================================================\r\n";
			  
			  
			//start centcom log
			$config = new JConfig();
			$extoption['driver']   = $config->dbtype;
			$extoption['host']     = $config->host;
			$extoption['user']     = $config->user;    
			$extoption['password'] = $config->password;   
			$extoption['database'] = 'centcom';
			$extoption['prefix']   = 'api_';
			$dbEx = & JDatabase::getInstance($extoption );
	
			
			$oURI = JURI::getInstance();
			$cur_domain = $oURI->getHost();
			
			$queryEx = $dbEx->getQuery(true);
			$queryEx->select('*');
			$queryEx->from('#__apisc');
			$queryEx->where('(`primary_domain` = '.$dbEx->quote($cur_domain).' OR `secondary_domains` = '.$dbEx->quote($cur_domain).')');
			$queryEx->where('`site_database` = '.$dbEx->quote($config->db));
			$queryEx->order('`id` DESC');
			$dbEx->setQuery($queryEx, 0, 1);
			$site_row = $dbEx->loadObject();
				
				
			
			
			
			$queryEx = $dbEx->getQuery(true);
			$queryEx->insert('#__weeklyupdate_logs');
			
			if ($site_row) {
				$queryEx->set('`site_id` = '.$dbEx->quote($site_row->id));
				$queryEx->set('`company_title` = '.$dbEx->quote($site_row->company_title));
				$queryEx->set('`primary_domain` = '.$dbEx->quote($site_row->primary_domain));
			} else {
				$queryEx->set('`company_title` = '.$dbEx->quote($config->sitename));
				$queryEx->set('`primary_domain` = '.$dbEx->quote($cur_domain));
			}//if
			$queryEx->set('`date_created` = '.$dbEx->quote( JFactory::getDate()->toMySQL() ) );
			$dbEx->setQuery($queryEx);
			$dbEx->execute();
			
			$centcom_logid = $dbEx->insertid();
			
			//check any List here
			if (!$_GET['testapidefault'] && !$numrow) {
				$webapierror = 'No found any enewsletter list!';
				$queryEx = $dbEx->getQuery(true);
				$queryEx->update('#__weeklyupdate_logs');
				$queryEx->set('`errorlog` = '.$dbEx->quote($webapierror));
				$queryEx->where('`id` = '.$centcom_logid);
				$dbEx->setQuery($queryEx);
				$dbEx->execute();
				die($webapierror);
			}//if
			
				  
			$app = JFactory::getApplication();
			$model->setWeeklyUpdateContent($advisordetails);
			$cid = array();
			$weeklyupdate = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
			$cid = $app->getUserState("com_enewsletter.Weeklyupdatearticleid");	
			
			JLoader::import('enewsletter', JPATH_ROOT . '/administrator/components/com_enewsletter/models');
			$emodel = JModel::getInstance('Enewsletter', 'EnewsletterModel');
			  
			if (!$weeklyupdate){
			
				$articlecontent = '';
				$webapierror = 'Unable to fetch weekly update content.';
				  
				$queryEx = $dbEx->getQuery(true);
				$queryEx->update('#__weeklyupdate_logs');
				$queryEx->set('`errorlog` = '.$dbEx->quote($webapierror));
				$queryEx->where('`id` = '.$centcom_logid);
				$dbEx->setQuery($queryEx);
				$dbEx->execute();
				  
				die($webapierror);
			
			} else {	
				
				$articlecontent = $weeklyupdate;
				 
				$sqlserver_user = sqlserver_user;
				$sqlserver_pass = sqlserver_pass;
				$sqlserver_db =  "ArticleContent";
				$sqlserver_host = sqlserver_host; 
				
				$connectionInfo = array("UID" => $sqlserver_user, "PWD" => $sqlserver_pass, "Database"=>$sqlserver_db);	
				$serverName = $sqlserver_host;
				$conn = sqlsrv_connect( $serverName, $connectionInfo);	
				
				//var_dump($conn);
				
				$dom = new SmartDOMDocument();
				$dom->loadHTML($articlecontent);
				$mock = new SmartDOMDocument();
				$body = $dom->getElementsByTagName('body')->item(0);
                foreach ($body->childNodes as $child){
    				$mock->appendChild($mock->importNode($child, true));
                 }  
                 $articlecontent = $mock->saveHTML(); 
				
				
				$newarr = array();
				$anchor = $dom->getElementsByTagName('a');
		
				// Need article id of all anchors available in WeeklyContent
				foreach($anchor as $a){			
					$href = $a->getAttribute('href');							
					$href = parse_url($href);							
					parse_str($href['query'], $query);															
					if(!empty($query['id'])){
						array_push($newarr, $query['id']);	
					}//if
				}//for
				
				
				
		
				for ($h=0; $h < count($newarr) ; $h++) { 
					
					$sql="SELECT * FROM article_featured_image where article_id=".$newarr[$h];		
					$sqldata = sqlsrv_query($conn, $sql);
					$images = sqlsrv_fetch_array( $sqldata, SQLSRV_FETCH_ASSOC);		        	  								
		
					$img = $this->create_image('https://contentengine.advisorproducts.com/'.$images['slideshow_image']);
					$final_img = JURI::root().'media/com_enewsletter/images/'.$img;
						
					// Replace images over commented word
					$articlecontent = preg_replace('/<!--#APICONTENTIMAGE#-->/', "<img style='margin:8px;border:1px #000 solid;' width='100%' src='".$final_img."'", $articlecontent, 1);
				}//for
				
			}//if get weeklyupdate
		 	
			$footer = '';
			
			
			
			$data['weekly_update_content'] = $articlecontent;
			
			#HT - fix big issue here
			JLoader::import('managetemplate', JPATH_ROOT . '/administrator/components/com_enewsletter/models');
			$templatemodel = JModel::getInstance('Managetemplate', 'EnewsletterModel');
			$templates = $templatemodel->getAlltemplates('weeklyupdate');
			foreach($templates as $et){
				$templatefilearray[$et->id] =  $et->filename;
			}
			$template_name = $templatefilearray[$advisordetails['weeklyupdate_default_template']];
              
			  
			  //var_dump($data['weekly_update_content']);
			            
                        
			if ($advisordetails['template_weekly'] == ''){
            	$data['intro'] = $advisordetails['weekly_update_intro'];
                $WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$template_name); 
			    $content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, $advisordetails, $WEEKLYUPDATE);
             } else {
                             
				$WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$advisordetails['template_weekly']); 
				$settingssss =  JComponentHelper::getParams('com_enewsletter');
				
				$app = JFactory::getApplication();
				//  $model= JModelLegacy::getInstance('Subscription', 'EnewsletterModel', array('ignore_request' => true));
				//  $model->setWeeklyUpdateContent($advisordetails);
				//  $cid = array();
				//  $weeklyupdate = $app->getUserState("com_enewsletter.Weeklyupdatedesc");

                
				// get content popup
                $query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc limit 0,150 ";
				$db->setQuery($query);  
				$this->article = $db->loadObjectList();

				foreach ($this->article as $i => $item) {
				  
							if ( strpos($item->keywords, 'weekly update')!== false ) {
										$weekly_items[] = $item;
										continue;
							}else {
								if($item->type == 'Financial Briefs'){
										$final_items[] = $item;
										continue;
								}        
							}   
				}          

				
				/*
				
				if ($settingssss->get('typecontentchoice') == 'tab2'){      
					 $id_content_week = $weekly_items[0]->article_id.',';
				}else if ($settingssss->get('typecontentchoice') == 'tab3'){    
					// $id_content_week = $final_items[0]->article_id.',';
					  $id_content_week = $weekly_items[0]->article_id.',';
				}
				*/
              
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
                                            
                                            $checkid1 =     $wdata['rss']['channel']['item'][0]['articleid'];   
                                            $checkid2 =     $wdata['rss']['channel']['item'][0]['slideshowimage'];   
                                            $checkid2 = explode('/',$checkid2);
                                            $checkid2 = $checkid2[count($checkid2)-1];
                                            $checkid2 = explode('_',$checkid2);
                                            $checkid2 = $checkid2[0];
                                            
                                           
                                            
                                            if ( ($checkid1 - $checkid2) == 1 ){
                                                 $id_content_week = $checkid2.',';
                                                 
                                                 if ($settingssss->get('contetnt_resouce') == '2') {
                                                  $id_content_week = $checkid2.','.$final_items[0]->article_id.',';;
                                                  }
                                            }else{
                                                    $article_url = "https://contentengine.advisorproducts.com/service/".$wdata['rss']['channel']['item'][0]['link'];                                                 
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                    curl_setopt($ch,CURLOPT_URL,$article_url);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                                                    curl_setopt($ch, CURLOPT_POST, false);
                                                    $adata = curl_exec($ch);			
                                                    $adata = XML2Array::createArray($adata);   	

                                                    $weklyupdatecontenttest = $adata['rss']['channel']['item']['description'];   
                                                    
                                                    $weklyupdatecontenttest = explode('&show=one',$weklyupdatecontenttest);
                                                    $weklyupdatecontenttest = $weklyupdatecontenttest[0];
                                                    $weklyupdatecontenttest = explode('http://contentsrv.advisorsites.com/showarticle.asp?domain=&article=',$weklyupdatecontenttest);
                                                    $weklyupdatecontenttest = $weklyupdatecontenttest[1];
                                                    
                                                    $id_content_week = $weklyupdatecontenttest.',';
                                                    if ($settingssss->get('contetnt_resouce') == '2') {
                                                         $id_content_week = $weklyupdatecontenttest.','.$final_items[0]->article_id.',';;
                                                     }
                                                  
                                            }
                                          				
				  
				 if ($id_content_week == '' ) {
					 $id_content_week = $weekly_items[0]->article_id.',';
				 }

				 if ($id_content_week != '' ) {
					  if ($settingssss->get('contetnfull') == "N"){
							 $showintro= "&getonlyintro=1";
						 }
						 
						 $url = JURI::base().'index.php?option=com_enewsletter&senweekly=1&task=getcontent&id='.$id_content_week.$showintro;
						 $options = array(
							   CURLOPT_RETURNTRANSFER => true,    
							   CURLOPT_HEADER         => false,   
							   CURLOPT_FOLLOWLOCATION => true,     
							   CURLOPT_ENCODING       => "",      
							   CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0", 
							   CURLOPT_AUTOREFERER    => true,   
							   CURLOPT_CONNECTTIMEOUT => 120,    
							   CURLOPT_TIMEOUT        => 120,     
							   CURLOPT_MAXREDIRS      => 10,      
							   CURLOPT_SSL_VERIFYPEER => false     
						   );

						   $ch      = curl_init( $url );
						   curl_setopt_array( $ch, $options );
						   $content11 = curl_exec( $ch );
						   $err     = curl_errno( $ch );
						   $errmsg  = curl_error( $ch );
						   $header  = curl_getinfo( $ch );
						   curl_close( $ch );
						   if (strlen($content11) > 2000000 ){
							  $content11 =  preg_replace("/<img[^>]+\>/i", " ", $content11);
						   }                                                       
							$weeklyupdate = str_replace('id="intro"', 'style="display:none;"', $content11);
				 }
                           
                             
				$dom = new SmartDOMDocument();
				$dom->loadHTML($WEEKLYUPDATE); 
				$a3 = $dom->getElementById('cta');    
				if($a3){  
                                                  $a4 = $dom->getElementById('settingintro'); 
                                                  if($a4){
                                                      $a4->nodeValue = $advisordetails['weekly_update_intro'];
                                                  }
                                                  $a5 = $dom->getElementById('settingdeclo');  
                                                  if($a5){
                                                      $a5->nodeValue = $advisordetails['weekly_update_newsletter'];      
                                                  }	

                                                 $spo = strpos($WEEKLYUPDATE,'settingintro');
                                                 $spo2 = strpos($WEEKLYUPDATE,'settingdeclo');
                                                 if($spo === false ){
                                                        $weekly_update_intro =  $advisordetails['weekly_update_intro'];
                                                 }
                                                 if($spo2 === false ){
                                                         $weekly_update_newsletter =  $advisordetails['weekly_update_newsletter'];      

                                                 }
                                                $weeklyupdate = str_replace('#ffffff','transparent',$weeklyupdate);
                                                $weeklyupdate = str_replace('#f4f4f4','transparent',$weeklyupdate);

                                                $a3->nodeValue = $weekly_update_intro.'<br>'.$weeklyupdate.'<br>'. $weekly_update_newsletter;                                   
					    
					  
				} 
                                            
				$WEEKLYUPDATE = $dom->saveHTML();	
				$WEEKLYUPDATE  = htmlspecialchars_decode($WEEKLYUPDATE);
				
				$content =  $WEEKLYUPDATE;
				
				
				$content = str_replace('display: none;', '"ids="cbvnncb""', $content);

				$dom = new SmartDOMDocument();    
				$dom->loadHTML($content);  

				$links = $dom->getElementsByTagName('div');

  
				$i=1;
				foreach ($links as $link){ 
					if ($link->getAttribute('ids') == 'cbvnncb'){
						 $link->removeAttribute('id');
						 $link->setAttribute("id", 'aabbcc'.$i);
						 $i++;
					}                       
				}
				for ($j = ( $i - 1 ) ; $j >= 1  ;$j-- ){
					$a3 = $dom->getElementById('aabbcc'.$j);
					if( $a3 != NULL ){
						 $a3->parentNode->removeChild($a3);
					}
				}



			  $linkas = $dom->getElementsByTagName('a');
			  $k=1;
			  foreach ($linkas as $linka){ 

				  if ($linka->getAttribute('ids') == 'cbvnncb'){
					   $linka->removeAttribute('id');
					   $linka->setAttribute("id", 'aabbccss'.$k);
					   $k++;
				  }                       
			  }

			  for ($j = 1 ; $j< $k ;$j++ ){
				  $a4 = $dom->getElementById('aabbccss'.$j); 
				   if($a4 != NULL){
					  $a4->parentNode->removeChild($a4);
				   }
			  }

			  $WEEKLYUPDATE =  $dom->saveHTML();    
			  $content = $WEEKLYUPDATE;
			
			}//if template_weekly
                       
					   
			if (!$content) {
			
				$webapierror = 'Error on get Content!';
			
				$queryEx = $dbEx->getQuery(true);
				$queryEx->update('#__weeklyupdate_logs');
				$queryEx->set('`errorlog` = '.$dbEx->quote($webapierror));
				$queryEx->where('`id` = '.$centcom_logid);
				$dbEx->setQuery($queryEx);
				$dbEx->execute();
				
				die($webapierror);
				
			}//if	   
      		
			$APIKEY = CONSTANT_APIKEY;
			$ACCESS_TOKEN = $advisordetails['api_key'];
			
			$gid = array();
			$cid = array();
			
			$subject = mysql_real_escape_string($advisordetails['update_subject']);
		
			$weeklyupdategroup = array();
			
			foreach ($groupRows as $group) {
				$gid[] = $group->group_id;
				$weeklyupdategroup[] = $group->group_id;
			}//for
			
						
			$listmembers = '';
			$ccontacts = '';
			
			
			
			$com_enews_config = JComponentHelper::getParams('com_enewsletter');
		
		
			if (  ( !$com_enews_config->get('autoweeklysend', 1) && !JRequest::getInt('dosendnow', 0) ) && $_GET['testapidefault'] != 1  ) {
			
				//semi-auto-process
				$db = JFactory::getDBO();
			
			
				if ($com_enews_config->get('semiautoemail', NULL)) {
					$advisor_email_a = array($com_enews_config->get('semiautoemail'));
				} else {
					//if no setting - we send to Advisor Group users for Approved
					$query = $db->getQuery(true);
					$query->select('email');
					$query->from('#__users');
					$query->where('`id` IN (select user_id from #__user_usergroup_map where group_id IN (9))');
					$db->setQuery($query);
					$advisor_email_a = $db->loadColumn();
				}//if
				
				
				//var_dump($advisor_email_a);
				
				if ($advisor_email_a) {
					$subject = "Approval For Weekly Update";
		
					$mail = JFactory::getMailer();
					$config	= JFactory::getConfig();
					
					$mailfrom = $config->get('mailfrom');
					$fromname = $config->get('fromname');
									
					//more detail
					$message = "<p>Your content is ready to go!</p>
								<p>Please review the content below and click Approve or Deny</p>
								<p><a style='font-size:16px;' href='".JURI::root(false)."index.php?option=com_enewsletter&task=sendweeklyupdate&dosendnow=1'>Approve</a> | <a style='font-size:16px;' href='".JURI::root(false)."index.php?option=com_enewsletter&view=setting&editcontent=1'>Edit</a> </p>
													";
					
					$message .= $content;
					
					
					$mail->setSender(array($mailfrom, $fromname));
					$mail->setSubject($subject);
					$mail->setBody($message);
					
					
					
					
					$mail->IsHTML(true);
					$mail->addRecipient($advisor_email_a);
					$sent = $mail->Send();
					
					$webapierror = "Sent to: ".implode(', ',$advisor_email_a)." for approved!";
					
				} else {
					$webapierror = "Not found email!";
				}//if
			
				
				$queryEx = $dbEx->getQuery(true);
				$queryEx->update('#__weeklyupdate_logs');
				$queryEx->set('`semi_auto` = 1');
				$queryEx->set('`content` = '.$dbEx->quote($content));
				$queryEx->set('`errorlog` = '.$dbEx->quote($webapierror));
				$queryEx->where('`id` = '.$centcom_logid);
				$dbEx->setQuery($queryEx);
				$dbEx->execute();
			
			
				die($webapierror);
			
			}//if semi-auto
		
		
		
                        
			if ($_GET['testapidefault'] == 1) {                           
						
				$advisordetails['newsletter_api'] = 'C';
							
				if($advisordetails['newsletter_api'] == 'C'){
								
					$weeklyupdategroup = array();
					$APIKEY   =   '8yjjgyktxnteujwf8k42xqwh';
					$ACCESS_TOKEN = '9ce7ecfa-9cc0-4e53-a7cf-d31862426366';
					$weeklyupdategroup[] = '1347232641';
					$cc = new ConstantContact(trim($APIKEY));	
					$campaign = new Campaign();					
					$lists = $cc->getLists($ACCESS_TOKEN);
											
					$config = JFactory::getConfig();
					$campaign->name = '[Run Test] - '.time().'_Weekly Update';
					$campaign->subject = '[ContantContact][Run Test]['.$config->get( 'sitename' ).'] - Weekly Update '.date('Y-m-d');
					$campaign->from_name = 'Rimba Advisorproducts';
					$campaign->from_email = 'rhan@advisorproducts.com';
					$campaign->greeting_string = '[Run Test] - Weekly Update '.date('Y-m-d');
					$campaign->reply_to_email = 'rhan@advisorproducts.com';
					$campaign->text_content = '[Run Test] - Weekly Update '.date('Y-m-d');
					$campaign->email_content = $content;
					$campaign->email_content_format = 'HTML';
		
					$i = 1;
					foreach($weeklyupdategroup as $g){
							
							$campaign->addList($g);
							$groupcontact = $cc->getContactsFromList($ACCESS_TOKEN, $g);
							
							foreach($groupcontact->results as $re){
								$ccontacts .= $i.". ".$re->email_addresses[0]->email_address."\t".date('m/d/Y')."\r\n";
								$i++;
							}
							$ccontacts .= '';
							$gid[] = $g;
					}
					
					
					try {
						$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
												
						try {
							$schedule = new Schedule();
							$cc->addEmailCampaignSchedule($ACCESS_TOKEN,$return->id, $schedule);
							$listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
							$listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
							$listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
							$listmembers .= $ccontacts;
							$data['email_id'] = $return->id;
							
						} catch (CtctException $ex) {
							$errorcnt++;
							$listmembers .= "Error in Scheduling Campaign ".$campaign_title."\r\n<br>";
							$ccerror = $ex->getErrors();
							$listmembers .= $ccerror[0]['error_message'];
							echo $listmembers;
							die;
						}
					}catch (CtctException $ex) {
						$errorcnt++;
						$listmembers .= "Error in Creating Campaign".$campaign_title."\r\n<br>";
						$ccerror = $ex->getErrors();
						$listmembers .= $ccerror[0]['error_message'];
						echo $listmembers;
						die;
					} //try
									
				}//newsletter_api
							
				if ($data['email_id'] > 0) {
					$webapierror = 'done';
				} else {
					$webapierror = 'not done';
				}
		
				die($webapierror);
		
			}//if testapidefault
		
		
			/* Check for constant contact/mailchimp and send email start */
			if ($advisordetails['newsletter_api']  == 'G') {
		
				$api_url = 'http://api2.getresponse.com';
				$client = new jsonRPCClient($api_url);
	
				$details = $client->send_newsletter(
													$ACCESS_TOKEN, array (
																			'campaign' => $gid[0],
																			'subject'  => $data['update_subject'],
																			'name'     => date('Ymd-h:i:s'),
																			"contents" => array('html' => $content),
																			'get_contacts' => array('campaigns' => $gid)
													)
									);

				if ($details && !$details['error'] && $details['result']) {
					$return = $details['result'];
				} else {
					if ($details['error']) {
						$this->setMessage($details['error']['message']." - (from GetResponse)",'error');
					} else {
						$this->setMessage('send weeklyupdate error');
					}//if
	
					$this->setRedirect('index.php?option=com_enewsletter&view=weeklyupdate');
					return;
	
				}//if
				$mailinglist = 'GetResponse List';
		
			} else  if ($advisordetails['newsletter_api'] == 'I') {

				$infusionsoft_host = $advisordetails['api_login_name'];
				
				$infusionsoft_api_key = $ACCESS_TOKEN;
				
				require JPATH_SITE.'/administrator/components/com_enewsletter/libraries/infusionsoft/infusionsoft.php';
				
				$contacts = Infusionsoft_DataService::query(new Infusionsoft_Contact(), array('Company' => 'api'));
				$email_a = array();
				foreach ($contacts as $contact) {
					$email_a[$contact->Id] = $contact->Email;
				}//for
	
				if (count($email_a)) {
	
					$contactFromApp1 = array_shift($contacts);
	
					$fromAddress = $advisordetails['from_email'];
					$toAddress = $contactFromApp1->Email;
					$ccAddresses = $advisordetails['from_email'];
					$bccAddresses = $advisordetails['from_email'];
					$contentType = 'HTML';
					$subject = $advisordetails['update_subject'];
					$htmlBody = $content;
					$textBody = strip_tags($content);
	
	
					$return = Infusionsoft_EmailService::sendEmail(array_keys($email_a), $fromAddress, $toAddress, $ccAddresses, $bccAddresses, $contentType, $subject, $htmlBody, $textBody);
	
					if (!$return) {
						$this->setMessage('send newsletter error');
						$this->setRedirect('index.php');
						return;
					}//if
	
				}//if	
				$mailinglist = 'Infusionsoft List';
								
		 
								
			} else if($advisordetails['newsletter_api'] == 'M'){
					
				$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
				$type = 'regular';

				$campaign_title = time().'_'.$advisordetails['update_subject'];
				$campaign_title =  substr($campaign_title, 0, 78);

				$opts['title'] = $campaign_title;
				$opts['subject'] = $advisordetails['update_subject'];
				$opts['from_email'] = $advisordetails['from_email'];
				$opts['from_name'] = $advisordetails['from_name'];			
				$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
				$opts['authenticate'] = true;
				$email_content = array('html'=>$content);	
			
				$mgroups = $mailchimp->lists();
				$mgroups = $mgroups['data'];
				
				$email_ids = array();
				$no_subscribers = 0;
				
				$webapierror = '';
				
				foreach($weeklyupdategroup as $g){	
					$opts['list_id'] = $g;
					$gid[] = $g;
					$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
					if ($mailchimp->errorCode){
						$errorcnt++;
						$webapierror .= "Unable to Create New Campaign ".$campaign_title."\r\n";
						$webapierror .= $mailchimp->errorMessage."\r\n";
					} else {
						$return = $mailchimp->campaignSendNow($retval);
						if ($mailchimp->errorCode){
							$errorcnt++;
							$webapierror .= "Unable to Send Campaign ".$campaign_title."\r\n";
							$webapierror .= "".$mailchimp->errorMessage."\r\n";
						} else {
							$listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
							$listcontacts = $mailchimp->listMembers($g);			
							$email_ids[] = $retval;	
							$i = 1;
							if(!empty($listcontacts['data'])){
								$listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
								$listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
								foreach($listcontacts['data'] as $lc){
									$listmembers .= $i.". ".$lc['email']."\t".date('m/d/Y')."\r\n";
									$i++;
									$no_subscribers++;
								}//for
								
								$listmembers .= "\r\n\r\n";
							}
						} 
						$data['email_id'] = implode(',',$email_ids);
		
					}//if				
				}//for
				
				$tmpmailinglist = array();
				foreach($weeklyupdategroup as $hk){
					   foreach ($mgroups as $hk1){
						   if ($hk == $hk1['id'] ) {
							$tmpmailinglist[] = $hk1['name'];   
						   }
					   }     
				}//for
				$mailinglist = implode(',',$tmpmailinglist);
				
				
				if ( $mailinglist && $data['email_id'] ) {
					$queryEx = $dbEx->getQuery(true);
					$queryEx->update('#__weeklyupdate_logs');
					$queryEx->set('`api_type` = '.$dbEx->quote($advisordetails['newsletter_api']));
					$queryEx->set('`content` = '.$dbEx->quote($content));
					$queryEx->set('`email_id` = '.$dbEx->quote($data['email_id']));
					$queryEx->set('`campaign_title` = '.$dbEx->quote($campaign_title));
					$queryEx->set('`mailinglist` = '.$dbEx->quote($mailinglist));
					$queryEx->set('`no_subscribers` = '.$dbEx->quote($no_subscribers));
					$queryEx->where('`id` = '.$centcom_logid);
					$dbEx->setQuery($queryEx);
					$dbEx->execute();
					
				} elseif ($webapierror) {
					
					$queryEx = $dbEx->getQuery(true);
					$queryEx->update('#__weeklyupdate_logs');
					$queryEx->set('`api_type` = '.$dbEx->quote($advisordetails['newsletter_api']));
					$queryEx->set('`errorlog` = '.$dbEx->quote($webapierror));
					if (JRequest::getInt('dosendnow')) $queryEx->set('`semi_auto` = 1');
					$queryEx->where('`id` = '.$centcom_logid);
					$dbEx->setQuery($queryEx);
					$dbEx->execute();
					
					die($webapierror);
				
				}//if
				
			
			} else if($advisordetails['newsletter_api'] == 'C'){
							
				$cc = new ConstantContact(trim($APIKEY));	
				$campaign = new Campaign();
				
				$lists = $cc->getLists($ACCESS_TOKEN);
					
				$tmpmailinglist = array();					
				foreach($weeklyupdategroup as $hk){
					foreach ($lists as $hk1){
						if ($hk == $hk1->id ) {
							$tmpmailinglist[] = $hk1->name;   
						}
					}     
				}
				$mailinglist = implode(',',$tmpmailinglist);
				
								
				$campaign_title = time().'_'.$advisordetails['update_subject'];
				$campaign_title =  substr($campaign_title, 0, 78);
				$campaign->name = $campaign_title;
				$campaign->subject = $advisordetails['update_subject'];
				$campaign->from_name = $advisordetails['from_name'];
				$campaign->from_email = $advisordetails['from_email'];
				$campaign->greeting_string = $advisordetails['update_subject'];
				$campaign->reply_to_email = $advisordetails['from_email'];
				$campaign->text_content = $advisordetails['update_subject'];
				$campaign->email_content = $content;
				$campaign->email_content_format = 'HTML';
					
				$i = 1;
				
				$no_subscribers = 0;
				foreach($weeklyupdategroup as $g){
					$campaign->addList($g);
					$groupcontact = $cc->getContactsFromList($ACCESS_TOKEN, $g);
					
					foreach($groupcontact->results as $re){
						$ccontacts .= $i.". ".$re->email_addresses[0]->email_address."\t".date('m/d/Y')."\r\n";
						$i++;
						$no_subscribers++;
					}//for
					$ccontacts .= '';
					$gid[] = $g;
				}//for
			
				try {
					$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
					try {
						$schedule = new Schedule();
                                                $schedule->scheduled_date = date('Y-m-d',  strtotime('+1 hours')).'T'.date('H:i',  strtotime('+1 hours')).':00.000-0400';
						$cc->addEmailCampaignSchedule($ACCESS_TOKEN,$return->id, $schedule);
						$listmembers .="Campaign ".$campaign_title." sent to Email Campaign system successfully!\r\n";
						$listmembers .= "Campaign Name : ".$campaign_title."\r\n\r\n";
						$listmembers .= "----------------------------\r\nCampaign email sent details :\r\n---------------------------- \r\n";
						$listmembers .= $ccontacts;
						$data['email_id'] = $return->id;
						
						
						$queryEx = $dbEx->getQuery(true);
						$queryEx->update('#__weeklyupdate_logs');
						$queryEx->set('`api_type` = '.$dbEx->quote($advisordetails['newsletter_api']));	
						$queryEx->set('`content` = '.$dbEx->quote($content));
						$queryEx->set('`email_id` = '.$dbEx->quote($data['email_id']));
						$queryEx->set('`campaign_title` = '.$dbEx->quote($campaign_title));
						$queryEx->set('`mailinglist` = '.$dbEx->quote($mailinglist));
						$queryEx->set('`no_subscribers` = '.$dbEx->quote($no_subscribers));
						$queryEx->where('`id` = '.$centcom_logid);
						$dbEx->setQuery($queryEx);
						$dbEx->execute();
						
						
					} catch (CtctException $ex) {
						$errorcnt++;
						$listmembers .= "Error in Scheduling Campaign ".$campaign_title."\r\n<br>";
						$ccerror = $ex->getErrors();
						$listmembers .= $ccerror[0]['error_message'];
						
						$queryEx = $dbEx->getQuery(true);
						$queryEx->update('#__weeklyupdate_logs');
						$queryEx->set('`api_type` = '.$dbEx->quote($advisordetails['newsletter_api']));
						$queryEx->set('`errorlog` = '.$dbEx->quote($listmembers));
						if (JRequest::getInt('dosendnow')) $queryEx->set('`semi_auto` = 1');
						$queryEx->where('`id` = '.$centcom_logid);
						$dbEx->setQuery($queryEx);
						$dbEx->execute();
						
						die($listmembers);
					}
				}catch (CtctException $ex) {
					
					$errorcnt++;
					$listmembers .= "Error in Creating Campaign".$campaign_title."\r\n<br>";
					$ccerror = $ex->getErrors();
					$listmembers .= $ccerror[0]['error_message'];
					
					$queryEx = $dbEx->getQuery(true);
					$queryEx->update('#__weeklyupdate_logs');
					$queryEx->set('`api_type` = '.$dbEx->quote($advisordetails['newsletter_api']));
					$queryEx->set('`errorlog` = '.$dbEx->quote($listmembers));
					if (JRequest::getInt('dosendnow')) $queryEx->set('`semi_auto` = 1');
					$queryEx->where('`id` = '.$centcom_logid);
					$dbEx->setQuery($queryEx);
					$dbEx->execute();
					
					die($listmembers);
					
				}//try
			
			}//if newsletter_api
		

			
			/* Save update mail in database start */
			if ( empty($data['email_id']) ) {
			
				$webapierror = "Weekly update not sent!\r\n";
				die($webapierror);
				
			} else {
					
				$approval_status = '';
				$email_sent_status = '1';
				$return_id = '';
				
				$title = $subject;
							
				//Query to insert update mail
				$updateinsertquery = "INSERT INTO  #__enewsletter(id, user_id, title, subject, intro, trailer, mass_email_content, type, api_type, email_sent_status, email_id,is_active, approval_status, approval_email_id, content, weekly_update_content, dte_created, dte_modified) ".
					   "VALUES ".
					   "(null,".$advisordetails['id'].",'".$db->quote($title)."', '".$db->quote($subject)."','".$db->quote($advisordetails['weekly_update_intro'])."', '', '', 'weeklyupdate','".$db->quote($advisordetails['newsletter_api'])."', '1', '".$db->quote($data['email_id'])."', '".$db->quote($email_sent_status)."', '".$db->quote($approval_status)."','".$db->quote($return_id)."', ".$db->quote($content).", ".$db->quote($articlecontent).",now(),now() )";
				
				$db->setQuery($updateinsertquery);
				$db->execute();
				$id = $db->insertid();
			
													
				//Query to insert articles of update mail
				foreach($cid as $c){
					$articleinsertquery = "INSERT INTO #__enewsletter_article(id,e_id,article_id,show_image) ".
												 "VALUES(null,".$id.",".$c.",'')";
					$db->setQuery($articleinsertquery);
					$db->execute();
				}//for
					
					
				
				//Query to insert groups of update mail
				foreach($gid as $g){
					$groupinsertquery = '';
					$groupinsertquery = "INSERT INTO #__enewsletter_groups(id,e_id,group_id) ".
											"VALUES(null,".$id.",'".$g."')";
					$db->setQuery($groupinsertquery);
					$db->execute();
					
					
				}//for
				
				//Query to insert history of update mail
				$historyinsertquery = "INSERT INTO #__enewsletter_history (id,user_id,title,campaign_title,subject,content,e_id,email_id,api_type,dte_send,mailinglist)".
				   "VALUES ".
				   "(null,".$advisordetails['id'].",'WeeklyUpdate On Friday', ".$db->quote($campaign_title).", '".$db->quote($subject)."', ".$db->quote($content).",".$id.", '".$data['email_id']."','".$advisordetails['newsletter_api']."',now(),'".$mailinglist."')";
						   
				
				$db->setQuery($historyinsertquery);
				$db->execute();
				
				
				
				$queryEx = $dbEx->getQuery(true);
				$queryEx->update('#__weeklyupdate_logs');
				$queryEx->set('`title` = '.$dbEx->quote($title));
				$queryEx->set('`subject` = '.$dbEx->quote($subject));
				$queryEx->set('`dte_send` = '.$dbEx->quote(JFactory::getDate()->toMySQL()));
				if (JRequest::getInt('dosendnow')) $queryEx->set('`semi_auto` = 1');
				$queryEx->where('`id` = '.$centcom_logid);
				$dbEx->setQuery($queryEx);
				$dbEx->execute();
				
				/* Update mail sent to CC list start */
				if($advisordetails['archive_cc_list'] != ''){
				
					$mailer = JFactory::getMailer();
					$config = JFactory::getConfig();
					$sender = array( 
									$advisordetails['from_email'],
									$advisordetails['from_name']
						 );		
						  
					$mailer->setSender($sender);
	
					$recipient = explode(',',$advisordetails['archive_cc_list']);	
					$mailer->addRecipient($recipient);
					$mailer->isHTML(true);
					$mailer->Encoding = 'base64';
					$mailer->setSubject($subject);
					$mailer->setBody($content);
					$send = $mailer->Send();
			
					if ( $send !== true ) {
						$errorcnt++;
						$emailerror = "Email Digest not sent! \r\n";
						die($emailerror);
					}
				}//if archive_cc_list
					
			}//if empty email_id	
			
		
		}//if setting_ok
	
		if($listmembers != ''){
			$filecontent .= $listmembers."\r\n\r\n";
		}
			
		if($error != ''){
			$filecontent .= "----------------------------\r\nMy Sql error :\r\n---------------------------- \r\n".$error."\r\n\r\n\r\n";
		}
		
		if($sqlerror != ''){
			$filecontent .= "----------------------------\r\nMS Sql error :\r\n---------------------------- \r\n".$sqlerror."\r\n\r\n\r\n";
		}		
		
		if($emailerror != ''){
			$filecontent .= "----------------------------\r\nEmail Digest error :\r\n---------------------------- \r\n".$emailerror."\r\n\r\n\r\n";
		}
	
		if($webapierror != ''){
			$filecontent .= "----------------------------\r\nWeekly update error :\r\n---------------------------- \r\n".$webapierror."\r\n\r\n\r\n";
		}
		
		
			
		$filecontent .= "\r\n\r\n";
		echo $filecontent;
		
		echo '<div style="font-size:40px;" >Your Enewsletter Has Been Sent!!</div>';
		
		die();
		
	}//func
	
	
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
        
  }//func
	
	
	/**
	 * Used to update approved mail by compliance office
	 *
	 * @return void
	 */
	public function mailapproval(){
	
		$config = new JConfig();
		$host = $config->host;
		$user = $config->user;
		$password = $config->password;
		$database = $config->db;
		$dbprefix = $config->dbprefix;
		
		$status = $_POST['status'];
		$emailid = $_POST['emailid'];
    

		$returnstatus = array();
		
		
		// Check for valid status otherwise return error message 
		if($status == 'APR' || $status == 'PND' || $status == 'REJ'){
			
			// Check for email id otherwise return error message
			if($emailid == ''){
				$returnstatus['success'] = '';
				$returnstatus['error_code'] = '2';
				$returnstatus['error_message'] = 'MailID can not be NULL';
				header('content-Type: application/json');
				echo json_encode($returnstatus);return;
			}
		
			/* connect to database and get advisor details start */
			mysql_connect($host,$user,$password) or die(mysql_error());
			mysql_select_db($database)or die(mysql_error());
			
			$selectequery = "SELECT * FROM ".$dbprefix."enewsletter where approval_email_id = ".$emailid."";
			$eresult = mysql_query($selectequery);
      
			if (mysql_errno()) {
				$error = "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectequery."\r\n";
			}else{
				if(mysql_num_rows($eresult) == 1){
        
                                        $newsletter = mysql_fetch_assoc($eresult);
					
					$selectgroupquery = "SELECT * FROM ".$dbprefix."enewsletter_groups where e_id = ".$newsletter['id']."";
					$groupresult = mysql_query($selectgroupquery);

					if (mysql_errno()) {
						$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectgroupquery."\r\n";
					} 
				
					$selectdetailsquery = "SELECT * FROM ".$dbprefix."advisorsettings";
					$result = mysql_query($selectdetailsquery);
					if (mysql_errno()) {
						$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$selectdetailsquery."\r\n";
					} 
					$advisordetails = mysql_fetch_assoc($result);
					
					
					// Check for approve status
					if($status == 'APR'){
						$APIKEY = CONSTANT_APIKEY;
						$ACCESS_TOKEN = $advisordetails['api_key'];
						$listmembers = '';
						$listcontacts = '';
						$campaignerror = '';
						if($advisordetails['newsletter_api'] == 'M'){
							$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
			
							$type = 'regular';
							$campaign_title = $newsletter['title'].time();
                                                        $campaign_title = time().'_'.$newsletter['title'];
                                                        $campaign_title =  substr($campaign_title, 0, 78);
							$opts['title'] = $campaign_title;
							$opts['subject'] = $newsletter['subject'];
							$opts['from_email'] = $advisordetails['from_email'];
							$opts['from_name'] = $advisordetails['from_name'];		
							$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
							$opts['authenticate'] = true;			
							$email_content = array('html'=>$newsletter['content']);
							
							$email_ids = array();
							
							$i = 0;
							
							$campaigngroups = array();		
							while($grow = mysql_fetch_assoc($groupresult)){
								$campaigngroups[] = $grow['group_id'];
								$opts['list_id'] = $grow['group_id'];							
								$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
								if ($mailchimp->errorCode){
									$returnstatus['success'] = '';
									$returnstatus['error_code'] = '4';
									$returnstatus['error_message'] = $mailchimp->errorMessage;
									header('content-Type: application/json');
									echo json_encode($returnstatus);return;
								} else 
								{
									$return = $mailchimp->campaignSendNow($retval);
									if ($mailchimp->errorCode){
										$returnstatus['success'] = '';
										$returnstatus['error_code'] = '4';
										$returnstatus['error_message'] = $mailchimp->errorMessage;
										header('content-Type: application/json');
										echo json_encode($returnstatus);return;
									} else {			
										$email_ids[] = $retval;	
									} 
								}	
								$i++;
							}
							$campaignid = implode(',',$email_ids);
							
						}else if($advisordetails['newsletter_api'] == 'C'){
							$cc = new ConstantContact(trim($APIKEY));	
							$campaign = new Campaign();
                                                        $campaign_title = $newsletter['title'].time();
                                                        $campaign_title = time().'_'.$newsletter['title'];
							$campaign->name = $campaign_title;
							$campaign->subject = $newsletter['subject'];
							$campaign->from_name = $advisordetails['from_name'];
							$campaign->from_email = $advisordetails['from_email'];
							$campaign->greeting_string = $newsletter['subject'];
							$campaign->reply_to_email = $advisordetails['from_email'];
							$campaign->text_content = $newsletter['subject'];
							$campaign->email_content = $newsletter['content'];
							$campaign->email_content_format = 'HTML';
							
							$i = 0;
							
							$campaigngroups = array();		
							while($grow = mysql_fetch_assoc($groupresult)){
								$campaigngroups[] = $grow['group_id'];
								$campaign->addList($grow['group_id']);
								$i++;
							}
							
							try {
								$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
								try {
									$schedule = new Schedule();
									$cc->addEmailCampaignSchedule($ACCESS_TOKEN,$return->id, $schedule);
									$campaignid = $return->id;
									
								} catch (CtctException $ex) {
									$ccerror = $ex->getErrors();
									$returnstatus['success'] = '';
									$returnstatus['error_code'] = '5';
									$returnstatus['error_message'] = $ccerror[0]['error_message'];
									header('content-Type: application/json');
									echo json_encode($returnstatus);return;
								}
							}catch (CtctException $ex) {
								$ccerror = $ex->getErrors();
								$returnstatus['success'] = '';
								$returnstatus['error_code'] = '5';
								$returnstatus['error_message'] = $ccerror[0]['error_message'];
								header('content-Type: application/json');
								echo json_encode($returnstatus);return;
							} 
						}
						$email_sent_status = '1';
					}else{
						$campaignid = '';
						$email_sent_status = '0';
					}

					// Update status in enewsletter table 
					$updatequery = "UPDATE ".$dbprefix."enewsletter set email_sent_status = '".$email_sent_status."', email_id = '".$campaignid."', approval_status = '".$status."' where approval_email_id = ".$emailid."";
					$result = mysql_query($updatequery);
					if (mysql_errno()) {
						$error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$updatequery."\r\n";
					} 
					
					// If status is APR it will insert record of sent mail in history table  
					if($status == 'APR'){
					
						$inserthistory = "INSERT INTO ".$dbprefix."enewsletter_history(id,user_id,title,campaign_title,subject,content,e_id,email_id,api_type,dte_send,mailinglist) VALUES('',".$newsletter['user_id'].",'".mysql_real_escape_string($newsletter['title'])."','".mysql_real_escape_string($campaign_title)."','".mysql_real_escape_string($newsletter['subject'])."','".mysql_real_escape_string($newsletter['content'])."',".$newsletter['id'].", '".$campaignid."','".$newsletter['api_type']."',now(),'')";
                                                
						$historyresult = mysql_query($inserthistory);
						if (mysql_errno()) {
							echo $error .= "MySQL error ".mysql_errno().": ".mysql_error()."\r\nWhen executing:\r\n".$inserthistory."\r\n";
						}
						
						
						/* Send mail to archive list start */
						if($advisordetails['archive_cc_list'] != '' && $advisordetails['archive_cc_list'] != null){
							
							if($advisordetails['newsletter_api'] == 'C'){
								$listcontacts = '<br><center><br>'.$newsletter['subject'].' sent to :<br><br><table border="1"><th> </th><th>Name</th><th>Time</th>';
								$curtime = date('m-d-Y h:m:i');
								$i = 1;
								
								
								foreach($campaigngroups as $cg){
									$contacts = $cc->getContactsFromList($ACCESS_TOKEN,$cg);
									foreach($contacts->results as $con){
										$listcontacts .= '<tr>';
										if($con->status == 'ACTIVE'){
											$listcontacts .= '<td>'.$i.'</td>';
											$listcontacts .= '<td>';
											$listcontacts .= $con->email_addresses[0]->email_address.'</td>';
											$listcontacts .= '<td>'.$curtime.'</td>';
											$i++;
										}
										$listcontacts .= '</tr>';
									}
								}
								$listcontacts .= '</table></center>';
								
							}else if($advisordetails['newsletter_api'] == 'M'){
								$listcontacts = '<br><center><br>'.$newsletter['subject'].' sent to :<br><br><table border="1"><th> </th><th>Name</th><th>Time</th>';
								$curtime = date('m-d-Y h:m:i');
								$i = 1;
								foreach($campaigngroups as $g){
									$contacts = $mailchimp->listMembers($g);
									foreach($contacts['data'] as $con){
										$listcontacts .= '<tr>';
										$listcontacts .= '<td>'.$i.'</td>';
										$listcontacts .= '<td>';
										$listcontacts .= $con['email'].'</td>';
										$listcontacts .= '<td>'.$curtime.'</td>';
										$listcontacts .= '</tr>';
										$i++;
									}
								}
								$listcontacts .= '</table></center>';
							}
							
							
							
							$mailer = JFactory::getMailer();
							$config = JFactory::getConfig();
							$sender = array( 
								$advisordetails['from_email'],
                                                                $advisordetails['from_name']
								 );		
								  
							$mailer->setSender($sender);
							$ccemail = explode(',',$advisordetails['archive_cc_list']);
							$mailer->addRecipient($ccemail);
							$mailer->isHTML(true);
							$mailer->Encoding = 'base64';
							$mailer->setSubject($advisordetails['update_subject']);
							$mailer->setBody($newsletter['content'].$listcontacts);
							$send = $mailer->Send();
					
							if ( $send !== true ) {
								$errorcnt++;
								$emailerror = "Email not sent! \r\n";
							}
							
						}
						/* Send mail to archive list end */	
					}	
									
					if($error != ''){	
						$returnstatus['success'] = '';
						$returnstatus['error_code'] = '6';
						$returnstatus['error_message'] = $error;
						header('content-Type: application/json');
						echo json_encode($returnstatus);return;	
					}else{
						$returnstatus['success'] = 'Success !';
						$returnstatus['error_code'] = '';
						$returnstatus['error_message'] = '';
						header('content-Type: application/json');
						echo json_encode($returnstatus);return;	
					}   
		
				}else{
					$returnstatus['success'] = '';
					$returnstatus['error_code'] = '3';
					$returnstatus['error_message'] = 'Invalid MailID';
					header('content-Type: application/json');
					echo json_encode($returnstatus);return;
				}
			}
		
		}else{
			
			$returnstatus['success'] = '';
			$returnstatus['error_code'] = '1';
			$returnstatus['error_message'] = 'Invalid Status';
			header('content-Type: application/json');
			echo json_encode($returnstatus);return;
		}
  }//func
  
  
  
  
  function makeCaptcha() {
  
  	$app =& JFactory::getApplication();
  
  	$moduleid = JRequest::getInt('module', 0);
  
	$code = rand(1000,9999);
	
	$statename = "enewscaptchacode$moduleid";
	
	$app->setUserState($statename, $code );
	
	$im = imagecreatetruecolor(50, 24);
	$bg = imagecolorallocate($im, 22, 86, 165); //background color blue
	$fg = imagecolorallocate($im, 255, 255, 255);//text color white
	imagefill($im, 0, 0, $bg);
	
	imagestring($im, 5, 5, 5,  $app->getUserState($statename), $fg);
	
	header("Cache-Control: no-cache, must-revalidate");
	header('Content-type: image/png');
	imagepng($im);
	imagedestroy($im);
	
	$app->close();
	
  }//func
  
  
	
}
