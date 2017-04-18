<?php
class CtaViewReport extends JViewLegacy {
	/**
	 * Display the view
	 */
	function display($tpl = null){
	
		$layout = JRequest::getVar('layout');
		
		$this->params = JComponentHelper::getParams('com_cta');
		
		if ($layout=='video') {
		
			JRequest::setVar('tmpl', 'component');
			$this->setLayout($layout);
		} else {
			$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
			
			$this->item = $ctaModel->getSetting();
			$this->videos = $ctaModel->getVideos();
			
			JToolBarHelper::title(JText::_('Report'));
			JToolBarHelper::apply('report.save');
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_cta');
		}//if
				
		parent::display($tpl);
	}
	
}//class