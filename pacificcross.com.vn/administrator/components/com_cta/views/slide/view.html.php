<?php
class CtaViewSlide extends JViewLegacy {
	/**
	 * Display the view
	 */
	function display($tpl = null){
	
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		
		$this->item = $ctaModel->getSetting();
		$this->videos = $ctaModel->getVideos($this->item->selected_video);
		
		/*echo '<pre>';
		print_r($this->videos);
		exit;*/
		
		JToolBarHelper::title(JText::_('Calls to Action'));
		JToolBarHelper::apply('slide.save');
		
		parent::display($tpl);
	}
	
}//class