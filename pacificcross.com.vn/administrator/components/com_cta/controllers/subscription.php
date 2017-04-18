<?php
// No direct access
defined('_JEXEC') or die;

class CtaControllerSubscription extends JControllerLegacy {
	
	
	function save() {
		
		#print_r($_POST);
		#exit;
		JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		$id = JRequest::getInt('id');
		//$mandatory_social = JRequest::getInt('mandatory_social');
		$mandatory_enewsletter = JRequest::getInt('mandatory_enewsletter');
		
		$social_icon_size = JRequest::getInt('social_icon_size');
		$used_facebook = JRequest::getInt('used_facebook');
		$used_linkedin = JRequest::getInt('used_linkedin');
		$used_twitter = JRequest::getInt('used_twitter');
		$used_enewsletter = JRequest::getInt('used_enewsletter');
		$link_facebook = JRequest::getVar('link_facebook');
		$link_linkedin = JRequest::getVar('link_linkedin');
		$link_twitter = JRequest::getVar('link_twitter');
		
		$constantcontact = JRequest::getInt('constantcontact');
		$mailchimp = JRequest::getInt('mailchimp');
		
		
		$constant_contact_email = JRequest::getVar('constant_contact_email');
		//$constant_contact_key = JRequest::getVar('constant_contact_key');
		//$constant_contact_secret = JRequest::getVar('constant_contact_secret');
		$constant_contact_token = JRequest::getVar('constant_contact_token');
		$mailchimp_email = JRequest::getVar('mailchimp_email');
		$mailchimp_api_key = JRequest::getVar('mailchimp_api_key');
		
		//$social_text = JRequest::getVar('social_text');
		
		$social_text = JRequest::getVar('social_text', NULL, 'post', 'string', JREQUEST_ALLOWRAW);
		
		$social_text_position = JRequest::getVar('social_text_position');
		
		$logo = $_FILES['enewsletter_logo'];
			
		$old_enewsletter_logo = JRequest::getVar('old_enewsletter_logo');
		$del_enewsletter_logo = JRequest::getVar('del_enewsletter_logo');
		
		if ($id) {
			
			$enewsletter_logo = NULL;
			
			define('PHOTOS_URL', 'components/com_cta/enewsletter/');
			define('PHOTOS_DIR', JPATH_ROOT.'/'.PHOTOS_URL);
			if (!is_dir(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
			
			
			if ( $del_enewsletter_logo && $old_enewsletter_logo ) {
				unlink(PHOTOS_DIR.$old_enewsletter_logo);
				$enewsletter_logo = '';
			}//if
			
			if ($logo['name'] && !$logo['error']) {
				foreach (glob(JPATH_ROOT.'/'.PHOTOS_URL."*") as $filename) {
					unlink($filename);
				}//for
				if (move_uploaded_file($logo['tmp_name'], PHOTOS_DIR.$logo['name'])) {
					$enewsletter_logo = $logo['name'];
				}//if
			}//if
			
			
		
			$db = & JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update('#__cta_setting');
			
			//$query->set('`mandatory_social` = '.(int)$mandatory_social);
			$query->set('`mandatory_enewsletter` = '.(int)$mandatory_enewsletter);
			
			$query->set('`social_icon_size` = '.(int)$social_icon_size);
			$query->set('`used_facebook` = '.(int)$used_facebook);
			$query->set('`used_linkedin` = '.(int)$used_linkedin);
			$query->set('`used_twitter` = '.(int)$used_twitter);
			$query->set('`used_enewsletter` = '.(int)$used_enewsletter);
			$query->set('`link_facebook` = '.$db->quote($link_facebook));
			$query->set('`link_linkedin` = '.$db->quote($link_linkedin));
			$query->set('`link_twitter` = '.$db->quote($link_twitter));
			
			$query->set('`constantcontact` = '.$db->quote($constantcontact));
			$query->set('`mailchimp` = '.$db->quote($mailchimp));
			
			$query->set('`constant_contact_email` = '.$db->quote($constant_contact_email));
			//$query->set('`constant_contact_key` = '.$db->quote($constant_contact_key));
			//$query->set('`constant_contact_secret` = '.$db->quote($constant_contact_secret));
			$query->set('`constant_contact_token` = '.$db->quote($constant_contact_token));
			$query->set('`mailchimp_email` = '.$db->quote($mailchimp_email));
			$query->set('`mailchimp_api_key` = '.$db->quote($mailchimp_api_key));
			
			if ($enewsletter_logo!==NULL) $query->set('`enewsletter_logo` = '.$db->quote($enewsletter_logo));
			$query->set('`social_text` = '.$db->quote($social_text));
			$query->set('`social_text_position` = '.$db->quote($social_text_position));
			
			$query->where('`id` = '.(int)$id);
			$db->setQuery($query);
			$db->execute();
			
			#echo $query;
			#exit;
			
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=subscription', false));
			return true;
			
		}//if
		
		
	}//func
}
