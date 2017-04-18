<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Banners list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class CtaControllerLeads extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	//protected $text_prefix = 'com_orlandoconventionaid_events';

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Lead', $prefix = 'CtaModel', $config = array('ignore_request' => true))
	{
		
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	function export() {
		
		$app = JFactory::getApplication();

		$db		=& JFactory::getDBO();
		
		$this->setRedirect( 'index.php?option=com_cta&view=leads' );
		
		
		//do export
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cta'.DS.'export_excel'.DS.'Worksheet.php');
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cta'.DS.'export_excel'.DS.'Workbook.php');
		
		$query = "SELECT a.*, DATE_FORMAT(`date_created`,'%m-%d-%Y') AS `created`"
					. ' FROM #__cta_register AS a'
					. " ORDER BY id DESC";

		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		#echo '<pre>';
		#print_r($rows);
		#exit;
		
		if ($rows) {
			// HTTP headers
			$this->_HeaderingExcel('leads_'.time().".xls");
			// Creating a workbook
			$workbook = new Workbook("-");
			// Creating the second worksheet
			$worksheet2 =& $workbook->add_worksheet();
			
			// Format for the headings
			$formatot =& $workbook->add_format();
			$formatot->set_size(10);
			$formatot->set_align('left');
			$formatot->set_color('white');
			$formatot->set_pattern();
			$formatot->set_fg_color('black');
			
			
			//note that the header contain the three columns and its a array
			$array['report_header'] = array("Created", "First Name", "Last Name", "Email", "Phone", "Enewsletter" );
			
			foreach ($array['report_header'] as $col => $key) {
				$worksheet2->write_string(0, $col, $key, $formatot);
			}//for
			
			
			$i = 1;
			foreach ($rows as $row) {
				
				$array['report_values'][$i][0]	= $row->created;
				$array['report_values'][$i][1]	= $row->first_name;
				$array['report_values'][$i][2]	= $row->last_name;
				$array['report_values'][$i][3]	= $row->email;
				$array['report_values'][$i][4]	= $row->phone;
				$array['report_values'][$i][5]	= $row->used_enewsletter?'Yes':'No';
				
				$moduleParams = new JRegistry();
				$moduleParams->loadString($row->video_selected);
				
				$video_selected = unserialize($row->video_selected);
				
				#echo '<pre>';
				//var_dump($row->video_selected);
				#print_r($video_selected);
				#exit;
				
				foreach ($array['report_values'][$i] as $col => $key) {
					$worksheet2->write($i, $col, $key);
				}//for
				
				if ( is_array($video_selected) && count($video_selected) ) {
					
					foreach($video_selected as $video_name){
						$col++;
						$worksheet2->write($i, $col, $video_name);
					}//for
				}//if
				
				$cusitems_selected = unserialize($row->cusitems_selected);
				if ( is_array($cusitems_selected) && count($cusitems_selected) ) {
					foreach($cusitems_selected as $video_name){
						$col++;
						$worksheet2->write($i, $col, $video_name);
					}//for
				}//if
				
				$i++;
				
			}//foreach
			
			$workbook->close();
			
			exit;
		
		}//if rows
		
		
		return;
		
	}
	
	function _HeaderingExcel($filename) {
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$filename" );
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
	}//func
	
	
}
