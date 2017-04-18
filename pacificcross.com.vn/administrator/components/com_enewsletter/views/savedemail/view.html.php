<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
 
/**
 * Savedemail view class
 */
class EnewsletterViewSavedemail extends JView
{

    // Overwriting JView display method
    function display($tpl = null) 
    {
			
		$this->item		= $this->get('Emails');
		
		#HT - Compliance Status
		$this->doCompliance = false;
		if ($this->item	 && file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php')) {
		
			$this->doCompliance = true;
			
			JLoader::register('ContentManagerHelper', JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
			$site_id = ContentManagerHelper::getSiteID();
			
			if ($site_id) {
			
				$params = JComponentHelper::getParams('com_contentmanager');
				//centcom process
		
				$option = array(); //prevent problems
				$option['driver']   = $params->get('centcom_driver');            // Database driver name
				$option['host']     = $params->get('centcom_host');    // Database host name
				$option['user']     = $params->get('centcom_user');       // User for database authentication
				$option['password'] = $params->get('centcom_password');   // Password for database authentication
				$option['database'] = $params->get('centcom_database');      // Database name
				$option['prefix']   = $params->get('centcom_prefix');             // Database prefix (may be empty)
				$db_ex = & JDatabase::getInstance( $option );
			
				$query = $db_ex->getQuery(true);
				$query->select('*');
				$query->from('#__content_client');
				$query->where('site_id = '.$site_id);
				$query->where('`element` IN ("enewsletter")');
				$db_ex->setQuery($query);
				//cho $query->dump();
				$rows_ex = $db_ex->loadAssocList('object_id');
				//echo '<pre>';
				//print_r($rows_ex);
				//var_dump($rows);
				
				foreach ($this->item as &$row) {
					
					//echo $row->mailid;
					
					if (array_key_exists($row->id, $rows_ex)) {
						$row_ex = $rows_ex[$row->id];
						$row->date_submit = $row_ex['date_submit'];
						$row->date_approved = $row_ex['date_approved'];
						$row->status_approved = $row_ex['status'];
						
						//print_r($row);
						
					}//if
				}//for
			}//if site_id
		}//if items
		#HT - Compliance Status End
			
			
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
		
		parent::display($tpl);
		$this->addToolbar();
    }
		
		/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$app = JFactory::getApplication();		
		$this->complianceflag = $app->getUserState("com_enewsletter.complianceflag");
		
		JToolBarHelper::title(JText::_('Saved Email List'));
		JToolBarHelper::deleteList('', 'savedemail.adelete');
		JToolBarHelper::divider();
		JToolBarHelper::cancel('savedemail.cancel');
		JToolBarHelper::divider();
    	JToolBarHelper::back('Menu','index.php?option=com_enewsletter');
		JToolBarHelper::divider();
		JToolBarHelper::help('../savedemail.html', true);
	}
}