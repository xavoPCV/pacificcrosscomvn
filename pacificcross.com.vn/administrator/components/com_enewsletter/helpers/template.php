<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

class enewsletterHelper {

	function replaceTemplateCode($template_type, &$data = array(), $advisordetails = NULL, $MASSEMAIL = NULL, $sampledata = NULL ) {
	
		JLoader::import('enewsletter', JPATH_ROOT . '/administrator/components/com_enewsletter/models');
		$emodel = JModel::getInstance('Enewsletter', 'EnewsletterModel');
		
		if (!$data || !count($data)) {
			$data = array();
			$advisordetails = $emodel->getAdvisordetails();
		}//if
		
		if ($advisordetails) {
		
		
			if (is_array($advisordetails)) $advisordetails = (object) $advisordetails;
		
		
			$data['logo'] = $emodel->genLogo($advisordetails->logo);
			//$data['firm_text'] = $emodel->genFirmText($advisordetails);
			$data['social_links'] = $emodel->genSocialLinks($advisordetails->social_links);
			$data['firm_banner'] = $emodel->genBanner($advisordetails->bannerimg);
			$data['firm_address_bottom'] = $data['firm_address'] = $advisordetails->address1;
			$data['firm_address2_bottom'] = $data['firm_address2'] = $advisordetails->address2;
			$data['firm_phone_bottom'] = $data['firm_phone'] = $advisordetails->phone;
			$data['firm_city_bottom'] = $data['firm_city'] = $advisordetails->city;
			$data['firm_zip_bottom'] = $data['firm_zip'] = $advisordetails->zip;
			$data['firm_state_bottom'] = $data['firm_state'] = $advisordetails->state;
			
			
			$data['firm_second_address_bottom'] = $data['firm_second_address'] = $advisordetails->second_address1;
			$data['firm_second_address2_bottom'] = $data['firm_second_address2'] = $advisordetails->second_address2;
			$data['firm_second_phone_bottom'] = $data['firm_second_phone'] = $advisordetails->second_phone;
			$data['firm_second_city_bottom'] = $data['firm_second_city'] = $advisordetails->second_city;
			$data['firm_second_zip_bottom'] = $data['firm_second_zip'] = $advisordetails->second_zip;
			$data['firm_second_state_bottom'] = $data['firm_second_state'] = $advisordetails->second_state;
			
			
			
			
			
			switch (intval($advisordetails->address_position)){
			case 2://both
				break;
			case 1://bottom
				$data['firm_address'] = '';
				$data['firm_address2'] = '';
				$data['firm_phone'] = '';
				$data['firm_city'] = '';
				$data['firm_zip'] = '';
				$data['firm_state'] = '';
				
				$data['firm_second_address'] = '';
				$data['firm_second_address2'] = '';
				$data['firm_second_phone'] = '';
				$data['firm_second_city'] = '';
				$data['firm_second_zip'] = '';
				$data['firm_second_state'] = '';
				
				
				break;
			case 0://top
			default:
				
				$data['firm_address_bottom'] = '';
				$data['firm_address2_bottom'] = '';
				$data['firm_phone_bottom'] = '';
				$data['firm_city_bottom'] = '';
				$data['firm_zip_bottom'] = '';
				$data['firm_state_bottom'] = '';
			
			
				$data['firm_second_address_bottom'] = '';
				$data['firm_second_address2_bottom'] = '';
				$data['firm_second_phone_bottom'] = '';
				$data['firm_second_city_bottom'] = '';
				$data['firm_second_zip_bottom'] = '';
				$data['firm_second_state_bottom'] = '';
			
				break;
			}//switch
			
		} else {
		
			$advisordetails = $emodel->getAdvisordetails();
		
		}//if
		
		
		//is this have second address
		$have_second_address_top = false;
		if ($data['firm_second_address'] || $data['firm_second_address2'] || $data['firm_second_phone'] || $data['firm_second_city'] || $data['firm_second_zip'] || $data['firm_second_state'] ) {
			$have_second_address_top = true;	
		}//if
		
		$have_second_address_bottom = false;
		if ($data['firm_second_address_bottom'] || $data['firm_second_address2_bottom'] || $data['firm_second_phone_bottom'] || $data['firm_second_city_bottom'] || $data['firm_second_zip_bottom'] || $data['firm_second_state_bottom'] ) {
			$have_second_address_bottom = true;	
		}//if
		
		$disclosure = '';
		$articles = '';
		switch ($template_type) {
		case 'massemail':
			$disclosure = $advisordetails->mass_email_disclosure;
			break;
		case 'weeklyupdate':
			$disclosure = $advisordetails->weekly_update_newsletter;
			
			$articles = $data['weekly_update_content'];
			
			break;
		case 'newsletter':
			$disclosure = $advisordetails->newsletter_disclosure;
			
			$articleimgarray = array();
			$articleimg_j  = 1;
			
			//var_dump($data['articles']);
			//exit;
			
			if ($data['articles'] && $MASSEMAIL) {
				
				
				foreach($data['articles'] as $a) {	
					$articlebody = $a->description;										  
					$articletitle_rpl = '{$articletitle'.$articleimg_j.'}';
					$articlebody_rpl = '{$articlebody'.$articleimg_j.'}';
					$slide_rpl = '{$articleimage'.$articleimg_j.'}';
					$articlelink_rpl = '{$articlelink'.$articleimg_j.'}';
				
					if(trim($a->articlelink) ==''){
						$a->articlelink = 'javascript:void(0);';	
					}
				
					if($a->image == ''){ 
						$articleimgarray[] = 'article_img_'.$articleimg_j;
					}
				
					if($a->image != ''){
						$rsearch  = array($articletitle_rpl,$articlebody_rpl,$slide_rpl,$articlelink_rpl);
						$rreplace = array($a->article_title,$articlebody,$a->image,$a->articlelink);
					}else{
						$rsearch  = array($articletitle_rpl,$articlebody_rpl,$articlelink_rpl);
						$rreplace = array($a->article_title,$articlebody,$a->articlelink);
					}
				
					$MASSEMAIL =  str_replace($rsearch, $rreplace, $MASSEMAIL);
				
					$articleimg_j++;
						
				}//for
				
				//echo '<pre>';
				//print_r($MASSEMAIL);
				//exit;
				
			}//if articles
			
			break;
		}//swicth
		
		//echo '<pre>';
		//print_r($MASSEMAIL);
		//exit;
		
		
		$content = '';
		
		if ($MASSEMAIL) {

			if ($sampledata) {
				$data['intro'] = 'Template Intro Here..';
				$data['trailer'] = 'Template Trailer Here..';
				
				$data['articletitle1'] = 'Article 1 Title Here..';
				$data['articlebody1'] = 'Article 1 Body Here..';
				$data['articlelink1'] = '#';
				$data['articleimage1'] = 'Article 1 Image Here..';
				
				$data['articletitle2'] = 'Article 2 Title Here..';
				$data['articlebody2'] = 'Article 2 Body Here..';
				$data['articlelink2'] = '#';
				$data['articleimage2'] = 'Article 2 Image Here..';
				
				$data['articletitle3'] = 'Article 3 Title Here..';
				$data['articlebody3'] = 'Article 3 Body Here..';
				$data['articlelink3'] = '#';
				$data['articleimage3'] = 'Article 3 Image Here..';
				
				$data['articletitle4'] = 'Article 4 Title Here..';
				$data['articlebody4'] = 'Article 4 Body Here..';
				$data['articlelink4'] = '#';
				$data['articleimage4'] = 'Article 4 Image Here..';
				
				$data['articletitle5'] = 'Article 5 Title Here..';
				$data['articlebody5'] = 'Article 5 Body Here..';
				$data['articlelink5'] = '#';
				$data['articleimage5'] = 'Article 5 Image Here..';
				
				$data['weekly_update_content'] = 'Weekly Update Content Here..';
				
				
				$data['mass_email_content'] = 'Mass Email Content Here..';
				
				$disclosure = 'Template Disclosure Here..';
			}//if
		   
		   
		   $rsearch  = array('{$intro}'
							   , '{$trailer}'
							   ,'{$articletitle1}'
							   ,'{$articlebody1}'
							   ,'{$articlelink1}'
							   ,'{$articleimage1}'
							   ,'{$articletitle2}'
							   ,'{$articlebody2}'
							   ,'{$articleimage2}'
							   ,'{$articlelink2}'
							   ,'{$articletitle3}'
							   , '{$articlebody3}'
							   ,'{$articleimage3}'
							   ,'{$articlelink3}'
							   ,'{$articletitle4}'
							   ,'{$articlebody4}'
							   ,'{$articleimage4}'
							   ,'{$articlelink4}'
							   ,'{$articletitle5}'
							   ,'{$articlebody5}'
							   ,'{$articleimage5}'
							   ,'{$articlelink5}'

							   ,'{$articles}'
		   					   ,'{$massemailcontent}'

							   ,'{$disclosure}'
							   ,'{$logo}'
							   ,'{$firm_address}'
							   ,'{$social_links}'
							   ,'{$firm_phone}'
							   ,'{$firm_address2}'
							   ,'{$firm_city}'
							   ,'{$firm_zip}'
							   ,'{$firm_state}'
							   ,'{$firm_email}'
							   ,'{$firm_url}'
							   ,'{$firm_name}'
							   ,'{$firm_banner}'
							   ,'{$firm_address_bottom}'
							   ,'{$firm_address2_bottom}'
							   ,'{$firm_city_bottom}'
							   ,'{$firm_zip_bottom}'
							   ,'{$firm_state_bottom}'
							   ,'{$firm_phone_bottom}'
							   
							   
							   ,'{$firm_second_address}'
							   ,'{$firm_second_address2}'
							   ,'{$firm_second_city}'
							   ,'{$firm_second_zip}'
							   ,'{$firm_second_state}'
							   ,'{$firm_second_phone}'
							   
							   
							   ,'{$firm_second_address_bottom}'
							   ,'{$firm_second_address2_bottom}'
							   ,'{$firm_second_city_bottom}'
							   ,'{$firm_second_zip_bottom}'
							   ,'{$firm_second_state_bottom}'
							   ,'{$firm_second_phone_bottom}'
						);
			
			
			
			
			$rreplace = array($data['intro']
								, $data['trailer']
								, $data['articletitle1']
								, $data['articlebody1']
								, $data['articlelink1']
								, $data['articleimage1']
								, $data['articletitle2']
								, $data['articlebody2']
								, $data['articlelink2']
								, $data['articleimage2']
								, $data['articletitle3']
								, $data['articlebody3']
								, $data['articlelink3']
								, $data['articleimage3']
								, $data['articletitle4']
								, $data['articlebody4']
								, $data['articlelink4']
								, $data['articleimage4']
								, $data['articletitle5']
								, $data['articlebody5']
								, $data['articlelink5']
								, $data['articleimage5']
								
								, $articles
			
								, $data['mass_email_content']
								
								, $disclosure
								,$data['logo']
								,$data['firm_address']
								,$data['social_links']
								,$data['firm_phone']
								,$data['firm_address2']
								,$data['firm_city']
								,$data['firm_zip']
								,$data['firm_state']
								,'<a href="mailto:'.$advisordetails->from_email.'">'.$advisordetails->from_email.'</a>'
								,'<a href="'.(substr($advisordetails->url, 0, 4)!='http'?'http://':'').$advisordetails->url.'" target="_blank">'.$advisordetails->url.'</a>'
								,$advisordetails->firm
								,$data['firm_banner']
								,$data['firm_address_bottom']
								,$data['firm_address2_bottom']
								,$data['firm_city_bottom']
								,$data['firm_zip_bottom']
								,$data['firm_state_bottom']
								,$data['firm_phone_bottom']
								
								
								,$data['firm_second_address']
								,$data['firm_second_address2']
								,$data['firm_second_city']
								,$data['firm_second_zip']
								,$data['firm_second_state']
								,$data['firm_second_phone']
								
								,$data['firm_second_address_bottom']
								,$data['firm_second_address2_bottom']
								,$data['firm_second_city_bottom']
								,$data['firm_second_zip_bottom']
								,$data['firm_second_state_bottom']
								,$data['firm_second_phone_bottom']
						);
			
			$content =  str_replace($rsearch, $rreplace, $MASSEMAIL);
			
			
			
			
			
			$dom = new SmartDOMDocument();
			$dom->loadHTML($content);
			
			/*
			$baseurl = JURI::base();
			$baseurl = str_replace("administrator/", "", $baseurl);
			*/
			$baseurl = JURI::root(false);//have / at last
			$imgs = $dom->getElementsByTagName("img");
			foreach($imgs as $img){
				$src = $img->getAttribute('src');
				if (strpos($src,'http') !== false) {
				}else{
					$img->setAttribute( 'src' , $baseurl.$src );
				}
			}//for
			
			
			if(trim($data['intro']) == ''){
				$introelements = $dom->getElementById('intro');
				if(!empty($introelements)){
					$introelements->parentNode->removeChild($introelements);
				}			
			 }
			 
			 if(trim($disclosure) == ''){
				$disclosureelements = $dom->getElementById('disclosure');
				if(!empty($disclosureelements)){
					$disclosureelements->parentNode->removeChild($disclosureelements);
				}
				$disclosuretextelements = $dom->getElementById('disclosuretext');
				if(!empty($disclosuretextelements)){
					$disclosuretextelements->parentNode->removeChild($disclosuretextelements);
				}			
			 }//if
			 
			if(trim($data['trailer']) == ''){
				$trailerelements = $dom->getElementById('trailer');
				if(!empty($trailerelements)){
					$trailerelements->parentNode->removeChild($trailerelements);
				}
			}//if
			
			
			for($m=0; $m< count($articleimgarray); $m++){
				$elements = $dom->getElementById($articleimgarray[$m]);
				if(!empty($elements)){
					$elements->parentNode->removeChild($elements);
				}			
			}
			
			
			if ($sampledata) {
				 for($m=1; $m<=5; $m++){
					$elements = $dom->getElementById('article_img_'.$m);
					if(!empty($elements)){             
						$elements->parentNode->replaceChild($dom->createTextNode(sprintf("Article $m Image Here..", $m)),$elements);
					}//if
				}//for
			
			} else {
				for($k=$articleimg_j; $k<=5; $k++){
					$elements = $dom->getElementById('article_'.$k);
					if(!empty($elements)){
						$elements->parentNode->removeChild($elements);
					}			
				}//for
			}//if
			
			if ($data['firm_banner']) {
				$enewsheader = $dom->getElementById('enewsheader');
				if($enewsheader) $enewsheader->parentNode->removeChild($enewsheader);
			}//if
			
			if (!$data['firm_address_bottom']) {
				$bottomaddress = $dom->getElementById('bottomaddress');
				if($bottomaddress) $bottomaddress->parentNode->removeChild($bottomaddress);
			}//if
			
			if (!$data['firm_address']) {
				$topaddress = $dom->getElementById('topaddress');
				if($topaddress) $topaddress->parentNode->removeChild($topaddress);
			}//if
			
			if (!$have_second_address_top) {
				$secondaddress = $dom->getElementById('secondaddress');
				if($secondaddress) $secondaddress->parentNode->removeChild($secondaddress);
			}//if
			
			if (!$have_second_address_bottom) {	
				$bottomaddress2 = $dom->getElementById('bottomaddress2');
				if($bottomaddress2) $bottomaddress2->parentNode->removeChild($bottomaddress2);
			
			}//if
			
			$content = $dom->saveHTML();
		
		}//if
	
		return $content;
		
		
	}//func
	
}
