<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';
jimport('joomla.application.categories');

abstract class modFAQCategoriesHelper
{
	public static function getList(&$params)
	{
		/*$categories = JCategories::getInstance('Content');
		$category = $categories->get($params->get('parent', 'root'));

		if ($category != null)
		{
			$items = $category->getChildren();
			if($params->get('count', 0) > 0 && count($items) > $params->get('count', 0))
			{
				$items = array_slice($items, 0, $params->get('count', 0));
			}
			return $items;
		}*/
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__categories');
		$query->where('published = 1 && extension='.$db->quote('com_faq.faqs.faq_category_id'));
		
		$query->where('id IN (select distinct `faq_category_id` from #__faq_faqs where 1 group by `faq_category_id`)');
		
		 $lang = JFactory::getLanguage();
		if ($lang->getTag() == 'vi-VN' ) {
				$query->where(' language = "'.$lang->getTag().'" ') ;
		
		}else {
		
			$query->where(' language <> "vi-VN" ') ;
		}
	
		
		
		$db->setQuery($query);
		
		//echo $query->dump();
		
		$items = $db->loadObjectList();
		return $items;
	}

}
