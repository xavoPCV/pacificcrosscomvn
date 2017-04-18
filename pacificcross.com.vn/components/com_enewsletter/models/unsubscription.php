<?php
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

/**
 * Unsubscription model class.
 */
class EnewsletterModelUnsubscription extends JModel
{
    
	/**
    * Gets the greeting
    * @return string The greeting to be displayed to the user
    */
    function getDetails()
    {
       // Get a db connection.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = 'Select * from #__advisorsettings order by id desc limit 1';
		$db->setQuery($query);
		$advisordetails = $db->loadObjectList();
		return $advisordetails[0];
    }
	
}