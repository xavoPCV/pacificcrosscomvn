<?php
/**
 * @version		$Id: helper.php 20541 2012-07-12 21:12:06Z $
 * @package		Joomla.Site
 * @subpackage	mod_fnm
 * @copyright	Copyright (C) 2012 Pratham Software Inc., All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

abstract class modApicontentCatCloudHelper {
	
	public function getCategories(&$moduleparams, $category) {
		
		$selection_method = $moduleparams->get('selection_method', 'fnc');
		
		$word_count = $moduleparams->get('word_count', 10);


		$db = JFactory::getDBO();
		
		
		
		
		switch ($selection_method){
		case 'fnc':
			$sql = "select category, count(category) count_c from #__apifnc where published = 1 group by `category`";
			break;
		case 'fbc':
			$sql = "select category, count(category) count_c from #__apifbc where published = 1 group by `category`";
			break;
		case 'both':
			
			$sql = "SELECT category, count(category) AS count_c
					FROM
					(
						SELECT category FROM #__apifnc
						UNION ALL
						SELECT category FROM #__apifbc
					) AS UnionTable
					GROUP BY category";
			
			
			break;
		}//switch
		
		
		$db->setQuery($sql);
		
		$arr = $db->loadAssocList('category','count_c');
		
		
		#echo str_replace('#_','plat',$db->getQuery());
		//echo str_replace('#_','plat',$query);
		
		$cats = array();
		
		
		$minimum_count	= 0;
		$maximum_count	= 0;
		$min_size = 10;
		$max_size = 30;

		
		foreach ($category as $cat_id => $cat_name) {
			$cat_o = new stdClass();
			$cat_o->id = $cat_id;
			$cat_o->name = $cat_name;
			$count_c = 0;
			if (array_key_exists($cat_id, $arr)) $count_c = $arr[$cat_id];
			$cat_o->count_c = $count_c;
			$cats[$cat_id] = $cat_o;
			
			
			$minimum_count	= $minimum_count > $count_c?$count_c:$minimum_count;
			$maximum_count	= $maximum_count > $count_c?$maximum_count:$count_c;
			
		}//for
		
		$spread = $maximum_count - $minimum_count;

		if ($spread == 0) {
			$spread = 1;
		}//if
		
		
		//for($i = 0; $i < count($cats); $i++) {
		foreach ($cats as &$row) {
			$size = $min_size + ($row->count_c - $minimum_count) * ($max_size - $min_size) / $spread;
			$row->fontsize  = $size;
		}//for

		/*echo '<pre>';
		print_r($cats);
		exit;*/
		
		return $cats;
	
	}//func
	
	
	
}//class
