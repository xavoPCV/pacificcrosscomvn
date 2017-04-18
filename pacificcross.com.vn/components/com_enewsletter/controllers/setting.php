<?php
// No direct access.
defined('_JEXEC') or die;

// Include XML2Array library to convert xml result into array
require_once JPATH_SITE.'/administrator/components/com_enewsletter/XML2Array.php';

jimport('joomla.application.component.controller');

// Include mailchimp/constant contact library files
require(JPATH_SITE.'/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Services\ListService;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\MessageFooter;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;


class EnewsletterControllerSetting extends JController {

	var $max_size_upload = 10485760; // 10MB in bytes
	var $img_exts = array('gif', 'jpg', 'jpeg', 'png');

	function savecropctaimage() {
		$result = array('status' => 0, 'msg' => '');
		
		define('PHOTOS_DIR', JPATH_ROOT . "/media/com_enewsletter/upload/");
		if (!is_dir(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
		
		$img = JRequest::getVar( 'imgcode', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$filename = JRequest::getVar( 'filename', NULL );
		
		if ( $img && $filename ) {
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			
			$data = base64_decode($img); 
			
			$output_file = PHOTOS_DIR.$filename;
			
			$success = file_put_contents($output_file, $data);
			
			$result['status'] = $success;
			
			$result['filename'] = "media/com_enewsletter/upload/".$filename.'?rand='.time();
			
		}//if
		
		
		echo json_encode($result);
		exit;
	}
	
	
	function saveuploadctaimage() {
		
		$result = array('status' => 0, 'msg' => '');
		
		define('PHOTOS_DIR', JPATH_ROOT . "/media/com_enewsletter/upload/");
		
		if (!is_dir(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
		
		$photo = $_FILES['uploadctaimage'];
		
		
		
		$filename = NULL;
		if ($photo['name'] && !$photo['error']) {
			//Checking Extension of File
			$info = pathinfo($photo['name']);
			$extension = strtolower($info['extension']);
			$valid_extension = in_array($extension, $this->img_exts);
	
			if ( $valid_extension && $photo['size'] <= $this->max_size_upload) {
				$filenamerand = $info['filename'] . time() . rand(1, 100);
				if (move_uploaded_file($photo['tmp_name'], PHOTOS_DIR . $filenamerand . "." . $extension)) {
					$filename = $filenamerand . "." . $extension;
					$filesize = $photo['size'];
					
					
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$params = JComponentHelper::getParams('com_enewsletter');
					$params->set('uploadctaimage', $filename);
					
					$str_paa = $params->toString();
		
					$query->update('#__extensions')->set('`params` = '.$db->quote($str_paa))->where('`element` = "com_enewsletter"');
					$db->setQuery($query);
					$db->execute();
					
					$result['msg'] = 'Success!';
					
					$result['status'] = 1;
					$result['filenameonly'] = $filename;
					$result['filename'] = 'media/com_enewsletter/upload/'.$filename;
				} else {
					
					$result['msg'] = 'Move uploaded file error!';	
				
				}//if
			} else {
				$result['msg'] = 'Invalid extension or over max upload size!';
			}//if
		} else if ($photo['name'] && $photo['error']) {
			$result['msg'] = $photo['error'];
		}//if photo
		
		echo json_encode($result);
		exit;
	}
	
	function savecroplogo() {
		$result = array('status' => 0, 'msg' => '');
		
		define('PHOTOS_DIR', JPATH_ROOT . "/media/com_enewsletter/logo/");
		
		$img = JRequest::getVar( 'imgcode', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$filename = JRequest::getVar( 'filename', NULL );
		
		if ( $img && $filename ) {
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			
			$data = base64_decode($img); 
			
			$output_file = PHOTOS_DIR.$filename;
			
			$success = file_put_contents($output_file, $data);
			
			$result['status'] = $success;
			
			$result['filename'] = "media/com_enewsletter/logo/".$filename.'?rand='.time();
			
		}//if
		
		
		echo json_encode($result);
		exit;
	}

	function saveuploadlogo() {
		
		$result = array('status' => 0, 'msg' => '');
		
		define('PHOTOS_DIR', JPATH_ROOT . "/media/com_enewsletter/logo/");
		
		
		$photo = $_FILES['uploadlogo'];
		
		
		
		
		$filename = NULL;
		if ($photo['name'] && !$photo['error']) {
			//Checking Extension of File
			$info = pathinfo($photo['name']);
			$extension = strtolower($info['extension']);
			$valid_extension = in_array($extension, $this->img_exts);
	
			if ( $valid_extension && $photo['size'] <= $this->max_size_upload) {
				$filenamerand = $info['filename'] . time() . rand(1, 100);
				if (move_uploaded_file($photo['tmp_name'], PHOTOS_DIR . $filenamerand . "." . $extension)) {
					$filename = $filenamerand . "." . $extension;
					$filesize = $photo['size'];
					
					
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					
					
					$user  = JFactory::getUser();
					 $query = 'SELECT count(*) FROM #__advisorsettings  where  user_id IN ('.$user->id.')';
					 $db->setQuery($query); 
					 $found = $db->loadResult(); 
					 
					 
					$user_id = $found?$user->id:0;
					
					$query = $db->getQuery(true);
					
					$query->clear()->update('#__advisorsettings')
						->set('`logo` = '.$db->quote($filename))
						->where('`user_id` = '.$user_id);
					$db->setQuery($query);
					$db->execute();
					
					$result['msg'] = 'Success!';
					$result['status'] = 1;
					
					$result['filenameonly'] = $filename;
					$result['filename'] = 'media/com_enewsletter/logo/'.$filename;
				} else {
					
					$result['msg'] = 'Move uploaded file error!';	
				
				}//if
			} else {
				$result['msg'] = 'Invalid extension or over max upload size!';
			}//if
		} else if ($photo['name'] && $photo['error']) {
			$result['msg'] = $photo['error'];
		}//if photo
	
		
		
		
		 echo json_encode($result);
		 exit;
		
	}//func
	
	
	function savecropadvisor() {
		
		$result = array('status' => 0, 'msg' => '');
		
		define('PHOTOS_DIR', JPATH_ROOT . "/media/com_enewsletter/images/");
		if (!is_dir(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
		
		$img = JRequest::getVar( 'imgcode', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$filename = JRequest::getVar( 'filename', NULL );
		
		if ( $img && $filename ) {
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			
			$data = base64_decode($img); 
			
			$output_file = PHOTOS_DIR.$filename;
			
			$success = file_put_contents($output_file, $data);
			
			$result['status'] = $success;
			
			$result['filename'] = "media/com_enewsletter/images/".$filename.'?rand='.time();
			
		}//if
		
		
		echo json_encode($result);
		exit;
	}


	function saveuploadadvisor() {
		
		$result = array('status' => 0, 'msg' => '');
		
		define('PHOTOS_DIR', JPATH_ROOT . "/media/com_enewsletter/images/");
		if (!is_dir(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
		
		$photo = $_FILES['uploadadvisor'];
		
		$filename = NULL;
		if ($photo['name'] && !$photo['error']) {
			//Checking Extension of File
			$info = pathinfo($photo['name']);
			$extension = strtolower($info['extension']);
			$valid_extension = in_array($extension, $this->img_exts);
	
			if ( $valid_extension && $photo['size'] <= $this->max_size_upload) {
				$filenamerand = $info['filename'] . time() . rand(1, 100);
				if (move_uploaded_file($photo['tmp_name'], PHOTOS_DIR . $filenamerand . "." . $extension)) {
					$filename = $filenamerand . "." . $extension;
					$filesize = $photo['size'];
					
					
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$params = JComponentHelper::getParams('com_enewsletter');
					$params->set('uploadadvisor', $filename);
					
					$str_paa = $params->toString();
		
					$query->update('#__extensions')->set('`params` = '.$db->quote($str_paa))->where('`element` = "com_enewsletter"');
					$db->setQuery($query);
					$db->execute();
					
					$result['filename'] = "media/com_enewsletter/images/".$filename;
					$result['filenameonly'] = $filename;
					
					
					$result['msg'] = 'Success!';
					
					$result['status'] = 1;
				} else {
					
					$result['msg'] = 'Move uploaded file error!';	
				
				}//if
			} else {
				$result['msg'] = 'Invalid extension or over max upload size!';
			}//if
		} else if ($photo['name'] && $photo['error']) {
			$result['msg'] = $photo['error'];
		}//if photo
	
		
		
		
		 echo json_encode($result);
		 exit;
		
	}//func

	
	function savewidgets() {
		 $db = JFactory::getDbo();
		 $query = $db->getQuery(true);
		 
		 $params = JComponentHelper::getParams('com_enewsletter');
		
		 $result = array('status' => 0, 'msg' => '');
		 
		 $backgc = JRequest::getVar('backgc');
		 $params->set('backgc', $backgc);
		 
		 $maintextgc = JRequest::getVar('maintextgc');
		 $params->set('maintextgc', $maintextgc);
		 
		 $backbargc = JRequest::getVar('backbargc');
		 $params->set('backbargc', $backbargc);
		 
		 $linktextgc = JRequest::getVar('linktextgc');
		 $params->set('linktextgc', $linktextgc);
		 
		 
		 $poll = JRequest::getInt('poll');
		 $params->set('poll', $poll);
		 
		 $ctavideo = JRequest::getInt('ctavideo');
		 $params->set('ctavideo', $ctavideo);
		 
		 $ctacustom = JRequest::getInt('ctacustom');
		 if ($ctavideo) $ctacustom = 0;
		 
		 $params->set('ctacustom', $ctacustom);
		 
		 
		 $useaddress2 = JRequest::getInt('useaddress2');
		 $params->set('useaddress2', $useaddress2); 
		 
		 $temintro = JRequest::getVar('temintro-edit-text');
		 $params->set('temintro', $temintro);
		 
		 $mapaddress = JRequest::getVar('map-edit-img');
		 $params->set('mapaddress', $mapaddress);
		  
		 $mapzoom = JRequest::getInt('mapzoom');
		 $params->set('mapzoom', $mapzoom);
		 
		 
		 $textctatit = JRequest::getVar('textctatit');
		 $params->set('textctatit', $textctatit);
		 
		 $cobactatit = JRequest::getVar('cobactatit');
		 $params->set('cobactatit', $cobactatit);
		 $cotectatit = JRequest::getVar('cotectatit');
		 $params->set('cotectatit', $cotectatit);
		 $btcobactatit = JRequest::getVar('btcobactatit');
		 $params->set('btcobactatit', $btcobactatit);
		 $btcotectatit = JRequest::getVar('btcotectatit');
		 $params->set('btcotectatit', $btcotectatit);
		 
		 $textbutonctatit = JRequest::getVar('textbutonctatit');
		 $params->set('textbutonctatit', $textbutonctatit);
		 
		 
		 
		 $cloudcheck = JRequest::getVar('cloudcheck', array(), 'post', 'array');
		 $params->set('cloudcheck', $cloudcheck);
                 
                 
                 
                 if (JRequest::getVar('advisorstatus')  == 1 ){
                      $params->set('advisorstatus', 1);
                 }else{
                      $params->set('advisorstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('pollstatus')  == 1 ){
                      $params->set('pollstatus', 1);
                 }else{
                      $params->set('pollstatus', -1);
                 }
                 
                
                 if (JRequest::getVar('logostatus')  == 1 ){
                      $params->set('logostatus', 1);
                 }else{
                      $params->set('logostatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('socialstatus')  == 1 ){
                      $params->set('socialstatus', 1);
                 }else{
                      $params->set('socialstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('ctastatus')  == 1 ){
                      $params->set('ctastatus', 1);
                 }else{
                      $params->set('ctastatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('addressstatus')  == 1 ){
                      $params->set('addressstatus', 1);
                 }else{
                      $params->set('addressstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('introstatus')  == 1 ){
                      $params->set('introstatus', 1);
                 }else{
                      $params->set('introstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('mapstatus')  == 1 ){
                      $params->set('mapstatus', 1);
                 }else{
                      $params->set('mapstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('tagstatus')  == 1 ){
                      $params->set('tagstatus', 1);
                 }else{
                      $params->set('tagstatus', -1);
                 }
                 
                 if (JRequest::getVar('meetingstatus')  == 1 ){
                      $params->set('meetingstatus', 1);
                 }else{
                      $params->set('meetingstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('eventstatus')  == 1 ){
                      $params->set('eventstatus', 1);
                 }else{
                      $params->set('eventstatus', -1);
                 }
                 
                 
                 if (JRequest::getVar('weeklystatus')  == 1 ){
                      $params->set('weeklystatus', 1);
                 }else{
                      $params->set('weeklystatus', -1);
                 }
               
                 
		
		
		
		$str_paa = $params->toString();
		
		$query->update('#__extensions')->set('`params` = '.$db->quote($str_paa))->where('`element` = "com_enewsletter"');
		$db->setQuery($query);
		$db->execute();
		
		//update advisorsettings
		
		 $firm = JRequest::getVar('firm');
		 $from_email = JRequest::getVar('from_email');
		 $url = JRequest::getVar('url');
		 $address_address1 = JRequest::getVar('address_address1');
		 $address_address2 = JRequest::getVar('address_address2');
		 $address_phone = JRequest::getVar('address_phone');
		 $address_city = JRequest::getVar('address_city');
		 $address_state = JRequest::getVar('address_state');
		 $address_zip = JRequest::getVar('address_zip');
		 
		 $address2_address1 = JRequest::getVar('address2_address1');
		 $address2_address2 = JRequest::getVar('address2_address2');
		 $address2_phone = JRequest::getVar('address2_phone');
		 $address2_city = JRequest::getVar('address2_city');
		 $address2_state = JRequest::getVar('address2_state');
		 $address2_zip = JRequest::getVar('address2_zip');
		 
		 $rss = JRequest::getVar('rss');
		 $linkedin = JRequest::getVar('linkedin');
		 $facebook = JRequest::getVar('facebook');
		 $twitter = JRequest::getVar('twitter');
		 $googleplus = JRequest::getVar('googleplus');
		 
		 
		 $social_links = new stdClass;
		 $social_links->facebook = $facebook;
		 $social_links->twitter = $twitter;
		 $social_links->linkedin = $linkedin;
		 $social_links->googleplus = $googleplus;
		 $social_links->rss = $rss;
		 
		 
		 
		 $user  = JFactory::getUser();
		 $query = 'SELECT count(*) FROM #__advisorsettings  where  user_id IN ('.$user->id.')';
         $db->setQuery($query); 
		 $found = $db->loadResult(); 
		 
		 
		$user_id = $found?$user->id:0;
		
		$query = $db->getQuery(true);
		
		$query->clear()->update('#__advisorsettings')
			->set('`firm` = '.$db->quote($firm))
			->set('`from_email` = '.$db->quote($from_email))
			->set('`url` = '.$db->quote($url))
			->set('`address1` = '.$db->quote($address_address1))
			->set('`address2` = '.$db->quote($address_address2))
			->set('`phone` = '.$db->quote($address_phone))
			->set('`city` = '.$db->quote($address_city))
			->set('`state` = '.$db->quote($address_state))
			->set('`zip` = '.$db->quote($address_zip))
			
			->set('`second_address1` = '.$db->quote($address2_address1))
			->set('`second_address2` = '.$db->quote($address2_address2))
			->set('`second_phone` = '.$db->quote($address2_phone))
			->set('`second_city` = '.$db->quote($address2_city))
			->set('`second_state` = '.$db->quote($address2_state))
			->set('`second_zip` = '.$db->quote($address2_zip))
			
			->set('`social_links` = '.$db->quote(json_encode($social_links)))
			
			->where('`user_id` = '.$user_id);
			
			
		$db->setQuery($query);
		$db->execute();
		
		
		$result['status'] = 1;
		$result['msg'] = 'Success!';
		
		//sleep(2);
		
		//$result['debug'] = $str_paa;
		 
		 echo json_encode($result);
		 exit;
		
	}//func
	
    public function savetemplate() {
          $db = JFactory::getDbo();
          $user = JFactory::getUser();
          $post = JRequest::get('post');
          //print_r($post);
           $query = $db->getQuery(true);
           if ($post['templateskk'] == ''){
                $query->update('#__advisorsettings')->set(" template_weekly = '' " )->where(' id = 1 ');
           } else {
                $query->update('#__advisorsettings')->set(" template_weekly = '".$post['templateskk'].".html' " )->where(' id = 1 ');
           }
           $db->setQuery($query);
           $db->query();
        //die;
          $this->setRedirect('index.php?option=com_enewsletter&view=setting');
              
        
    }
  
    public function social(){
            
        
            $db = JFactory::getDbo();
            $user = JFactory::getUser();
            $post = JRequest::get('post');
         
          
           $query = $db->getQuery(true);
           
         $a = array();
         $a->facebook = $post['facebook'];
         $a->twitter = $post['twitter'];
         $a->linkedin = $post['linkedin'];
         
           $query->update('#__advisorsettings')->set(" social_links = '".json_encode($a)."' " )->where(' id = 1 ');
           $db->setQuery($query);
           $db->query();
           $this->setRedirect('index.php?option=com_enewsletter&view=setting');
         
        
        
        
    }
    
    
    public function weekly(){
            
        
            $db = JFactory::getDbo();
            $user = JFactory::getUser();
            $post = JRequest::get('post');
       //  print_r($_POST);die;
          
        
              $query = "TRUNCATE #__weeklyupdate_group";
              $db->setQuery($query);
              $db->query();
           foreach ( $_POST['gid'] as $r ) {
              $query = "INSERT INTO `#__weeklyupdate_group` (`id`, `user_id`, `api_type`, `group_id`) VALUES (NULL, '".$user->id."', 'C', '".$r."');";
              $db->setQuery($query);
              $db->query();
           }
           
       
                        $autoweeklysend = $_POST['autoweeklysend'];
                        $semiautoemail = JRequest::getVar('semiautoemail', NULL);
                        $com_params = JComponentHelper::getParams('com_enewsletter');
			$com_params->set('autoweeklysend', intval($autoweeklysend));
			$semiautoemail = filter_var($semiautoemail, FILTER_VALIDATE_EMAIL)?trim($semiautoemail):'';
			$com_params->set('semiautoemail', $semiautoemail);                        
                        $com_params->set('contetnfull', $_POST['contetnfull'] );
                        
                        if ( $_POST['typecontentchoice'] == 'tab4'){
                                $com_params->set('typecontentchoice', 'tab4' );
                        }else {
                                $com_params->set('typecontentchoice', 'tab2' );
                        }
                                            
                        $com_params->set('inputyoutube', $_POST['inputyoutube'] );                        
                        $com_params->set('contetnt_resouce', $_POST['contetnt_resouce'] );                        
                        $com_params->set('youtubedescription', base64_encode($_POST['youtubedescription']) );
                        
                        $com_params->set('youtube_intro', base64_encode('<strong id="youtube_tit" style="font-size: 20px;    display: block;    margin-bottom: 5px;" data-mce-style="font-size: 20px;">'.$_POST['youtubeintro_tit'].'</strong>'.$_POST['youtubeintro']) );
                       
                        $com_params->set('templatechioce_plan', $_POST['templatechioce_plan'] );
                        $com_params->set('day_send', implode(',', $_POST['day_send']) );
                        $com_params->set('plan_semiautoemail',  $_POST['plan_semiautoemail'] );
                        $com_params->set('planing_option', $_POST['planing_option'] );
                        $com_params->set('plan_subject', $_POST['plan_subject'] );
                        $com_params->set('hour_send', $_POST['hour_send'] );
                        $com_params->set('mu_send', $_POST['mu_send'] );
                        $com_params->set('ti_send', $_POST['ti_send'] );
                        
			$com_params->set('youtube_subject', $_POST['youtube_subject'] );
                        $com_params->set('youtube_mail', $_POST['youtube_mail'] );
                        $com_params->set('youtube_semiautoemail', $_POST['youtube_semiautoemail'] );
                         
			$data = $com_params->toString();
			
			$query = $db->getQuery(true);
			$query->update('#__extensions');
			$query->set('`params` = '.$db->quote($data));
			$query->where('`element` = "com_enewsletter"');
			$db->setQuery($query);
			$db->execute();
                        
                        
	   if ( $_POST['autoweeklysend'] != 'N' ){
                    
                    $aaa = ", auto_update = 'Y' ";		
           }else{
                    $aaa = ", auto_update = 'N' ";
           }
         
           if ( trim($_POST['introduction_tit']) != ''){
            
             $introduction_tit = '<strong id="intro_tit" style="font-size: 20px;    display: block;    margin-bottom: 5px;" data-mce-style="font-size: 20px;">'.$_POST['introduction_tit'].'</strong>';
           }else{
               $introduction_tit = '';
           }
       
           $query = $db->getQuery(true);
           $query->update('#__advisorsettings')->set(" from_name = '".$db->escape($_POST['from_name'])."' , update_subject = '".$db->escape($_POST['loginname'])."' , weekly_update_newsletter = '".$db->escape($_POST['bott'])."' , weekly_update_intro = '".$db->escape($introduction_tit.$_POST['topp'])."'  $aaa " )->where(' id = 1 ');
           $db->setQuery($query);
           $db->query();
           
           $this->setRedirect('index.php?option=com_enewsletter&view=setting');
         
        
        
        
    }

    public function saveaddress(){
            $db = JFactory::getDbo();
            $user = JFactory::getUser();
            $post = JRequest::get('post');
         
           $query = $db->getQuery(true);
           
           if($post['Newsletter'] == 'Y'){
               $a =  " , send_newsletter = 'Y' ";
           }else {
               $a =  " , send_newsletter = 'N' ";
           }
           
         
            if($post['address_from_email'] == 'Y'){
               $a1 =  " , send_update = 'Y' ";
           }else {
               $a1 =  " , send_update = 'N' ";
           }
           
           
           $query->update('#__advisorsettings')->set(" address1 = '".$post['address_address1']."' , address2 = '".$post['address_address2']."' , phone = '".$post['address_phone']."' , city = '".$post['address_city']."' , zip = '".$post['address_zip']."' , state = '".$post['address_state']."' $a  $a1 " )->where(' id = 1 ');
           $db->setQuery($query);
           $db->query();
           $this->setRedirect('index.php?option=com_enewsletter&view=setting');
    }
  
}
