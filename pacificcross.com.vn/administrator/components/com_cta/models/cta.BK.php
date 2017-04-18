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
		
		$selected_video = new JParameter($item->selected_video);
		$item->selected_video = $selected_video->toArray();
		$slide_video = new JParameter($item->slide_video);
		$item->slide_video = $slide_video->toArray();
		
		return $item;
		
	}//func
	
	function getVideos($selected_video = array(), $limit = 0) {
		
		$params = JComponentHelper::getParams('com_cta');
		
		$con_str = "Driver={SQL Server Native Client 10.0};Server=".$params->get('SQL_SERVER_HOST').";Database=".$params->get('SQL_SERVER_DATABASE').";MARS_Connection=YES;";
		
		
		$connection = odbc_connect($con_str, $params->get('SQL_SERVER_USERNAME'), $params->get('SQL_SERVER_PASSWORD'));
		
		/*echo $con_str;
		echo $params->get('SQL_SERVER_USERNAME');
		echo $params->get('SQL_SERVER_PASSWORD');
		var_dump($connection);
		exit;*/
		
		if ($connection===false) {
			JError::raiseWarning(500, "Could not access ".$params->get('SQL_SERVER_DATABASE')." db ".odbc_errormsg());
			return false;
		}//if
		
		$oURI = JURI::getInstance();
		$ws_url = $oURI->getHost();
		
		
		/*$tquery = "select ".($limit?"Top $limit ":NULL)."* from tblVideo";
		$tquery .= ' where (ExpirationDate IS NULL OR ExpirationDate > \''.date('Y-m-d h:i:s').'\')';
		if ($selected_video && count($selected_video)) {
			$tquery .= ' AND VideoId IN ('.implode(',', $selected_video).')';
		}//if
		//echo $tquery;
		$proc_result = odbc_exec($connection, $tquery);*/
		#var_dump($proc_result);
		#exit;
		
		
		//$ws_url = "demo12.advisorsites.com";

		$tquery = "GetSiteInfo '$ws_url'";
		$proc_result = odbc_exec($connection, $tquery);
		$site_arr = odbc_fetch_array($proc_result);
		
		#echo "<pre>";
		#var_dump($site_arr);
		#exit;
		
		//demo OrGID
		//$OrgID = 58;
		
		//$OrgID = 3932;
		
		if ($site_arr) {
			$OrgID = $site_arr['SiteID'];
		} else {
			JError::raiseWarning(500, "Not found $ws_url");
			return false;
		}//if
		
		
		$tquery = "spGetVideoBySiteId ". $OrgID;
		
		//echo $tquery;
		
		$proc_result = odbc_exec($connection, $tquery);
		if ($proc_result===false) {
			JError::raiseWarning(500, odbc_errormsg($connection));
			odbc_close ($connection);
		}//if
		
		$vid_arr = array();
		while(odbc_num_rows($proc_result)){
			$vid_arr[] = odbc_fetch_array($proc_result);
		}//while
		
		
		/*
		if (odbc_error()) {
			JError::raiseWarning(500, odbc_errormsg($connection));
			return false;
		}//if
		
		
		$video_rows = array();
		while (odbc_num_rows($proc_result)) {
			$row = odbc_fetch_array($proc_result);
			if (is_array($row)) $video_rows[] = $row;
		}//while
		*/
		
		/*echo "<pre>";
		print_r($video_rows);
		echo "</pre>";*/
		odbc_close ($connection);
		
		
		$video_rows = array();
		if ($selected_video && count($selected_video)) {
			foreach ($vid_arr as $video) {
				if (in_array($video['VideoId'], $selected_video)) {
					$video_rows[] = $video;
				}//if
			}//for
			$vid_arr = $video_rows;
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
		
		$video_selected = new JParameter($row->video_selected);
		$row->video_selected = $video_selected->toArray();
		$social_connect = new JParameter($row->social_connect);
		$row->social_connect = $social_connect->toArray();
		
		return $row;
	
	}//func
	
}//class