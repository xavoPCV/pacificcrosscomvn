<?php
defined('_JEXEC') or die;




class enewsletterViewhistory extends JViewLegacy {

	public function display($tpl = null) {
            $app = JFactory::getApplication();           
            $app->setTemplate('system');   		
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
          
		$query = "SELECT * FROM #__enewsletter_history order by dte_send DESC";
        	$db->setQuery($query);  
                $this->his = $db->loadObjectList();

	
               
		
               
                 parent::display($tpl);
		
		
	}//func

}
