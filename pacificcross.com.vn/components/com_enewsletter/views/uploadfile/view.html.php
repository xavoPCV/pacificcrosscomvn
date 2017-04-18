<?php
defined('_JEXEC') or die;




class EnewsletterViewUploadfile extends JViewLegacy {

	public function display($tpl = null) {
              
             $db = JFactory::getDbo();
                $app = JFactory::getApplication();
                $user  = JFactory::getUser();
                JRequest::setVar('tmpl', 'component');
                $app->setTemplate('system');                 
                $session =& JFactory::getSession();
		/*
                if ($user->id == '') {
                    $baseurl = JURI::base();
                    foreach (array('apsample1') as $testsite) {
                        $pos = strrpos($baseurl, $testsite);
                        if ($pos === false) {
                            $app->redirect('index.php?option=com_users&view=login');
                        }
                      
                    }//for                     
                }              
                
        */    
            $directory = JPATH_SITE."/images/files/" ;
                  if (!file_exists($directory)) {
                      mkdir($directory, 0777);                     
                  } 
                    if ($handle = opendir($directory))
                    {
                        while (false !== ($file = readdir($handle)))
                        {
                            if ($file != '.' && $file != '..')
                            {
                              
                                $file_array = explode('.', $file);
                                if (strtolower($file_array[count($file_array)-1]) == 'pdf' || strtolower($file_array[count($file_array)-1]) == 'doc' || strtolower($file_array[count($file_array)-1]) == 'docx' || strtolower($file_array[count($file_array)-1]) == 'ppt' || strtolower($file_array[count($file_array)-1]) == 'pptx' || strtolower($file_array[count($file_array)-1]) == 'xlsx'  || strtolower($file_array[count($file_array)-1]) == 'mp3'    ){        
                                        
                                       $this->file[] = $file;
                                }
                             }
                        }
                        closedir($handle);
                    }
                    
                    sort($this->file);
            parent::display($tpl);
		
		
	}//func

}
