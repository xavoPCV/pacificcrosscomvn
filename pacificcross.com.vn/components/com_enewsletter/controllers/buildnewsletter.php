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


class EnewsletterControllerBuildnewsletter extends JController {

	//ajax
	function send(){
	
		$result = array('status' => 0, 'msg' => 'Invalid data');
		
		$id = JRequest::getInt('id');
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__advisorsettings');
		$db->setQuery($query, 0, 1);
		$advisordetails = $db->loadObject();
		
		$query->clear()->select('*')->from('#__enewsletter')->where('`id` = '.(int)$id)->where('`email_sent_status` = 0')->where('`type` = "enewsletter"')->where('(`email_id` = "" OR `email_id` IS NULL)');
		$db->setQuery($query);
		$enewsletterRow = $db->loadObject();
		
		//echo $query->dump();
		
		$query->clear()->select('`group_id`')->from('#__enewsletter_groups')->where('`e_id` = '.(int)$id);
		$db->setQuery($query);
		$gid = $db->loadColumn();
		
		
		$query->clear()->select('`article_id`')->from('#__enewsletter_article')->where('`e_id` = '.(int)$id);
		$db->setQuery($query);
		$showimage_ids = $cid = $db->loadColumn();
		
		
		if ( $advisordetails && $enewsletterRow && $gid && $cid ) {
		
			$api = $enewsletterRow->api_type;
			$from_email_address = $advisordetails->from_email;
			
			if ($api == 'M'){
				//$verified_email_name ???
				$from_email_address = trim($verified_email_name).'@'.trim($from_email_address);
			}//if
			
			// Get all details of logged user
			$loggeduser = JFactory::getUser();
		
			$data = array();
			$data['mdefaultemail'] = 1;//enewsletter.html
			$data['subject'] = $enewsletterRow->subject;
			$data['test_email'] = $from_email_address;
			$data['mass_email_content'] = $enewsletterRow->mass_email_content;
			$data['id'] = $id;
			$data['email_id'] = 0;
			$data['task'] = 'send';
		
			$ids = implode('","', $cid);
		
			// Get buildnesletter model
			require_once JPATH_ADMINISTRATOR.'/components/com_enewsletter/models/buildnewsletter.php';
			$model = JModelLegacy::getInstance('Buildnewsletter', 'EnewsletterModel', array('ignore_request' => true));
		
			$articles = $model->getArticle($ids);
			$articlecontent = '';
			
			$NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/enewsletter.html");
			
			// Create and replace content for selected articles start
			$articleimgarray = array();
			$j  = 1;
			
			
			
			//http://basesitesv1f.advisorproducts.com/resources/featured-news?view=fnclist&id={articleid}
			$custom_link_article = $advisordetails->custom_link_article;
			$valid_format = strpos($custom_link_article, '{articleid}')===false?false:true;
			
			
			foreach($articles as $ar){		
			  $articlelink=''  ;
			  
			  
			  
			  
			  if($valid_format)
			  {
				$articlelink = str_replace('{articleid}' ,$ar->article_id, trim($custom_link_article));
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
				
			  }//if
			  
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
		
		}//for
		
		$data['articles'] = $articles;
		
			
		$data['newsletter_disclosure'] = $advisordetails->newsletter_disclosure;
			
			
			
			
			
			$app = JFactory::getApplication();
			
			
			// Set all selected groups id's to state 
			$app->setUserState("com_enewsletter.gid",$gid);
			
			// Set current form's data to state 
			$app->setUserState("com_enewsletter.data",$data);
			
			$footer = '';
			
			
			$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, $advisordetails, $NEWSLETTER);
		
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
			
			
			
			
			
			//print_r($content);
			//exit;
			
			$APIKEY  = CONSTANT_APIKEY;
			$ACCESS_TOKEN = $advisordetails->api_key;
			
			
			// Check for current api mailchimp/constant contact and based on that create campaign
			if($api == 'M'){
				$mailchimp = new MCAPI(trim($ACCESS_TOKEN));
	
				$type = 'regular';
				$campaign_title = time().'_'.$data['subject'];
				$campaign_title =  substr($campaign_title, 0, 78);
				$opts['title'] = $campaign_title;
				$opts['subject'] = $data['subject'];
				$opts['from_name'] = $advisordetails->from_name;
			  	$opts['from_email'] = $from_email_address;
			  
			  
				$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);			
				$opts['authenticate'] = true;			
				$email_content = array('html' => $content, 
										'html_footer' => 'the footer with an *|UNSUB|* message' );
				
				$email_ids = array();
				
				foreach($gid as $g){			
					$opts['list_id'] = $g;							
					$retval = $mailchimp->campaignCreate($type, $opts, $email_content);
					if ($mailchimp->errorCode){
						$result['msg'] = JText::_($mailchimp->errorMessage);
					} else {
						$return = $mailchimp->campaignSendNow($retval);
						if ($mailchimp->errorCode){
							$result['msg'] = JText::_($mailchimp->errorMessage);
						} else {			
							$email_ids[] = $retval;	
						} 
					}	
				}//for
				
				$data['email_id'] = implode(',',$email_ids);			
			
			} else if ($api == 'C'){
	
				$cc = new ConstantContact($APIKEY);	
				$campaign = new Campaign();
	
				$campaign_title = time().'_'.$data['subject'];
				$campaign_title =  substr($campaign_title, 0, 78);
				$campaign->name = $campaign_title;
				$campaign->subject = $data['subject'];
				$campaign->from_name = $advisordetails->from_name;

				  $campaign->from_email = $from_email_address;
				  $campaign->reply_to_email = $from_email_address;
		  
				$campaign->greeting_string = $data['subject'];		
				$campaign->text_content = $data['subject'];			
				$campaign->email_content_format = 'HTML';
				$campaign->email_content = $content;
				
				foreach($gid as $g){
					$campaign->addList($g);
				}
				
				
				
				#HT
				$message_footer = new MessageFooter();
				$message_footer->city = $advisordetails->city;
				$message_footer->state = $advisordetails->state;
				$message_footer->country = $advisordetails->country;
				$message_footer->organization_name = $advisordetails->firm;
				$message_footer->address_line_1 = $advisordetails->address1;
				
				//$campaign->message_footer = $message_footer;
				
				try{
					$return = $cc->addEmailCampaign($ACCESS_TOKEN, $campaign);
					try{
						$schedule = new Schedule();
						$cc->addEmailCampaignSchedule($ACCESS_TOKEN, $return->id, $schedule);
						$data['email_id'] = $return->id;
						$result['status'] = $return->id;
					}catch (CtctException $ex2) {
						$ccerror = $ex2->getErrors();
						$result['msg'] = JText::_($ccerror[0]['error_message'])." - (from Constant Contact2)";
						
					}
					
				} catch (CtctException $ex) {
					$ccerror = $ex->getErrors();
					$result['msg'] = JText::_($ccerror[0]['error_message'])." - (from Constant Contact1)";
				}					
			}//if C
			
			
			//exit;
			
