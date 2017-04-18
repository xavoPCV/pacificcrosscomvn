<?php
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/components/com_apicontent/helpers/fnc.php';
//require_once JPATH_ROOT.'/modules/mod_apicontentcatcloud/helper.php';

class EnewsletterViewsetting extends JViewLegacy {

	public function display($tpl = null) {
            
                $db = JFactory::getDbo();
                $app = JFactory::getApplication();
                $user  = JFactory::getUser();
                $config = new JConfig();		
                JRequest::setVar('tmpl', 'component');
                $app->setTemplate('system');                 
                $session =& JFactory::getSession();
                if ($user->id == '') {
                    $baseurl = JURI::base();
                    foreach (array('apsample1') as $testsite) {
                        $pos = strrpos($baseurl, $testsite);
                        if ($pos === false) {
                            $app->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JURI::base() . 'index.php?option=com_enewsletter&view=editletter'));
                        }
                      
                    }//for                     
                }     
                
                
                 $check_license = '';
                
                // connect to centcome
         //       if ($session->get(md5($database)) == '' ){                    
                  
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
                    
                    // get function 
                    $checkfunction  = explode(',', $site_row->site_products );
                    if(in_array('1', $checkfunction)){
                    //     $session->set(md5($database),1);
                        $check_license = '';
                    }else{
                    //     $session->set(md5($database),-1);
                        $check_license = '_limit';
                    }
                    
                
       //         }
                
