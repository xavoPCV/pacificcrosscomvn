<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Adddtemplate controller class.
 */
class EnewsletterControllerAddtemplate extends JControllerForm
{

  /**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 */
	public function getModel($name = 'Managetemplate', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**  
	 * Used to Save/Update template 
	 * @return void
	 */
	public function save()
	{
		$app = JFactory::getApplication();
    
    
    // Get and set all data of add template form		
		$data = JRequest::getVar('jform', array(), 'post', 'array');
    
    // Make relative path to absolute of images included in template editor field start
    $dom=new SmartDOMDocument();
		$dom->loadHTML($data['content']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
		
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
				
			}
		}	    
    $data['content'] = $dom->saveHTML();
    // Make relative path to absolute of images included in template editor field end	
    
    // Check for invalid placeholders of a type (enewsletter/weekly update/mas email) start
    $wrongplacearray = array();
    $placeholderarray = array();
    if($data['type'] == 'newsletter'){
      $placeholderarray = array('{$articles}','{$massemailcontent}');
      foreach($placeholderarray as $p){
        if (strpos($data['content'],$p) !== false) {
           $wrongplacearray[] = $p;
        }
      }
    }else if($data['type'] == 'weeklyupdate'){
      $placeholderarray = array('{$articletitle1}', '{$articlebody1}', '{$articleimage1}', '{$articlelink1}',  
                                '{$articletitle2}', '{$articlebody2}', '{$articleimage2}', '{$articlelink2}',  
                                '{$articletitle3}', '{$articlebody3}', '{$articleimage3}', '{$articlelink3}', 
                                '{$articletitle4}', '{$articlebody4}', '{$articleimage4}', '{$articlelink4}',   
                                '{$articletitle5}', '{$articlebody5}', '{$articleimage5}', '{$articlelink5}','{$massemailcontent}','{$trailer}');
      foreach($placeholderarray as $p){
        if (strpos($data['content'],$p) !== false) {
           $wrongplacearray[] = $p;
        }
      }
    }else if($data['type'] == 'massemail'){
      $placeholderarray = array('{$articletitle1}', '{$articlebody1}', '{$articleimage1}', '{$articlelink1}',  
                                '{$articletitle2}', '{$articlebody2}', '{$articleimage2}', '{$articlelink2}',  
                                '{$articletitle3}', '{$articlebody3}', '{$articleimage3}', '{$articlelink3}', 
                                '{$articletitle4}', '{$articlebody4}', '{$articleimage4}', '{$articlelink4}',   
                                '{$articletitle5}', '{$articlebody5}', '{$articleimage5}', '{$articlelink5}',
                                '{$intro}', '{$articles}','{$trailer}');
      foreach($placeholderarray as $p){
        if (strpos($data['content'],$p) !== false) {
           $wrongplacearray[] = $p;
        }
      }
    }
    // Check for invalid placeholders of a type (enewsletter/weekly update/mas email) end

    
    // Get managetemplate model to save/update/get template
		$model = $this->getModel();
    
    // Set form data in session state
    $app->setUserState("com_enewsletter.data",$data);
    
    // Check for wrong placeholder. If all data is correct then it will save/update/get template and create template file otherwise redirect 
    // to template page with warning  of invalid place holders
    if(!empty($wrongplacearray)){
        $app->setUserState("com_enewsletter.loaddata",'no');
        $this->setMessage(JText::_('Invalid placeholders : '.implode(', ',$wrongplacearray)),'error');
    }else{
    
        // Check for template add/update 
        // If id of template is not found then it will save template and create new template file 
        // otherwise update existing template data and update content of existing template file
        if($data['id'] != ''){
            $model->editTemplate($data);
            $file = $data['filename']; 
            $filename = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$file;
            $fp = fopen($filename,"wb");
            fwrite($fp,$data['content']);
            fclose($fp);
            $app->setUserState("com_enewsletter.loaddata",'yes');
            $app->setUserState("com_enewsletter.data",$data);
            $this->setMessage(JText::_('Template updated successfully.'));
        } else{
            $returnid = $model->saveTemplate($data);
            $file = $data['type'].'_'.$returnid.'.html'; 
            $filename = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$file;
            $fp = fopen($filename,"wb");
            fwrite($fp,$data['content']);
            fclose($fp);
            if (file_exists($filename)) {
                   $id = $model->updateTemplate($returnid,$file);
            }else{
                $file = 'Not available';
                $id = $model->updateTemplate($returnid,$file);
            }
            $amodel = $this->getModel('addtemplate');
            $amodel->getTemplate($returnid);
            $data = $app->getUserState("com_enewsletter.data");
            $this->setMessage(JText::_('Template saved successfully.'));
        }               
    }
    
   
     if($data['id'] != ''){
		    $this->setRedirect(JRoute::_('index.php?option=com_enewsletter&view=addtemplate&id='.$data['id'],false));
      }else{
        $this->setRedirect(JRoute::_('index.php?option=com_enewsletter&view=addtemplate',false));
      }
	
	}
  
