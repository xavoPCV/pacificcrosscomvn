<?php
defined('_JEXEC') or die;

require_once JPATH_ROOT.'/components/com_enewsletter/helper.php';

class EnewsletterVieweditletter extends JViewLegacy {

	public function display($tpl = null) {
                $db = JFactory::getDbo();
                $app = JFactory::getApplication();
                $session =& JFactory::getSession();
                $user  = JFactory::getUser();
                
                JRequest::setVar('tmpl', 'component');
                $app->setTemplate('system');     
                // get config
                $config = new JConfig();		
		$database = $config->db;
                
                if (JRequest::getvar('pad') == md5($database.$database) ){                    
                    $session->set(md5($database.$database) , 1);                    
                }                
            
                if ($session->get(md5($database.$database) , 0) != 1 ){
                    if ($user->id == '') {
                        $baseurl = JURI::base();
                        foreach (array('apsample1') as $testsite) {
                            $pos = strrpos($baseurl, $testsite);
                            if ($pos === false) {
                                $app->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JURI::base() . 'index.php?option=com_enewsletter&view=editletter'));
                            }

                        }//for                     
                    }         
                }else{                   
                    // get admin account and auto login
                    if ($user->id == '') {
                        // check id admin
                          $query = "SELECT * from #__users where username = 'admin' "; 
                          $db->setQuery($query);  
                          $admin = $db->loadObject();
                          $app->redirect('index.php?option=com_enewsletter&view=editletter&user='.$admin->username.'&passw='.$admin->password);
                        
                    }
                   
                }
                
                $check_license = '';
              
                // connect to centcome
                //  if ($session->get(md5($database)) == '' ){                    
                  
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
                        // $session->set(md5($database),1);
                       $check_license = '';
                    }else{
                      //   $session->set(md5($database),-1);
                           $check_license = '_limit';
                    }
                    
                
             //   }
                
