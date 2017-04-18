<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Weeklyupdate view class 
 */
class EnewsletterViewWeeklyupdate extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
		
        // Overwriting JView display method
        function display($tpl = null) 
        {
		
			// Include the component HTML helpers.
			JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
		
			$app = JFactory::getApplication();
			$this->api = $app->getUserState("com_enewsletter.API");
			$this->subject = $app->getUserState("com_enewsletter.Weekly_subject");
			$this->weekly_intro = $app->getUserState("com_enewsletter.Weekly_intro");
	
			if($this->api == 'C'){
			
				$this->setLayout( 'constantcontactweeklyupdate' );
				
			}else if($this->api == 'M'){
				
				$this->setLayout( 'mailchimpweeklyupdate' );
			}else if($this->api == 'G'){
				
				$this->setLayout( 'getresponseweeklyupdate' );
			}else if($this->api == 'I'){
				$this->setLayout( 'infusionsoft' );	
			}else{
				
				$app->enqueueMessage(JText::_('You are not allowed to create weekly update. First Fill Setup details.'),'warning');
        		$app->redirect('index.php?option=com_enewsletter&view=advisorsetting');
			}
			
			#HT - Compliance
			$this->doCompliance = file_exists(JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
			
			if ($this->doCompliance) {
				require_once(JPATH_ADMINISTRATOR.'/components/com_contentmanager/classes/screenmaster/function.php');
				JLoader::register('ContentManagerHelper', JPATH_ADMINISTRATOR.'/components/com_contentmanager/helpers/contentmanager.php');
				$site_id = ContentManagerHelper::getSiteID();
				$this->doComplianceConfig = ContentManagerHelper::getSiteConfig($site_id);
				$this->doCompliance = $this->doComplianceConfig->enewsletter_compliance;
			}//if

			$this->addToolbar();
			parent::display($tpl);
        }
		
	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		$app = JFactory::getApplication();		
		$data=$app->getUserState("com_enewsletter.data");
		$complianceflag = $app->getUserState("com_enewsletter.complianceflag");
		
		JToolBarHelper::title(JText::_('Weekly Update'));
    
    	if(isset($data['id']) && $data['id'] != ''){
			$this->id = $data['id'];
			$this->email_id = $data['email_id'];
			if (!$this->doCompliance) {
				JToolBarHelper::custom('weeklyupdate.apply', 'apply.png', '', JText::_('Save Email'), false);
	      		JToolBarHelper::divider();
    	  		JToolBarHelper::custom('weeklyupdate.saveascopy', 'save.png', '', JText::_('Save as Copy'), false);
			}//if
		}else{
			if (!$this->doCompliance) JToolBarHelper::custom('weeklyupdate.apply', 'apply.png', '', JText::_('Save Email'), false);
		}
		
		
		if (!$this->doCompliance) {
    		JToolBarHelper::divider();
	   		JToolBarHelper::custom('weeklyupdate.saveandnew', 'save-new.png', '', JText::_('Save & New'), false);
		}//if
		
		
		#HT - Compliance
		if ($this->doCompliance) {
			if ($data['approval_status'] != 'APR') {
				JToolBarHelper::custom('weeklyupdate.apply', 'apply.png', '', JText::_('Submit to Compliance'), false);
				//JToolBarHelper::divider();
			}//if
			
			
			if ($data['approval_status']=='APR') {
				JToolBarHelper::custom('weeklyupdate.send', 'send_email.png', '', JText::_('Send Email'), false);
			}//if
		}//if
		
		JToolBarHelper::divider();
		JToolBarHelper::custom('weeklyupdate.preview', 'preview.png', '', JText::_('Preview Email'), false);
		JToolBarHelper::divider();
		JToolBarHelper::custom('weeklyupdate.test', 'test_email.png', '', JText::_('Test Email'), false);
		JToolBarHelper::divider();
		if($complianceflag == 1){
			//no .net Compliance
			//JToolBarHelper::custom('weeklyupdate.sendtocompliance', 'send_email.png', '', JText::_('Submit to Compliance'), false);
		}else{
			if (!$this->doCompliance) JToolBarHelper::custom('weeklyupdate.send', 'send_email.png', '', JText::_('Send Email'), false);
		}
		JToolBarHelper::divider();
		JToolBarHelper::cancel('weeklyupdate.cancel');
		JToolBarHelper::divider();
    	JToolBarHelper::back('Menu','index.php?option=com_enewsletter');
		JToolBarHelper::divider();
		JToolBarHelper::help('../fans_update_email.html', true);
	}
}