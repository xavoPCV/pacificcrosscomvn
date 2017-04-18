<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Managetemplate model.
 *
 */
class EnewsletterModelManagetemplate extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 */ 
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	

	/**
	 * Get all email templates
	 *
	 * @return  array  An array of email templates
	 *
	 */
		public function getAlltemplates($emailtype = ''){
      
			$db = JFactory::getDBO();
      if($emailtype){
          $query = "SELECT * from #__email_templates where status = 'published' and type = '$emailtype' order by id";
      }else{
			   $query = "SELECT * from #__email_templates order by id desc";
      }
			$db->setQuery($query);  
			$emails = $db->loadObjectList();
      return $emails;
     }
     
  /**
	 * save email template
	 * 
	 * @param array $data  email template details
	 * 
	 * @return int $id id of saved email template           
	 *
	 */
		public function saveTemplate($data = array()){
			$db = JFactory::getDBO();
			$insertquery = "INSERT INTO #__email_templates (id,description,filename,type,status) 
				   VALUES('','".$data['description']."','','".$data['type']."','".$data['status']."')";
      $db->setQuery($insertquery);
  		$result =  $db->query();  
  		$id = $db->insertid(); 
      return $id;
     }
     
  /**
	 * update email template filename after creating template file
	 * 
	 * @param int $id id of saved email template
	 * @param string $filename name of template file   
	 * 
	 * @return void      
	 *
	 */
		public function updateTemplate($id = null,$filename=''){
			$db = JFactory::getDBO();
			$updatequery = "UPDATE #__email_templates set filename  = '".$filename."' where id = ".$id."";	
			$db->setQuery($updatequery);
			$result =  $db->query();
     }
     
  /**
	 * update email template data
	 * 
	 * @param array $data template details 
	 * 
	 * @return int id of template      
	 *
	 */
		public function editTemplate($data = array()){
			$db = JFactory::getDBO();
			$updatequery = "UPDATE #__email_templates set description  = ".$db->quote($data['description']).",status  = '".$data['status']."'  where id = ".$data['id']."";	
			$db->setQuery($updatequery);
			$result =  $db->query();
      return $data['id'];
     }
     
  /**
	 * remove email template data
	 * 
	 * @param int $id id of template 
	 * 
	 * @return boolean    
	 *
	 */
		public function removeTemplate($id=null){
			$db = JFactory::getDBO();
      
      $query = "SELECT * from #__email_templates where id = $id";
			$db->setQuery($query);  
			$emails = $db->loadObjectList();
      unlink($filename = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$emails[0]->filename);
      
			$deletegroupquery = "DELETE FROM #__email_templates where id = $id";    	
			$db->setQuery($deletegroupquery);
			$result =  $db->query();
      return $result;
     }  
	
}
