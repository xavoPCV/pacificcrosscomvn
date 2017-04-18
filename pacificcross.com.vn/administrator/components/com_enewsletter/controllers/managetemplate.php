<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Managetemplate controller class.
 */
class EnewsletterControllerManagetemplate extends JControllerForm
{

  /**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 */
	public function getModel($name = 'Managetemplate', $prefix = 'EnewsletterModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Used to remove template
	 *    
	 * @return void
	 */
	public function remove()
	{
		$app = JFactory::getApplication();
    
    // Get all data of current page		
		$data = JRequest::getVar('jform', array(), 'post', 'array');
    
    // Get and set managetemplate mdoel
    $model = $this->getModel();
    
    // Remove template 
    $result = $model->removeTemplate($data['id']);
    
    if($result == 1){
        $this->setMessage(JText::_('Template removed successfully.'));
    }else{
        $this->setMessage(JText::_('Template not removed.'));
    }
	  $this->setRedirect('index.php?option=com_enewsletter&view=managetemplate');
	}
  	
}
