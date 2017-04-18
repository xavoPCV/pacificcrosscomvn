<?php
defined('_JEXEC') or die;




class EnewsletterViewsendmail extends JViewLegacy {

	public function display($tpl = null) {
               $app = JFactory::getApplication();
//                $tmpls = JREQUEST::getVar('tmpl');
//                 if($tmpls == ''){
//                     $app->redirect('index.php?option=com_enewsletter&view=sendmail&tmpl=component');                     
//                 }
//              
                $app->setTemplate('system');   
//                
		
            $user  = JFactory::getUser();
                if ($user->id == '') {
                    $baseurl = JURI::base();
                    foreach (array('apsample1') as $testsite) {
                        $pos = strrpos($baseurl, $testsite);
                        if ($pos === false) {
                            $app->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JURI::base() . 'index.php?option=com_enewsletter&view=editletter'));
                        }//if
                    }//for                     
                }
               
            $db = JFactory::getDBO();
         
                
                 $sql = "select extension_id from #__extensions where type= 'module' and element = 'mod_leftmenuedit' and enabled = 1 ";
                $db->setQuery($sql);
                $ischeck = $db->loadResult();
                if($ischeck > 0 ){
                       $tpl = 'style2';                      
                }     
                
            $query = 'SELECT * FROM #__advisorsettings';
            $db->setQuery($query);  
            $advisordetails = $db->loadObjectList();
             $db = JFactory::getDBO();
		
		$query = "SELECT * FROM (SELECT *,'Featured News' as type FROM #__apifnc union SELECT *,'Financial Briefs' as type FROM #__apifbc) test order by test.created desc";
        	$db->setQuery($query);  
                $this->article = $db->loadObjectList();

	
               
		//$this->state		= $this->get('State');
		//$this->items		= $this->get('Articles');
		
		//echo count($this->items);	

		//$this->pagination	= $this->get('Pagination');
		//$this->groups = $this->get('Groups');
		
		// Check for errors.
//		if (count($errors = $this->get('Errors'))) {
//			JError::raiseError(500, implode("\n", $errors));
//			return false;
//		}
//		
//		// Include the component HTML helpers.
//		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
//		
//		#HT - Compliance
//		$this->doCompliance = file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
//
//
//		//$this->addToolbar();
//		
//		$app = JFactory::getApplication();
		$this->api = $advisordetails[0]->newsletter_api;
                $this->allsetting = $advisordetails;
//                $this->idt = $emailtype = JRequest::getVar('idt','newsletter');
//                $query = "SELECT * from #__email_templates where status = 'published' and type = '$emailtype' order by id"; 
//                $db->setQuery($query);  
//		$e = $db->loadObject();
                
                // check  template by user
            //    $filename = explode('.', $e->filename); 
                
                $query = "SELECT * from #__email_templates where status = 'published' order by id"; 
                $db->setQuery($query);  
		$template =  $db->loadObjectlist();
                
              
                 $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/" ;

                    if ($handle = opendir($directory))
                    {
                        while (false !== ($file = readdir($handle)))
                        {
                            if ($file != '.' && $file != '..')
                            {
                                //echo $file.'<br>';
                                $file_array = explode('.', $file);
                                if ($file_array[count($file_array)-1] == 'html'){
                                    $alfilefolder[] = $file;
                                }
                             }
                        }

                        closedir($handle);
                    }
                  
                     foreach ($template as $j){
                       $fna = explode('.',  $j->filename);
                            foreach ($alfilefolder as $r){
                                  $pos = strpos( $r , $fna[0]."_".$user->id.'_' );
                                  
                                   $checknoname = strpos( $r ,  $fna[0]."_".$user->id.'_.html' );
                                   if ($checknoname !== false) {
                                              continue;
                                   }
                                        
                                   if ($pos !== false) {
                                   
                                        $this->tems[]  = str_replace('.html', '', $r);
                                   } 
                            }
                     }
              
		if($this->api == 'C'){
			//$this->setLayout( 'constantcontactnewsletter' );
			
		}else if($this->api == 'M'){
			//$this->setLayout( 'mailchimpnewsletter' );
			
		}else{
		//	$app->enqueueMessage(JText::_('You are not allowed to build newsletter. First Fill Setup details.'),'warning');
      	//	$app->redirect('administrator/index.php?option=com_enewsletter&view=advisorsetting');
			
		}
                 // choice template
               
            parent::display($tpl);
		
		
	}//func

}
