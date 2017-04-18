<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the helper functions only once
require_once dirname(__FILE__).'/helper.php';

$list = modFAQCategoriesHelper::getList($params);
//var_dump($list);die;
if (!empty($list)) {
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	//$startLevel = reset($list)->getParent()->level;
	//var_dump($startLevel);
	require JModuleHelper::getLayoutPath('mod_faq_categories', $params->get('layout', 'default'));
}
