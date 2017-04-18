<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Banner model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.6
 */
class CtaModelLead extends JModelAdmin
{
	
	
	public function getTable($type = 'Lead', $prefix = 'CtaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	
	public function getForm($data = array(), $loadData = true)
	{
		

		return ;
	}
	
	
	
}//class