<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Contact Component Controller
 *
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @since 1.5
 */
class CtaController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{

		// Set the default view name and format from the Request.
		$vName		= JRequest::getCmd('view', 'form');
		
		JRequest::setVar('view', $vName);

		parent::display();

		return $this;
	}
}