  /**  
	 * Used to preview template 
	 * @return void
	 */
  public function preview()
	{
		$app = JFactory::getApplication();
    
    // Get and set all data of add template form		
		$data = JRequest::getVar('jform', array(), 'post', 'array');
    
    // Make relative path to absolute of images included in template editor field start
    $dom=new SmartDOMDocument();
		$dom->loadHTML($data['content']);
		$imgs = $dom->getElementsByTagName("img");
		foreach($imgs as $img){
			$src = $img->getAttribute('src');
			if (strpos($src,'http') !== false) {
		
			}else{
				$baseurl = JURI::base();
				$baseurl = str_replace("administrator/", "", $baseurl);
				$img->setAttribute( 'src' , $baseurl.$src );
				
			}
		}	    
    $data['content'] = $dom->saveHTML();     
    // Make relative path to absolute of images included in template editor field start

    
    // Check for invalid placeholders of a type (enewsletter/weekly update/mas email) start
    $wrongplacearray = array();
    $placeholderarray = array();
    if($data['type'] == 'newsletter'){
      $placeholderarray = array('{$articles}','{$massemailcontent}');
      foreach($placeholderarray as $p){
        if (strpos($data['content'],$p) !== false) {
           $wrongplacearray[] = $p;
        }
      }
    }else if($data['type'] == 'weeklyupdate'){
      $placeholderarray = array('{$articletitle1}', '{$articlebody1}', '{$articleimage1}', '{$articlelink1}',  
                                '{$articletitle2}', '{$articlebody2}', '{$articleimage2}', '{$articlelink2}',  
                                '{$articletitle3}', '{$articlebody3}', '{$articleimage3}', '{$articlelink3}', 
                                '{$articletitle4}', '{$articlebody4}', '{$articleimage4}', '{$articlelink4}',   
                                '{$articletitle5}', '{$articlebody5}', '{$articleimage5}', '{$articlelink5}','{$massemailcontent}','{$trailer}');
      foreach($placeholderarray as $p){
        if (strpos($data['content'],$p) !== false) {
           $wrongplacearray[] = $p;
        }
      }
    }else if($data['type'] == 'massemail'){
      $placeholderarray = array('{$articletitle1}', '{$articlebody1}', '{$articleimage1}', '{$articlelink1}',  
                                '{$articletitle2}', '{$articlebody2}', '{$articleimage2}', '{$articlelink2}',  
                                '{$articletitle3}', '{$articlebody3}', '{$articleimage3}', '{$articlelink3}', 
                                '{$articletitle4}', '{$articlebody4}', '{$articleimage4}', '{$articlelink4}',   
                                '{$articletitle5}', '{$articlebody5}', '{$articleimage5}', '{$articlelink5}',
                                '{$intro}', '{$articles}','{$trailer}');
      foreach($placeholderarray as $p){
        if (strpos($data['content'],$p) !== false) {
           $wrongplacearray[] = $p;
        }
      }
    }
    // Check for invalid placeholders of a type (enewsletter/weekly update/mas email) end
    
    // Check for wrong placeholder. If all data is correct then it will preview template otherwise redirect 
    // to template page with warning of invalid place holders
    if(!empty($wrongplacearray)){
        $this->setMessage(JText::_('Invalid placeholders : '.implode(', ',$wrongplacearray)),'error');
    }else{
        $data['task'] =  JRequest::getVar('task');
    } 
    
    // Set form data in session state
    $app->setUserState("com_enewsletter.loaddata",'no');
    $app->setUserState("com_enewsletter.data",$data);
    
    if($data['id'] != ''){
        $this->setRedirect(JRoute::_('index.php?option=com_enewsletter&view=addtemplate&id='.$data['id'],false));
    }else{
        $this->setRedirect(JRoute::_('index.php?option=com_enewsletter&view=addtemplate',false));
    }
		  
	
	}
	
	/**  
	 * Used to cancel and redirect to main page
	 * @return void
	 */
	function cancel(){	
		$app = JFactory::getApplication();
    $this->setRedirect('index.php?option=com_enewsletter&view=managetemplate');
	}
}
