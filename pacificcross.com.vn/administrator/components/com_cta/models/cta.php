<?php
defined('_JEXEC') or die;

jimport( 'joomla.html.parameter' );

class CtaModelCta extends JModelLegacy {
	
	function getSetting() {
		$db = $this->getDbo();
		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__cta_setting');
		$db->setQuery($query);
		#echo str_replace('#_','plat',$query);
		$item = $db->loadObject();
		#print_r($setting_row);
		
		$selected_video = new JRegistry($item->selected_video);
		$item->selected_video = $selected_video->toArray();
		$slide_video = new JRegistry($item->slide_video);
		$item->slide_video = $slide_video->toArray();
		
		return $item;
		
	}//func
	
	function getVideos($selected_video = array(), $limit = 0) {
		
		$params = JComponentHelper::getParams('com_cta');
		
		if ( strpos($_SERVER['HTTP_HOST'], 'localhost')!==false ) {
		
			$vid_arr = array();
			$vid_arr[0] = array('VideoId' => 11, 'Title' => 'Investment Perspectives 2013', 'VideoFile' => 'inv-per4.mp4', 'ImgCTA' => 'investmentstory.jpg');
			$vid_arr[1] = array('VideoId' => 12, 'Title' => 'Blah Blah', 'VideoFile' => 'inv-per4.mp4', 'ImgCTA' => 'earningsvsstock.jpg');
			$vid_arr[2] = array('VideoId' => 10, 'Title' => 'Retirement Income Report', 'VideoFile' => 'retirement-income.mp4', 'ImgCTA' => 'retirementincomereport.jpg');
			
			
			if ($selected_video && count($selected_video)) {
				foreach ($vid_arr as $video) {
					if (in_array($video['VideoId'], $selected_video)) {
						$video_rows[] = $video;
					}//if
				}//for
				$vid_arr = $video_rows;
			}//if
			
		
		} else {
			/*
			$con_str = "Driver={SQL Server Native Client 10.0};Server=".$params->get('SQL_SERVER_HOST').";Database=".$params->get('SQL_SERVER_DATABASE').";MARS_Connection=YES;";
			$connection = odbc_connect($con_str, $params->get('SQL_SERVER_USERNAME'), $params->get('SQL_SERVER_PASSWORD'));
			if ($connection===false) {
				JError::raiseWarning(500, "Could not access ".$params->get('SQL_SERVER_DATABASE')." db ".odbc_errormsg());
				return false;
			}//if
			
			$tquery = "exec spGetVideos";
			//echo $tquery;
			
			$proc_result = odbc_exec($connection, $tquery);
			if ($proc_result===false) {
				JError::raiseWarning(500, odbc_errormsg($connection));
				odbc_close ($connection);
			}//if
			*/
			$vid_arr = array();
			/*
			while(odbc_num_rows($proc_result)){
				$row = odbc_fetch_array($proc_result);
				if (is_array($row)) $vid_arr[] = $row;
			}//while
	
			odbc_close ($connection);
			*/
			
			$video_rows = array();
			/*
			if ($selected_video && count($selected_video)) {
				foreach ($vid_arr as $video) {
					if (in_array($video['VideoId'], $selected_video)) {
						$video_rows[] = $video;
					}//if
				}//for
				$vid_arr = $video_rows;
			}//if
			*/
		}//if
		
		if ($limit) {
			foreach ($vid_arr as $i => $video) {
				if ($i < $limit) {
					$video_rows[] = $video;
				}//if
			}//foreach
			$vid_arr = $video_rows;
		}//if
		
		return $vid_arr;
		
	}//func
	
	function getRegister($id) {
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__cta_register');
		$query->where('`id` = '.(int)$id);
		$db->setQuery($query);
		#echo str_replace('#_','plat',$query);
		$row = $db->loadObject();
		
		$video_selected = new JRegistry($row->video_selected);
		$row->video_selected = $video_selected->toArray();
		$social_connect = new JRegistry($row->social_connect);
		$row->social_connect = $social_connect->toArray();
		
		return $row;
	
	}//func
	
