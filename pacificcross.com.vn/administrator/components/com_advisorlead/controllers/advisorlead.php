<?php
/*------------------------------------------------------------------------
# advisorlead.php - Advisor Lead Component
# ------------------------------------------------------------------------
# author    Vu Nguyen
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   iexodus.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Advisorlead Controller
 */
class AdvisorleadControlleradvisorlead extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'advisorlea', $prefix = 'AdvisorleadModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}
}
?>