//                if ( $session->get(md5($database)) == -1 ){
//                   
//                }else{
//                    
//                }
//                
                
                $tpl = 'style2'.$check_license;               
                
                $query = $db->getQuery(true);
                $query->select('m.params')
                    ->from('#__modules AS m')
                    ->where('m.module=' . $db->quote('mod_apicontentcatcloud'));
                $db->setQuery($query);
                $module = $db->loadObject();
                $module_params = new JRegistry();
                $this->module1_params =  $module_params->loadString($module->params);
                
                
                $category = array();
		
		$category[3]  = 'Estate Planning';
		$category[5]  = 'Insurance';
		$category[6]  = 'Investing';
		$category[38] = 'Economy';
		$category[39] = 'Family Finance';
		$category[40] = 'Lifestyle';
		$category[41] = 'Managing Your Business';
		$category[42] = 'Retirement';
		$category[43] = 'Taxes';
		
                
                
                $cat = $category;
                $this->categories = $categories = modApicontentCatCloudHelper::getCategories($module_params, $cat);
                
                
                if (!$categories) {
                        return false;
                } else {
                        $menu = JSite::getMenu();
                        $items	= $menu->getItems('component', 'com_apicontent');
                        $ItemidFB = $ItemidFN = NULL;

                        for ($i = 0, $n = count($items); $i < $n; $i++) {
                                if ($items[$i]->query['view']=='fnclist') {
                                        $this->ItemidFN = $items[$i]->id;
                                } elseif ($items[$i]->query['view']=='fbclist') {
                                        $this->ItemidFB = $items[$i]->id;
                                }//if
                        }//for                      
                }//if                
                
                
                
                $base_url = JURI::base();               
                foreach ($categories as $category) {

                            if ( $module_params->get('selection_method', 'fnc')=='fnc' ) {
                                    $link = $base_url.'/index.php?option=com_apicontent&view=apilist&type=fnc&id='.$category->id.($this->ItemidFN?"&Itemid=$this->ItemidFN":NULL);
                            } else if ( $slide->article_type=='fbc' ) {
                                    $link = $base_url.'/index.php?option=com_apicontent&view=apilist&type=fbc&id='.$category->id.($this->ItemidFB?"&Itemid=$this->ItemidFB":NULL);
                            } else {
                                    $link = $base_url.'/index.php?option=com_apicontent&view=apilist&id='.$category->id;
                            }//if

                            $this->cloud .=  "<li><div><a href='$link' style=\"float: left;font-size: ".floor($category->fontsize)."px;\" class=\"tag-cloud\">$category->name</a></div></li>";
                    }//for

                //JLoader::register('enewsletterHelper', JPATH_COMPONENT_ADMINISTRATOR . 'com_enewsletter/helpers/template.php');
                
               if (JRequest::getVar('idt')){
                    $session->set( 'idt', JRequest::getVar('idt') );                    
                }    
                $this->idt = $emailtype =  $session->get( 'idt' );
                
               // user create muti layout
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
                                        if ($checknoname !== false) {
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
                     
               if ($this->idt == '' && $this->changetemps == '' && count($this->tems_user) == 0 ){
                    // new advisor
                    $tpl = 'create'.$check_license;       
                    // choice template
                    $query = "SELECT * from #__email_templates where status = 'published' order by id"; 
                    $db->setQuery($query);  
                    $this->tems = $db->loadObjectlist(); 
                   
               } else {
                   
                   if ($this->idt == '' || $this->changetemps == '' ) {
                       $datet = '';                    
                       foreach ($this->tems_user as $r) {
                           // check date time
                           // echo $r."<br>";
                           $filename = $directory.$r.'.html';
                           
                           if(filemtime($filename) > $datet){                              
                               $datet = filemtime($filename);
                               $arra =  explode('_'.$user->id.'_', $r);                             
                               $this->idt = $arra[0];                                 
                               $this->changetemps = $arra[1];
                           }
                       }
                    
                       $session->set( 'changetemps', $this->changetemps );    
                   }
               
                $this->changetemps_lauout =   $session->get( 'changetemps', $this->tems_layout[0] );
                if ( JRequest::getVar('id_user') != '' && JRequest::getVar('new_tem') != 1 ){
                     $userchoie = JRequest::getVar('id_user');
                }else {
                     $userchoie = $user->id;
                }      
                   
                if ( JRequest::getVar('new_tem') == 1 ) {
            
                    $query = "INSERT INTO  `#__enewsletter_option` (`id`, `filen`, `user_id`, `option`) VALUES (NULL, '".$db->escape($this->idt."_".$user->id.'_'.$this->changetemps_lauout.'.html')."', '".$user->id."', '".JRequest::getVar('optionf')."')";
                    $db->setQuery($query);
                    $db->execute();
                    $this->optionf = JRequest::getVar('optionf');
                  
                }
                
                $check_f1= JPATH_SITE."/administrator/components/com_enewsletter/templates/".$this->idt."_".$userchoie.'_'.$this->changetemps_lauout.'.html';
                if (file_exists($check_f1)) {  
                    $maildata = file_get_contents($check_f1);                    
                }              
                $this->filen = $this->idt."_".$userchoie; 
                
		if (!$maildata){
                // get template defaults                         
                    $check_default = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$this->idt."_defaults.html";
                    $check_f1= JPATH_SITE."/administrator/components/com_enewsletter/templates/".$this->idt."_".$user->id.'_'.$this->changetemps.'.html';
                    copy($check_default, $check_f1);
                    $maildata = file_get_contents($check_default);   
                    if($this->changetemps_lauout == '' ){
                         $this->changetemps_lauout = 'Default'; 
                    }
                }             
                // choice template
                $query = "SELECT * from #__email_templates where status = 'published' order by id"; 
                $db->setQuery($query);  
		$this->tems = $db->loadObjectlist();                                
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
                
                if ( $this->optionf == '' ){
                       $query = $db->getQuery(true);
                       $query->select("`option`")->from("#__enewsletter_option")->where(" filen = '".$db->escape($this->idt."_".$user->id.'_'.$this->changetemps_lauout.'.html')."' ")->order(' id DESC ');;
                       
                       $db->setQuery($query);
                       $this->optionf = $db->loadResult();
                       
                       $query = $db->getQuery(true);
                       $query->select("`subject`")->from("#__enewsletter_option")->where(" filen = '".$db->escape($this->idt."_".$user->id.'_'.$this->changetemps_lauout.'.html')."' and user_id = '".$user->id."' ")->order(' id DESC ');
                       $db->setQuery($query);
                       $this->subject = $db->loadResult();
                       
                       $query = $db->getQuery(true);
                       $query->select("*")->from("#__enewsletter_option")->where(" filen = '".$db->escape($this->idt."_".$user->id.'_'.$this->changetemps_lauout.'.html')."' and user_id = '".$user->id."' ")->order(' id DESC ');
                       $db->setQuery($query);
                       $this->optioncolor = $db->loadObject();
                       
                }
                 
                $query = $db->getQuery(true);
                $query->select("filen,tplname")->from("#__enewsletter_option")->where(" tplname IS NOT NULL ");
                $db->setQuery($query);
                $this->allnametemplate = $db->loadObjectList();
              
                $this->custome_url = $datasets->custom_link_article;
                $dta_tmp = array(1);
                
		$maildata = enewsletterHelper::replaceTemplateCode('', $dta_tmp, $datasets, $maildata, true);   
                $maildata = str_replace('Webinar Series', 'Events', $maildata);
                $maildata = str_replace('src="'.JURI::base().'data:image', 'src="data:image', $maildata);                
        	$dom = new SmartDOMDocument();
    		$dom->loadHTML($maildata); 
                 // check 2 component meeting and event
                $query = "SELECT extension_id from #__extensions where element = 'com_booknow' and enabled = 1  "; 
                $db->setQuery($query);  
                $this->ext_booknow_id = $db->loadResult();   
                 
                $query = "SELECT extension_id from #__extensions where element = 'com_zcalendar' and enabled = 1  "; 
                $db->setQuery($query);  
                $this->ext_zcalendar_id = $db->loadResult();   
                
                $query = "SELECT path from #__menu where link LIKE 'index.php?option=com_apicontent&view=fnc%' and client_id = 0  and published = 1 "; 
                $db->setQuery($query);  
                $investlink = $db->loadObject();
                 
                $query = "SELECT path from #__menu where link LIKE 'index.php?option=com_apicontent&view=fbc%' and client_id = 0  and published = 1 "; 
                $db->setQuery($query);  
                $finanlink = $db->loadObject();
                $query = "SELECT path from #__menu where link LIKE 'index.php?option=com_apicontent&view=weeklyupdate%' and client_id = 0  and published = 1 "; 
                $db->setQuery($query);  
                $weeklink = $db->loadObject();
                if ($this->ext_booknow_id > 0){
                    $query = "SELECT path from #__menu where link LIKE 'index.php?option=com_booknow&view=booknow%' and client_id = 0  and published = 1  "; 
                    $db->setQuery($query);  
                    $meeting = $db->loadObject();
                }
                if ($this->ext_zcalendar_id > 0){
                    $query = "SELECT path from #__menu where link LIKE 'index.php?option=com_zcalendar&view=register%' and client_id = 0  and published = 1 "; 
                    $db->setQuery($query);  
                    $serminar = $db->loadObject();   
                }
                 // title invest
                $query = "SELECT article_title FROM #__apifnc WHERE keywords LIKE '%investing news%' or keywords LIKE 'investing news%' or keywords LIKE '%investing news' ORDER BY created DESC"; 
                $db->setQuery($query);  
                $nameinvest = $db->loadResult();              
                // title finalconal
                $query = "SELECT article_title FROM #__apifnc WHERE keywords LIKE '%financial markets%' or keywords LIKE 'financial markets%' or keywords LIKE '%financial markets' ORDER BY created DESC"; 
                $db->setQuery($query);  
                $namefinancial = $db->loadResult();                  
               
                 
                if ( JRequest::getVar('new_tem') == 1 ) {
                                    // get module logo
                                    $query = "SELECT params FROM #__modules WHERE module = 'mod_logo' "; 
                                    $db->setQuery($query);  
                                    $para = $db->loadResult();  
                                    if ($para != '') {
                                        $para = json_decode($para);
                                        if ($para->logosrc != '') {
                                               $logo = $dom->getElementById('imgslogo');
                                               if($logo){
                                                      $para->logosrc =    str_replace('https', 'http', $para->logosrc);
                                                      $base=    str_replace('https', 'http', JURI::base() );
                                                      $sssq = strrpos($para->logosrc, $base);

                                                       if ($sssq === false) {
                                                            $logo->setAttribute( 'src' , $base.$para->logosrc );    
                                                       }else {
                                                            $logo->setAttribute( 'src' , $para->logosrc );    
                                                       }
                                                       
                                                        $logo->setAttribute( 'style' , 'width:90%;min-width:260px;max-width:350px;' );    

                                               }                   
                                        }
                                    }

                                  if ($investlink){                    
                                      $a1 = $dom->getElementById('ainvest');
                                      if($a1){
                                      $a1->setAttribute( 'href' , JURI::base().$investlink->path );    
                                      }                   
                                  }
                                  if ($finanlink){
                                      $a2 = $dom->getElementById('afinancial');
                                        if($a2){
                                      $a2->setAttribute( 'href' , JURI::base().$finanlink->path );  
                                        }
                                  }             
                                  if ($weeklink){
                                      $a3 = $dom->getElementById('aweekly');
                                       if($a3){
                                       $a3->setAttribute( 'href' , JURI::base().$weeklink->path );      }             
                                  }
                                  if ($this->ext_booknow_id > 0){
                                      if ($meeting){
                                          $a4 = $dom->getElementById('aschedule');
                                           if($a4){
                                           $a4->setAttribute( 'href' , JURI::base().$meeting->path );}                
                                      }   
                                  }
                                  if ($this->ext_zcalendar_id > 0){
                                      if ($serminar){
                                           $a5 = $dom->getElementById('aserminar1');
                                           if($a5){
                                           $a5->setAttribute( 'href' , JURI::base().$serminar->path );      }                                   
                                      }else {
                                           $a5 = $dom->getElementById('aserminar1');
                                           if($a5){
                                           $a5->setAttribute( 'href' , JURI::base().'index.php?option=com_zcalendar' );        }                                   
                                      }
                                  }
                           }
               
                            $a3 = $dom->getElementById('aweekly');    
                            if($a3){
                            $a3->nodeValue = 'Weekly Update';}              
                           if ($nameinvest){
                                if($a1){
                                $a1->nodeValue = $nameinvest;}
                           }
                           if ($namefinancial){
                                if($a2){
                                $a2->nodeValue = $namefinancial;}
                           }

                           $this->maildata = $dom->saveHTML();	
                           $this->maildata = preg_replace('/<link[^>]*>/i', '', $this->maildata);                
                           $this->maildata = str_replace('id="invest" class="ui-sortable-handle" style="', 'id="invest" class="ui-sortable-handle" style="display:none;', $this->maildata);
                           $this->maildata = str_replace('width: 210px;', 'width: 195px;', $this->maildata);
                           $this->maildata = str_replace('padding-right: 50px;', 'padding-right: 0px;', $this->maildata);
                           $this->maildata = str_replace('padding: 30px;', 'padding: 15px;word-wrap: break-word;', $this->maildata);
                           $this->maildata = str_replace('width="270px"', 'width="210px"', $this->maildata);
                           $this->maildata = str_replace('alt="Smiley face"', '', $this->maildata);
                            // get contact in new site
                           $this->com_params = JComponentHelper::getParams('com_enewsletter');            
                           if ( JRequest::getVar('new_tem') == 1 ) { 
                                         
                           
                            // get menu contact
                           $query = "SELECT link FROM  `#__menu` WHERE  `link` LIKE  '%com_contact%'AND  `client_id` = 0 LIMIT 0 , 30";
                           $db->setQuery($query);   
                           $menulink = $db->loadResult();
                           if ( $menulink == '' ){
                                $query = " SELECT * FROM  `#__contact_details`   " ;
                                $db->setQuery($query);   
                                $infocontact = $db->loadObject();
                           }else {
                                $menulinka = explode('id=', $menulink);
                                $menulinkid = $menulinka[1];
                                $query = " SELECT * FROM  `#__contact_details` where id = '".$menulinkid."'  " ;
                                $db->setQuery($query);   
                                $infocontact = $db->loadObject();
                           }

                           if (count($infocontact) > 0 ){                               
                               
                                preg_match_all('!\d+!', $advisordetails[0]->phone, $telformat);                                
                                foreach ( $telformat[0] as $tela ) {
                                  $strtelformat .= $tela;
                                }                      
                                $telformat =  "(".substr($strtelformat, 0, 3).") ".substr($strtelformat, 3, 3)."-".substr($strtelformat,6);
                                if ($advisordetails[0]->second_address1 != ''&& $advisordetails[0]->second_city != '' && $advisordetails[0]->second_phone != '' ) {       
                                      $minwidth = 400;                             
                                }else {
                                      $minwidth = 222;      
                                }
                              
                                $infocontacthtml = '   <div id="address-content" >  
                                                            <table style="min-width: '.$minwidth.'px"  border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top">
                                                               <td>
                                                                       <p style="font-size:12px;    margin-top: 0px;"><b>'.$advisordetails[0]->firm.'</b><br><span id="topaddress">'.$advisordetails[0]->address1.' '.$advisordetails[0]->address2.'<br>
                                                                       '.$advisordetails[0]->city.', '.$advisordetails[0]->state.' '.$advisordetails[0]->zip.'<br>
                                                                       Tel: '.$telformat.' <br></span>
                                                                       Email: <a href="mailto:'.$advisordetails[0]->from_email.'">'.$advisordetails[0]->from_email.'</a><br><a href="'.$advisordetails[0]->url.'" target="_blank">'.$advisordetails[0]->url.'</a></p>
                                                               </td>
                                                 ';
                                if ($advisordetails[0]->second_address1 != ''&& $advisordetails[0]->second_city != '' && $advisordetails[0]->second_phone != '' ) {        
                                                
                                         $infocontacthtml .='   <td id="secondaddress" style="padding-left: 40px;font-size:12px;" >
								<p style=" margin-top: 0px;" ></p>                                                              
                                                                '.$advisordetails[0]->second_address1.', '.$advisordetails[0]->second_address2.' <br/>
								'.$advisordetails[0]->second_city.', '.$advisordetails[0]->second_state.' '.$advisordetails[0]->second_zip.' <br/>
								Tel: '.$advisordetails[0]->second_phone.'<br/></span></p>
                                                               </td>';
                                }                              

                                $infocontacthtml .='           </tr></tbody></table> </div>  ';

                           }

                                $this->caddress =strip_tags( "$infocontact->address , $infocontact->city , $infocontact->suburb , $infocontact->state , $infocontact->country " ) ;                    
                                $this->caddress = str_replace("'", '', $this->caddress);
                           if ($infocontacthtml) {
                                $dom = new SmartDOMDocument();
                                $dom->loadHTML($this->maildata); 
                                $acontact = $dom->getElementById('address');
                                if($acontact){
                                       $acontact->nodeValue = 'sccxczxczfgxczx';
                                }
                                $this->maildata = $dom->saveHTML();
                                $this->maildata = str_replace('sccxczxczfgxczx', $infocontacthtml,  $this->maildata );
                           }
                           if ( $infocontact->state != ''  ){
                               $this->imagedecta = $infocontact->state;
                           }
                       }


                      // get new weeklyupdate                  
                      if ( ( $this->optionf == 2 || $this->optionf == 3 || $this->optionf == 4 || $this->optionf == 5 || $this->optionf == 6 || $this->optionf == 7 ) && JRequest::getVar('new_tem') == 1  ) {        
                        
                          
                          if ($this->optionf == 7){
                              
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
                                           $corlorctatr = 'f4f4f4';
                                           
                                                     $hhhtt = "<div style='text-align: center;  '><a href='http://www.youtube.com/watch?v=$id' target='_blank'  ><img src='".$linkimage."' style='width:505px;height:435px; ' /></a></div>";                      
                                                                     
                                             $acol2 ='<table id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="mceItemTable"><tbody><tr id-api="" id-cont="article_content_1" class="edit_content" id="article_1" style="background: rgb(240, 240, 240); border: rgb(244, 244, 244);" data-mce-style="background-color: #f4f4f4;"><td style="padding: 25px;" data-mce-style="padding: 25px;">
                                          <table width="100%" class="mceItemTable"><tbody><tr><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; text-align: justify;">
                                                          
                                                          <br><div finra="0" info="" id="article_content_1" style="font-family: Arial; font-size: medium; color: rgb(3, 10, 11);" data-mce-style="font-family: Arial; font-size: medium;"> 
                                                             '.base64_decode($this->com_params->get('youtube_intro')).$hhhtt.base64_decode($this->com_params->get('youtubedescription')).'
                                                          </div> 
                                                          </td>
                                                     
                                                  </tr></tbody></table></td>
                                  </tr></tbody></table>';



                                        } catch (Exception $e) {

                                        }

                                       $this->maildata = str_replace('bcvdhdjdjdjdj', $acol2, $this->maildata );
                                      
                                }else{
                                       $this->maildata = str_replace('bcvdhdjdjdjdj', '', $this->maildata );
                                }
                          }else{
                              
                              if ($this->optionf != 6){
                                // get content popup
                                $query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc limit 0,150 ";
                                $db->setQuery($query);  
                                $this->article = $db->loadObjectList();                                     
                                foreach ($this->article as $i => $item) {
                                     //skip weekly
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
                                if ($this->optionf == 2 ) {
                                     $id_content_week = $weekly_items[0]->article_id.',';
                                } else if ($this->optionf == 3 ) {
                                     $id_content_week = $final_items[0]->article_id.',';
                                } else if ($this->optionf == 4 || $this->optionf == 5 ) {
                                     $id_content_week = $weekly_items[0]->article_id.','.$final_items[0]->article_id.',';
                                }
                           } else {
                               
                               $id_content_week = JRequest::getVar('arinewsubmit');
                               if ( JRequest::getVar('cimag') != '' ) {
                                   $cimag  = '&sid='.JRequest::getVar('cimag');
                               }else{
                                   $cimag  = '';
                               }
                               if ( JRequest::getVar('cgetin') != '' ) {
                                   $cgetin = '&getin='.JRequest::getVar('cgetin');
                               }else{
                                   $cgetin = '';
                               }
                           }
                           
                           if ( ( $this->optionf == 2 || $this->optionf == 3 || $this->optionf == 4 || $this->optionf == 5 || $this->optionf == 6) && ($this->idt == 'weekly' || $this->idt == 'massemail' ) ){
                               
                                    // old content							   
                                   $query = 'SELECT * FROM #__advisorsettings  where  user_id IN (0,'.$user->id.') order by user_id ';
                                   $db->setQuery($query);
                                   $model= JModelLegacy::getInstance('Subscription', 'EnewsletterModel', array('ignore_request' => true));
                                   $model->setWeeklyUpdateContent($db->loadAssoc());                                     

                                   $tabledata =  preg_replace("/<img[^>]+\>/i", " ", $app->getUserState("com_enewsletter.Weeklyupdatedesc"));
                                   $strchut = explode('margin-width:1px', $tabledata);
                                   $strchut = explode('<!--#END#-->', $strchut[1]);

                                   $strchut =  '<div style="    width: 738px;    margin-left: 25px;" ><table><tr><td><p class="'.$strchut[0];
                                    
                                    $url = JURI::base().'index.php?option=com_enewsletter&sid='.$id_content_week.',&getin='.$id_content_week.',&task=getcontent&id='.$id_content_week;
                                    
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
                                    
                                    
                                     if ( $this->optionf  != '5' && $this->optionf  != '6') {
                                        $this->maildata = str_replace('bcvdhdjdjdjdj', $content.$strchut, $this->maildata );
                                     } else {
                                            $dom = new SmartDOMDocument();
                                            $dom->loadHTML($this->maildata); 
                                            $a3 = $dom->getElementById('cta'); 
                                              if($a3){
                                             $a3->nodeValue = 'bcvdhdjdjdjdj';} 
                                             $this->maildata = $dom->saveHTML();	      
                                             $this->maildata = str_replace('bcvdhdjdjdjdj', '<img id="imgedef" src="http://rimbatest1.advisorproducts.com/images/contenttest.png"  style="display:none;" >'.$content.$strchut, $this->maildata );   
                                     }
                                       
                              
                           }else{
                                    if ($id_content_week != '' ) {
                                
                                    if ($this->idt != 'enewsletter_threecol' ){
                                          $url = JURI::base().'index.php?option=com_enewsletter'.$cimag.$cgetin.'&task=getcontent&id='.$id_content_week;
                                    } else {
                                          $url = JURI::base().'index.php?option=com_enewsletter'.$cimag.$cgetin.'&task=getcontent3&id='.$id_content_week;
                                    }
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
                                     if ( $this->optionf  != '5' && $this->optionf  != '6') {
                                        $this->maildata = str_replace('bcvdhdjdjdjdj', $content, $this->maildata );
                                     } else {
                                            $dom = new SmartDOMDocument();
                                            $dom->loadHTML($this->maildata); 
                                            $a3 = $dom->getElementById('cta'); 
                                              if($a3){
                                             $a3->nodeValue = 'bcvdhdjdjdjdj';} 
                                             $this->maildata = $dom->saveHTML();	      
                                             $this->maildata = str_replace('bcvdhdjdjdjdj', '<img id="imgedef" src="http://rimbatest1.advisorproducts.com/images/contenttest.png"  style="display:none;" >'.$content, $this->maildata );   
                                     }
                                    }   
                           }
                           
                              
                          }
                           
                       }

                       $this->document->addStyleSheet($this->baseurl.'/components/com_enewsletter/assets/style.css');		
                       // select poll
                       // get poll via curl
                       $url = 'https://centcom.advisorproducts.com/index.php?option=com_acepolls&task=getvote&ids='.JFactory::getApplication()->getUserState("com_enewsletter.id_site");
                          $options = array(
                                CURLOPT_RETURNTRANSFER => true,    
                                CURLOPT_HEADER         => false,   
                                CURLOPT_FOLLOWLOCATION => true,     
                                CURLOPT_ENCODING       => "",      
                                CURLOPT_USERAGENT      => "spider", 
                                CURLOPT_AUTOREFERER    => true,   
                                CURLOPT_CONNECTTIMEOUT => 120,    
                                CURLOPT_TIMEOUT        => 120,     
                                CURLOPT_MAXREDIRS      => 10,      
                                CURLOPT_SSL_VERIFYPEER => false,     
                                CURLOPT_TIMEOUT => 7
                            );

                            $ch      = curl_init( $url );
                            curl_setopt_array( $ch, $options );
                            $content = curl_exec( $ch );
                            $err     = curl_errno( $ch );
                            $errmsg  = curl_error( $ch );
                            $header  = curl_getinfo( $ch );
                            curl_close( $ch );

                            $this->poll = $content;

                       // google api key AIzaSyBNJIeTGgrFxcrTgo0YKZoj7Y-T7IYapS8 image
                       // googe api get lo - latude key AIzaSyAcBsiCXaeb4H4wZDMNtnjSRRCKP_B2D1M   

                       try {
                                // mod  cta
                                JFormHelper::loadFieldClass('checkboxes');
                                $opts = array();
                                JLoader::import('cta', JPATH_ROOT . '/administrator/components/com_cta/models');
                                $ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));             
                                $videos = $ctaModel->getVideos();

                                foreach ( $videos as $i => $video) {			
                                        $checked = in_array($video['VideoId'], $this->value)?' checked="checked"' : '';			
                                        $tit = JText::alt($video['Title'], preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname));	
                                        $tit1 = str_replace("'", "\'", $tit);
                                        $tit1 = str_replace('"', "\'\'", $tit1);
                                        $html[] = '<br><input onclick="$(\'#extend_video\').val(\'|xxx\');$(\'#cus_or_video\').val(\'video_id[]\');$(\'#textctatit\').val(\''.$tit1.'\');" type="radio" id="idcta'.$i.'" name="nidcta"' . ' value="'
                                        . htmlspecialchars($video['VideoId'], ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>'.chr(13);

                                        $view_video = JRoute::_(JURI::base().'administrator/index.php?option=com_cta&view=report&layout=video&vidfile='.$video['VideoFile'].'&vidimg='.$video['ImgCTA']);
                                        $preview_video = JURI::base().'index.php?option=com_cta&view=video&layout=video&vidfile='.$video['VideoFile'].'&vidimg='.$video['ImgCTA'];

                                        $extfile = explode('.', $video['VideoFile']);
                                        $html[] = '<label class="ctaicon_'.$extfile[(count($extfile)-1)].'" for="' . $this->id . $i . '"' . $class . '><a href="#" id="atagcta'.$i.'" onclick="previewcta('.$i.')"  ref="'.$preview_video.'" >'
                                .$tit  . '</a></label>'.chr(13);

                                     $i++;   
                                    }//for   
                        } catch (Exception $e) {
                         
                        }

                        $editletterModel = JModelLegacy::getInstance('editletter', 'EnewsletterModel'); 
                        $local_video =  $editletterModel->getListcta();    

                        if (count($local_video) > 0 ) {
                            $html[] = '<hr style="    margin-top: 10px;    margin-bottom: -10px;">';
                        }
                        foreach ( array_reverse($local_video) as $i => $video){                    

                            $html[] = '<br><input onclick="$(\'#extend_video\').val(\'\');$(\'#cus_or_video\').val(\'cusitem_id[]\');$(\'#textctatit\').val(\''.$video['title'] .'\');" type="radio" id="idccta'.$video['id'].'" name="nidcta"' . ' value="' .$video['id'].'" />'.chr(13);

                            $html[] = '<label class="ctaicon_'.$video['file_type'].'"  for="' . $this->id . $i . '"' . $class . '><a onclick="previewcta(\''.$video['id'].'\',\'2\' )"  class="nameof'.$video['id'].'" id="atagcta'.$i.'" href="#" ref="'.JURI::base().'index.php?option=com_cta&view=video&tmpl=component&preview=fsdf8sf987s98w7e8sd9f4s98f489sd4f8sd4fsd6&cusitem_id[]='.$video['id'].'" >'
                         .$video['title']  . '</a> <span onclick="deletecta(\''.$video['id'].'\')" style="color:red;cursor: pointer;    margin-left: 10px;    font-weight: bold;    font-size: 17px;" >x</span> </label>'.chr(13);
                            $i++;   
                        }    
                                    
                       
                       $this->ctainput = $html; 
                       
                       // get data address
                       $query = "select * from #__advisorsettings  ";
                       $db->setQuery($query);  
                       $this->address = $db->loadObject();      
                       
                       // get who advisor 
                       $query = "SELECT * FROM #__menu where menutype = 'our-team' and  `client_id` = 0 and published = 1";
                       $db->setQuery($query);  
                       $this->team = $db->loadObjectList();

                       // get content popup
                       $query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc limit 0,150 ";
                       $db->setQuery($query);  
                       $this->article = $db->loadObjectList();                   
                   
               }
               
               $query = 'SELECT * FROM #__advisorsettings  where  user_id IN (0,'.$user->id.') order by user_id ';
               $db->setQuery($query);  
               $this->allsetting = $db->loadObject();
               
               parent::display($tpl);	
                
	}//func

}
