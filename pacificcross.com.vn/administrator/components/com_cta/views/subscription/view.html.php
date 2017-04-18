<?php
class CtaViewSubscription extends JViewLegacy {
	/**
	 * Display the view
	 */
	function display($tpl = null){
	
		$ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));
		
		$this->item = $ctaModel->getSetting();
		
		JToolBarHelper::title(JText::_('Social'));
		JToolBarHelper::apply('subscription.save');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_cta');
		
		parent::display($tpl);
	}
	
}//class