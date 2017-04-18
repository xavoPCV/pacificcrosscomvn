<?php
defined('_JEXEC') or die;

class EnewsletterViewWeeklyupdatearchive extends JViewLegacy {

	public function display($tpl = null) {
	
		$app = JFactory::getApplication();
		
		$this->params = $app->getParams();

		$model = $this->getModel();
		
		$model->makescreens();
		
		$this->rows = $model->getItems();
		
		
	
		$this->document->addStyleSheet($this->baseurl.'/components/com_enewsletter/assets/style.css');
		
		parent::display($tpl);
		
		
	}//func

}
