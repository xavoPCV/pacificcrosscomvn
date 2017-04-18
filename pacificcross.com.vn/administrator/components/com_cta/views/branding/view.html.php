<?php
class CtaViewBranding extends JViewLegacy {
	/**
	 * Display the view
	 */
	function display($tpl = null){
	
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		
		$this->item = $ctaModel->getSetting();
		
		JToolBarHelper::title(JText::_('Branding'));
		JToolBarHelper::apply('branding.save');
		
		parent::display($tpl);
	}
	
}//class