//                if ( $session->get(md5($database)) == -1 ){
//                    
//                }else{
//                   
//                }
                
                
                
                $tpl = 'style2'.$check_license;  
                
                $query = 'SELECT * FROM #__advisorsettings  where  user_id IN (0,'.$user->id.') order by user_id ';
                $db->setQuery($query);  
                $this->allsetting = $db->loadObject();
				
				 // select setting user    
				$query = 'SELECT * FROM #__advisorsettings  ORDER BY id ASC';
				$db->setQuery($query);  
				$advisordetails = $db->loadObjectList(); 
				foreach ($advisordetails as $rowasa){
					if($rowasa->user_id == $user->id){
						$datasets = $rowasa;
					}
				}                
				if ($datasets == ''){
					$datasets =  $advisordetails[0];                   
				}
				
				$this->advisorsettings = $datasets;
                
                
                $query = 'SELECT group_id FROM #__weeklyupdate_group ';
                $db->setQuery($query);  
                $this->group = $db->loadObjectlist();
                
                
                $this->com_params = JComponentHelper::getParams('com_enewsletter');
                   
                $query = "SELECT * from #__email_templates where status = 'published' order by id"; 
                $db->setQuery($query);  
                $this->tems = $db->loadObjectlist();  
                
                
                 $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/" ;
                    if ($handle = opendir($directory))
                    {
                        while (false !== ($file = readdir($handle)))
                        {
                            if ($file != '.' && $file != '..')
                            {
                            
                                $file_array = explode('.', $file);
                                    
                                if ($file_array[count($file_array)-1] == 'html'){     
                           
                                       $pos = strpos( $file ,  "_".$user->id.'_' );
                                            $checknoname = strpos( $file ,  "_".$user->id.'_.html' );
                                            $checknoname1 = strpos( $file ,  'weeklyupdateright_defaults.html' );
                                            $checknoname2 = strpos( $file ,  'weeklyupdate_defaults.html' );
                                            $checknoname3 = strpos( $file ,  'weekly_defaults.html' );
                                            $checknoname4 = strpos( $file ,  'weeklyupdate.html' );
                                          
                                            
                                            if ($checknoname !== false || $checknoname1 !== false || $checknoname2 !== false || $checknoname3 !== false || $checknoname4 !== false) {
                                                  continue;
                                                }
                                                
                                                
                                           if ($pos !== false) {
                                                    
                                                $a = str_replace( $this->idt."_".$user->id.'_', '', $file);
                                                $this->tems_layout[]  = str_replace('.html', '', $a);
                                                $this->tems_user[]  = str_replace('.html', '', $file);
                                           } 
                                           
                                     $this->tems_user1[]  = str_replace('.html', '', $file);
                                }
                             }
                        }
                        closedir($handle);
                    }
                    
              
                       $this->tems_user = array_unique($this->tems_user);
                
                        if (JRequest::getVar('changetemps')){
                                 $session->set( 'changetemps', JRequest::getVar('changetemps') );       
                                 
                                 $a = explode(' : ', JRequest::getVar('changetemps')) ;
                                 $this->changetemps = $a[1];
                                 if ($this->changetemps == '') {
                                      $this->changetemps = $a[0];
                                 }      
                        }    
                         
                     
                        if ($this->allsetting->template_weekly != '' ) {
                      
                             $check_f1= JPATH_SITE."/administrator/components/com_enewsletter/templates/".$this->allsetting->template_weekly;
                            if (file_exists($check_f1)) {         

                                $this->maildata = file_get_contents($check_f1);

                            }  
                            
                            // select setting user    
                            $query = 'SELECT * FROM #__advisorsettings  ORDER BY id ASC';
                            $db->setQuery($query);  
                            $advisordetails = $db->loadObjectList(); 
                            foreach ($advisordetails as $rowasa){
                                if($rowasa->user_id == $user->id){
                                    $datasets = $rowasa;
                                }
                            }                
                            if ($datasets == ''){
                                $datasets =  $advisordetails[0];                   
                            }
                             $dta_tmp = array(1);

                
                            $this->maildata = enewsletterHelper::replaceTemplateCode('', $dta_tmp, $datasets, $this->maildata, true);   
                            
                            
                            
                              
                                                $app = JFactory::getApplication();
                                              //  $model= JModelLegacy::getInstance('Subscription', 'EnewsletterModel', array('ignore_request' => true));
                                              //  $model->setWeeklyUpdateContent($advisordetails);
                                              //  $cid = array();
                                              //  $weeklyupdate = $app->getUserState("com_enewsletter.Weeklyupdatedesc");
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
                                                  if ($this->com_params->get('typecontentchoice') == 'tab2'){      
                                                        $id_content_week = $weekly_items[0]->article_id.',';
                                                  }else if ($this->com_params->get('typecontentchoice') == 'tab3'){    
                                                       // $id_content_week = $final_items[0]->article_id.',';
                                                       $id_content_week = $weekly_items[0]->article_id.',';
                                                  }
                                          
                                                 if ($this->com_params->get('contetnt_resouce') == '2') {
                                                     $id_content_week = $weekly_items[0]->article_id.','.$final_items[0]->article_id.',';;
                                                 }

                                             if ($id_content_week != '' ) {
                                                 // curl echo JURI::base().index.php?option=com_enewsletter&task=getcontent&id="+check
                                                     if ($this->com_params->get('contetnfull') == "N"){
                                                         $showintro= "&getonlyintro=1";
                                                     }
                                                     $url = JURI::base().'index.php?option=com_enewsletter&task=getcontent&id='.$id_content_week.$showintro;
                                                    
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
                                                       $content = curl_exec( $ch );
                                                       $err     = curl_errno( $ch );
                                                       $errmsg  = curl_error( $ch );
                                                       $header  = curl_getinfo( $ch );
                                                       curl_close( $ch );
                                                       if (strlen($content) > 1000000 ){
                                                          $content =  preg_replace("/<img[^>]+\>/i", " ", $content);
                                                       }
                                                       //$content  = str_replace('edit_content', '', $content);
                                                      
                                                        $weeklyupdate = str_replace('id="intro"', 'style="display:none;"', $content);
                                             }
                           
                                                $cid = $app->getUserState("com_enewsletter.Weeklyupdatearticleid");	
                            
                            
                                            $dom = new SmartDOMDocument();
                                            $dom->loadHTML($this->maildata); 
                                            $a3 = $dom->getElementById('cta');    
                                            if($a3){
                                                  $a4 = $dom->getElementById('settingintro'); 
                                                
                                                  if($a4){
                                                      $a4->nodeValue = $this->allsetting->weekly_update_intro;
                                                  }
                                                  
                                                  $a5 = $dom->getElementById('settingdeclo');  
                                                
						  if($a5){
                                                      $a5->nodeValue = $this->allsetting->weekly_update_newsletter;     
                                                  }	
                                                  
                                                  
                                                         $spo = strpos($this->maildata,'settingintro');
                                                         $spo2 = strpos($this->maildata,'settingdeclo');
                                                         if($spo === false ){
                                                            $weekly_update_intro =  $this->allsetting->weekly_update_intro;
                                                         }
                                                         if($spo2 === false ){
                                                             $weekly_update_newsletter =  $this->allsetting->weekly_update_newsletter;                                                             
                                                         }
														 
														 
							$weeklyupdate = str_replace('#ffffff','transparent',$weeklyupdate);
							$weeklyupdate = str_replace('#f4f4f4','transparent',$weeklyupdate);
                                                        
                                                        $a3->nodeValue = $weekly_update_intro.$weeklyupdate.'<br>'.$weekly_update_newsletter;
                                                                                             
                                            } 
                                            
                                            
                                           

                                            
                                            $this->maildata = $dom->saveHTML();	
                                            $this->maildata  = htmlspecialchars_decode($this->maildata);
                        }
				  
                                                        $this->maildata = preg_replace('/<link[^>]*>/i', '', $this->maildata);
                
                   
                   
                          
                                                       // main backfrou
                                                       
                                                       
                                                        $idu = NULL;
                                                        $channel_id = $this->com_params->get('inputyoutube');
                                                        
                                                        if ( $channel_id != '' ) {
                                                            
                                                            try {
                                                                    $xml = simplexml_load_file(sprintf('https://www.youtube.com/feeds/videos.xml?channel_id=%s', $channel_id));
                                                                    if (empty($xml)){
                                                                         $xml = simplexml_load_file(sprintf('https://www.youtube.com/feeds/videos.xml?user=%s', $channel_id));
                                                                    }

                                                                    if (!empty($xml->entry[0]->children('yt', true)->videoId[0])){
                                                                        $id = $xml->entry[0]->children('yt', true)->videoId[0];
                                                                        $link = $xml->entry[0]->children('link', true)->videoId[0];
                                                                    }
                                                                    $linkimage = "http://img.youtube.com/vi/$id/0.jpg";


                                                                    $hhhtt = "<div style='width:100%; float:left; text-align:center;padding-bottom: 55px  ;  position: relative;' ><a href='http://www.youtube.com/watch?v=$id' target='_blank' ><div style='    position: absolute;    z-index: 6;    margin-left: 143px;' ><img src='".juri::base().'components/com_enewsletter/assets/images/videoplayer.png'."' style='width:520px;' /></div><div><img src='$linkimage' style='width: 505px;  ' /></div></a></div>";                                                

                                                                    $this->weeklyvideo = '<div style="    color: #'.$this->com_params->get('maintextgc','000000').';   background-color:#'.$this->com_params->get('backgc','ffffff').'; padding: 20px; width: 800px;    margin: 0 auto;" ><div style="text-align:center;    margin-bottom: 30px;" > '."<img style=  '  max-width: 500px;    min-width: 300px;' border=0 src='".JURI::base(false)."media/com_enewsletter/logo/".$this->advisorsettings->logo."' />".'<br></div>'.base64_decode($this->com_params->get('youtube_intro')).$hhhtt.'<br>'.base64_decode($this->com_params->get('youtubedescription')).'</div>';

                                                                    $this->weeklyvideo = str_replace('<a', "<a style='color:#".$this->com_params->get('linktextgc','2366bd')."' " , $this->weeklyvideo); 
  
                                                                } catch (Exception $e) {

                                                                }


                                                        }
		parent::display($tpl);	
                
	}//func

}
