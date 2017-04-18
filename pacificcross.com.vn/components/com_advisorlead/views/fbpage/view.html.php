<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


jimport('joomla.application.component.view');


class AdvisorleadViewFbpage extends JViewLegacy {

    function display($tpl = null) {
		
		$app = JFactory::getApplication();
		
		
		$app_id = APP_ID;
		
		$db = JFactory::getDBO();
		
		$skipadmin = JRequest::getInt('skipadmin', 0);
		
		$signed_request = JRequest::getVar('signed_request', NULL);
		$request = $this->parse_signed_request($signed_request);
		$fb_page = $request['page']['id'];
		$is_admin = $request['page']['admin'];
		
		//debug
		//$is_admin = 1;
		//$fb_page = '854381171298879';
		
		//var_dump($fb_page);
		//var_dump($is_admin);
		
		if ($fb_page) {

			$query = "select * from `#__advisorlead_fb_page` where fb_page = '$fb_page' and app_id = '$app_id' limit 1";
			$db->setQuery($query);
			$row = $db->loadObject();
			
			//var_dump($row);
			
			if ($row) {
			
				$pageid = $row->page_id;
			
				$model = JModelLegacy::getInstance('pages', 'AdvisorleadModel');
				
				//var_dump($model);
		
				$page = $model->get_page($pageid);
	
				//echo JURI::root(false).$page->slug;
				
				if ( !$is_admin || $skipadmin ) {
					$chandle = curl_init();
					curl_setopt($chandle, CURLOPT_URL, JURI::root(false).$page->slug);
					curl_setopt($chandle, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($chandle, CURLOPT_RETURNTRANSFER,0);
					$res = curl_exec($chandle);
					curl_close($chandle);
					$app->close();
				} else {
					
					$this->fb_page_id = $row->id;
					$this->page_id = $pageid;
					$this->signed_request = $signed_request;
					
					$total = $model->get_total_pages($page->uid);
					$this->pages = $model->get_pages($page->uid, 1, $total, '');
					
					$app->setTemplate('system');
					JRequest::setVar('tmpl', 'component');
					JHtml::_('behavior.framework');
					
				}//if
			
			}//if row
			
		}//if 
		 
		parent::display($tpl);
	}


	function parse_signed_request($signed_request) {
	
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		
		$secret = APP_SECRET;
		
		// decode the data
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);
		
		// confirm the signature
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}
		
		return $data;
	}
	
	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
	
}//class