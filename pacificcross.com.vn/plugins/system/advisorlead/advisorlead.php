<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
/**
 * Joomla Authentication plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Authentication.joomla
 * @since 1.5
 */
class plgSystemAdvisorlead extends JPlugin {

	function onBeforeRender() {

		
		$app = JFactory::getApplication();
		
		if ( $app->isAdmin() ) return;
		
		
		$input = $app->input;
		$view = $input->get('view');
		
		
		
		if ($view != 'login') {

			require_once (JPATH_SITE . DS . 'components' . DS . 'com_advisorlead' . DS . 'init_variable.php');
			
			JLoader::import('templates', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'models');
		
			$article_id = $input->get('id');
			
			//var_dump($article_id);
			//exit;
		
			JLoader::import('pages', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'models');
		
			$model = JModelLegacy::getInstance('pages', 'AdvisorleadModel');
		
			$page = $article_id != 0 ? $model->get_page('', '', '', $article_id) : '';
			
			//var_dump($page);
			
			
			if (!empty($page)) {
				echo $model->load_page($page);
				$app->close();
			}//if
			
		}//if

		
	}//func
	

}