<?php
/**
* @version 1.3.0
* @package RSform!Pro 1.3.0
* @copyright (C) 2007-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * RSForm! Pro system plugin
 */
class plgSystemRSFPOfflinePayment extends JPlugin
{
	var $componentId 	= 499;
	var $componentValue = 'offlinepayment';
	
	/**
	 * Constructor
	 *
	 * For php4 compatibility we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->newComponents = array(499);
	}
	
	function rsfp_bk_onAfterShowComponents()
	{		
		$lang = JFactory::getLanguage();
		$lang->load( 'plg_system_rsfpofflinepayment' );
		
		$formId = JRequest::getInt('formId');
		
		$link = "displayTemplate('".$this->componentId."')";
		if ($components = RSFormProHelper::componentExists($formId, $this->componentId))
			$link = "displayTemplate('".$this->componentId."', '".$components[0]."')";
		
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('ul.rsform_leftnav li a span#offline { background: url(components/com_rsform/assets/images/icons/offline.png) no-repeat 10px center; }');
		
		?>
		<li><a href="javascript: void(0);" onclick="<?php echo $link;?>;return false;" id="rsfpc<?php echo $this->componentId; ?>"><span id="offline"><?php echo JText::_('RSFP_OFFLINE_PAYMENT'); ?></span></a></li>
		<?php
	}
	
	function rsfp_onAfterCreatePlaceholders($args)
	{
		$formId = $args['form']->FormId;
		
		if ($components = RSFormProHelper::componentExists($formId, $this->componentId)) {			
			$choose 	= RSFormProHelper::componentExists($formId, 27);
			$chooseData = RSFormProHelper::getComponentProperties($choose[0]);
			
			$wireDetails = '';
			// find the value
			$pos = array_search('{'.$chooseData['NAME'].':value}', $args['placeholders']);
			if ($pos !== false) {
				$payValue = $args['values'][$pos];
				
				if ($payValue == $this->componentValue) {
					$data = RSFormProHelper::getComponentProperties($components[0]);
					$wireDetails = $data['WIRE'];
				}
			}
			
			$args['placeholders'][] = '{offline}';
			$args['values'][] 		= $wireDetails;
		}
	}
	
	function rsfp_getPayment(&$items, $formId)
	{
		if ($components = RSFormProHelper::componentExists($formId, $this->componentId))
		{
			$data = RSFormProHelper::getComponentProperties($components[0]);
			
			$item 			= new stdClass();
			$item->value 	= $this->componentValue;
			$item->text 	= $data['LABEL'];
			
			// add to array
			$items[] = $item;
		}
	}
	
	function rsfp_doPayment($payValue, $formId, $SubmissionId, $price, $products, $code)
	{
		// this plugin does nothing
	}
	
	function rsfp_bk_onAfterCreateComponentPreview($args = array())
	{
		if ($args['ComponentTypeName'] == 'offlinePayment')
		{
			$args['out'] = '<td>&nbsp;</td>';
			$args['out'].= '<td><img src="'.JURI::root(true).'/administrator/components/com_rsform/assets/images/icons/offline.png" /> '.$args['data']['LABEL'].'</td>';	
		}
	}
}