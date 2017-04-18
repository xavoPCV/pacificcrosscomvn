<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Advisorsetting controller class.
 */
class EnewsletterControllerAdvisorsetting extends JControllerForm
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
	public function getModel($name = 'Advisorsetting', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}


	/**
	 * Used to save advisor setting details
	 * 
	 * @return void      
	 */
	public function save()
	{
		$app = JFactory::getApplication();	
        $post = JRequest::getVar('iduser' ,'post');
    	// Get all data of current page	
		$data = JRequest::getVar('jform', array(), 'post', 'array');
                
		#HT
		$data['iduser'] = (int)$post;
		
		$from_email = JRequest::getVar('verified_emails');
		// Get and set all verified email address
		if($data['email_campaign_key'] == 'M'){
			$data['from_email'] = trim($data['verified_email_name']).'@'.trim($from_email);
		}else{
			$data['from_email']   = trim($from_email);
		}
   
    
    
      //echo '<pre>';
    //print_r($data);exit;
    
    // Get Enewsletter model
		$bmodel = $this->getModel('Buildnewsletter');	
    
    if($data['auto_update']) {
        $lids = JRequest::getVar('lids', array(), 'post', 'array');
      } else{
         $lids = array();
      }
      $bmodel->setWeeklyUpdateGroup($lids);
		
		/* check and make relative path to absolute path of all images start */
		$dataarray = array('newsletter_disclosure','mass_email_disclosure','weekly_update_intro','weekly_update_newsletter','join_list_instruction','join_list_email','privacy_policy');		
		foreach($dataarray as $da){
			$dom=new SmartDOMDocument();
			$dom->loadHTML($data[$da]);
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
			
			$mock = new SmartDOMDocument();
			$body = $dom->getElementsByTagName('body')->item(0);
			foreach ($body->childNodes as $child){
				$mock->appendChild($mock->importNode($child, true));
			}//for		
			$data[$da] = $mock->saveHTML();		
		}//for
		/* check and make relative path to absolute path of all images end */
		
		
		
		$logo = $_FILES['logo'];
		if ( $logo['name'] && !$logo['error'] ) {
			$ext = substr(strrchr($logo['name'], '.'), 1);
			//$file_name = substr($logo['name'], 0, strrpos($logo['name'], '.'));
			$file_name = rand(9,9999).'_'.time().".$ext";
			if (in_array($ext, array('jpg','jpeg','gif','png'))) {
				$new_path = JPATH_ROOT.'/media/com_enewsletter/logo/'.$file_name;
				if (move_uploaded_file($logo['tmp_name'], $new_path)) {
					$data['logo'] = $file_name;
				}//if
			}//if
		}//if
		
		if ( is_array($data['social_links']) ) {
			$data['social_links'] = json_encode($data['social_links']);
		} else {
			$data['social_links'] = json_encode(array());
		}//if
		
		$delbanner = JRequest::getInt('delbanner', 0);
		$oldbanner = JRequest::getVar('oldbanner', NULL);
		if ($delbanner && $oldbanner) {
			@unlink(JPATH_ROOT.'/media/com_enewsletter/banner/'.$oldbanner);
			$data['bannerimg'] = '';
		}//if
		
		$bannerimg = $_FILES['bannerimg'];
		if ( $bannerimg['name'] && !$bannerimg['error'] ) {
			$ext = substr(strrchr($bannerimg['name'], '.'), 1);
			$file_name = rand(9,9999).'_'.time().".$ext";
			if (in_array($ext, array('jpg','jpeg','gif','png'))) {
			
				if (!is_dir(JPATH_ROOT.'/media/com_enewsletter/banner/')) {
					mkdir(JPATH_ROOT.'/media/com_enewsletter/banner/');
				}//if
			
				$new_path = JPATH_ROOT.'/media/com_enewsletter/banner/'.$file_name;
				if (move_uploaded_file($bannerimg['tmp_name'], $new_path)) {
					$data['bannerimg'] = $file_name;
					
					if($oldbanner) @unlink(JPATH_ROOT.'/media/com_enewsletter/banner/'.$oldbanner);
				}//if
			}//if
		}//if
		
		//echo '<hr/>';
		//print_r($data);
		//exit;
		
		$model = $this->getModel('Advisorsetting', '', array());	
		$model->saveall($data); 

		$this->setMessage(JText::_('Set Up Details Saved Successfully.'));
		$this->setRedirect(JRoute::_('index.php?option=com_enewsletter&view=advisorsetting',false));
	
	}
  
  /**
	 * Used to clear and remove API  details from database
	 * 
	 * @return void      
	 */
  public function clearapidetails(){
     $model = $this->getModel('Advisorsetting', '', array());	
		$model->cleardetails(); 
  }
	
	/**
	 * Performs cancel action
	 *
	 * @return void
	 */
	function cancel(){	
		$app = JFactory::getApplication();
    $this->setRedirect('index.php?option=com_enewsletter');
	}
}