	function makeSlider($video_rows, $nu_slide = 1, $cta_url = NULL, $loadJquery = 1) {
		
		$db	= $this->getDbo();
		
		$base_url = JURI::root();
		$is_https = substr($base_url, 0, 8);
		if ($is_https=='https://') {
			$base_url = str_replace('https://','http://',$base_url);
		}//if
		
		if (!$cta_url) $cta_url = $base_url.'index.php?option=com_cta&view=form';
		
		
		$slide_code = array();
		$slide_code2 = array();
		
		$slide_width = 207;
		$slide_height = 155;
		
		//$app 	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_cta');
		
		$count_vid = count($video_rows);
		
		if ($count_vid) {
			$slide_code[] = '<link rel="stylesheet" type="text/css" href="'.$base_url.'components/com_cta/assets/skitter.styles.css" />'."\n";
			
			if ($loadJquery) {
				$slide_code[] = '<script type="text/javascript" src="'.$base_url.'components/com_cta/assets/jquery.min.js"></script>'."\n";
			}//if
			$slide_code[] = '<script type="text/javascript" src="'.$base_url.'components/com_cta/assets/jquery.skittercta.min.js"></script>'."\n";
			$slide_code[] = '<script type="text/javascript" src="'.$base_url.'components/com_cta/assets/jquery-ui.min.js"></script>'."\n";
		
			$slide_code[] = '<style type="text/css">'."\n";
			$slide_code[] = '.box_skitter_cta_large {
									width:'.$slide_width.'px;
									height:'.$slide_height.'px;
								}'."\n";
			$slide_code[] = '</style>'."\n";
			$slide_code[] = '<script type="text/javascript">'."\n";
			$slide_code[] = 'var ctaslide = jQuery.noConflict();'."\n";
			$slide_code[] = 'ctaslide(document).ready(function(){'."\n";
			$slide_code[] = 'ctaslide(\'.box_skitter_cta_large\').skittercta({'."\n";
			$slide_code[] = 'interval:2000,
								navigation:false,
								numbers:false,
								animation:\'fade\''."\n";
			$slide_code[] = '});'."\n";
			$slide_code[] = '});'."\n";
			$slide_code[] = '</script>'."\n";
			$slide_code[] = '<div class="box_skitter_cta box_skitter_cta_large">'."\n";
			$slide_code[] = '<ul>'."\n";
		
			$i = 0;
			foreach($video_rows as $video_row) {
				$img_thumb = $base_url.'components/com_cta/assets/no_image.png';
				if ($video_row['ImgCTA'])
					$img_thumb = $params->get('IMG_BASE_URL').'/'.$video_row['ImgCTA'];
				$slide_code[] = '<li><a href="'.$cta_url.'"><img src="'.$img_thumb.'" border="0"></a></li>'."\n";
				$i++;
				if ( $nu_slide > 1 && $i==3 ) break;
			}//for
		
			$slide_code[] = '</ul>'."\n";
			$slide_code[] = '</div><!--box_skitter_cta_large-->'."\n";
			
			
			switch ($nu_slide) {
			case 2:
					
				if ($count_vid>3) {

					$slide_code2[] = '<link rel="stylesheet" type="text/css" href="'.$base_url.'components/com_cta/assets/skitter.styles.css" />'."\n";
					$slide_code2[] = '<script type="text/javascript" src="'.$base_url.'components/com_cta/assets/jquery.min.js"></script>'."\n";
					$slide_code2[] = '<script type="text/javascript" src="'.$base_url.'components/com_cta/assets/jquery.skittercta.min.js"></script>'."\n";
					$slide_code2[] = '<script type="text/javascript" src="'.$base_url.'components/com_cta/assets/jquery-ui.min.js"></script>'."\n";
			
					$slide_code2[] = '<style type="text/css">'."\n";
					$slide_code2[] = '.box_skitter_cta_large2 {
											width:'.$slide_width.'px;
											height:'.$slide_height.'px;
										}'."\n";
					$slide_code2[] = '</style>'."\n";
					
					$slide_code2[] = '<script type="text/javascript">'."\n";
					$slide_code2[] = 'var ctaslide2 = jQuery.noConflict();'."\n";
					$slide_code2[] = 'ctaslide2(document).ready(function(){'."\n";
					$slide_code2[] = 'ctaslide2(\'.box_skitter_cta_large2\').skittercta({'."\n";
					$slide_code2[] = 'interval:2000,
										navigation:false,
										numbers:false,
										animation:\'fade\''."\n";
					$slide_code2[] = '});'."\n";
					$slide_code2[] = '});'."\n";
					$slide_code2[] = '</script>'."\n";
					$slide_code2[] = '<div class="box_skitter_cta box_skitter_cta_large2">'."\n";
					$slide_code2[] = '<ul>'."\n";
					
					for($i = 3; $i < $count_vid; $i++) {
						$video_row = $video_rows[$i];
						
						if ($video_row['ImgCTA'])
							$img_thumb = $params->get('IMG_BASE_URL').'/'.$video_row['ImgCTA'];
						else
							$img_thumb = $base_url.'components/com_cta/assets/no_image.png';
						
						$slide_code2[] = '<li><a href="'.$cta_url.'"><img src="'.$img_thumb.'" border="0"></a></li>'."\n";
					}//for
					
					$slide_code2[] = '</ul>'."\n";
					$slide_code2[] = '</div><!--box_skitter_cta_large2-->'."\n";
					
				}//if
				
				break;
			}//switch
			
		}//if count_vid
		
		
		return array(implode($slide_code),implode($slide_code2));
		
	}//func
	
}//class