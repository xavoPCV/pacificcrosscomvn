<?php
// No direct access
defined('_JEXEC') or die;

class CtaHelper {

	public static function addSubmenu($vName = 'report') {
	
		JSubMenuHelper::addEntry(
			JText::_('Reports'),
			'index.php?option=com_cta&view=report',
			$vName == 'report'
		);
		JSubMenuHelper::addEntry(
			JText::_('Calls to Action'),
			'index.php?option=com_cta&view=slide',
			$vName == 'slide'
		);
		JSubMenuHelper::addEntry(
			JText::_('Social'),
			'index.php?option=com_cta&view=subscription',
			$vName == 'subscription'
		);
		JSubMenuHelper::addEntry(
			JText::_('Branding'),
			'index.php?option=com_cta&view=branding',
			$vName == 'branding'
		);
		JSubMenuHelper::addEntry(
			JText::_('Leads'),
			'index.php?option=com_cta&view=leads',
			$vName == 'leads'
		);
		JSubMenuHelper::addEntry(
			JText::_('Custom Items'),
			'index.php?option=com_cta&view=cusitems',
			$vName == 'cusitems'
		);
	}//func
}//class