			$app->setUserState("com_enewsletter.data",'');
			$app->setUserState("com_enewsletter.gid",'');
			
			if ( $return && $data['email_id'] ){
				
				// Save/update mass email data	
				$query = $db->getQuery(true);
				$query->update('#__enewsletter');
				$query->set('email_id = '.$db->Quote($data['email_id']));
				$query->set('email_sent_status = 1');
				$query->where('id = '.(int)$data['id']);
				$db->setQuery($query);
				$db->execute();
				
				//echo '<p>'.$query->dump();
			
				
				// Assigns and store data in history table after creating and sending campaign
				$historydata = array();
				$historydata['title'] 			= $data['subject'];
				$historydata['campaign_title'] 	= $campaign_title;
				$historydata['subject'] 		= $data['subject'];
				$historydata['content'] 		= $content;
				$historydata['e_id'] 			= $data['id'];
				$historydata['email_id'] 		= $data['email_id'];
				$historydata['api_type'] 		= $api;
				$app->setUserState("com_enewsletter.User_ID", $enewsletterRow->user_id);
				
				//print_r($historydata);
				
				require_once JPATH_ADMINISTRATOR.'/components/com_enewsletter/models/history.php';
				$historymodel = JModelLegacy::getInstance('History', 'EnewsletterModel', array('ignore_request' => true));
				$historyreturn = $historymodel->saveHistory($historydata);
				
				
				/* Send mail to archive list start */
				if ( $advisordetails->archive_cc_list != '' && $advisordetails->archive_cc_list != NULL ){
				
					if ($api == 'C'){
						$listcontacts = '<center><br>Newsletter sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
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
						}//for
						$listcontacts .= '</table></center>';
					} else if($api == 'M'){
						$listcontacts = '<center><br>Newsletter sent to :<br><br><table border="1"><th>&nbsp;</th><th>Name</th><th>Time</th>';
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
					}//if
					
					$mailer = JFactory::getMailer();
					$config = JFactory::getConfig();
					
					
					$sender = array( 
									$from_email_address,
									$advisordetails->from_name
						 		);	
						 
					$mailer->setSender($sender);
					$recipient = explode(',',$advisordetails->archive_cc_list);		 
			
					$mailer->addRecipient($recipient);
					$mailer->isHTML(true);
					$mailer->Encoding = 'base64';
					$mailer->setSubject($data['subject']);
					$mailer->setBody($content.$listcontacts);
					
					//echo $content.$listcontacts;
	
					$send = $mailer->Send();
					if ( $send !== true ) {
						$result['msg'] = JText::_('Error sending test email.');
					} else {
						$result['msg'] = JText::_('Mail Sent to Archive List Successfully.');
					}//if
					
				}//if archive_cc_list
				/* Send mail to archive list end */	
				
				$result['msg'] = JText::_('E-Newsletter sent to Email Campaign system successfully.');
				
			}//if return
			
		}//if advisordetails
		
		echo json_encode($result);
		exit;
		
		
	}//func
	
  /**
	 * Used to create  resized image for articles .
	 * @param $name string (filename)
	 * @return string
	 */
  function create_image($name){
        $img = file_get_contents($name);
        $orgname = basename($name); 

        $newname_array = explode('.',$orgname);
        $imagename =    $newname_array[0].'_'.time().'.jpg';
        $path = JPATH_SITE.'/media/com_enewsletter/article_images/'.$imagename;

        $im = imagecreatefromstring($img);
        
        $size = getimagesize($name);

        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $newwidth = 475;
            $newheight = 475/$ratio;
        }
        else {
            $newwidth = 475*$ratio;
            $newheight = 475;
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
  
}
