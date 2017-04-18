<?php
/**
 * @version 2.0
 * @package Joomla
 * @subpackage flexIMPORT
 * @copyright (C) 2011 NetAssoPro - www.netassopro.com
 * @license GNU/GPL v2
 *
 * flexIMPORT is a addon for the excellent FLEXIcontent component. Some part of
 * code is directly inspired.
 * @copyright (C) 2009 Emmanuel Danan
 * see www.vistamedia.fr for more information
 *
 * flexIMPORT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.controlleradmin');

class FleximportControllerFields extends JControllerAdmin
{
	public function getModel($name = 'Field', $prefix = 'FlexImportModel',$config=array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true),$config);
		return $model;
	}
	function copy()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );

		$cid = JFactory::getApplication()->input->get('cid',array(0),'array');

		$model = $this->getModel('fields');

		if(!$model->copy( $cid )) {
			throw new Exception(JText::_('COM_FLEXIMPORT_FIELDS_COPY_FAILED'), 400);
		} else {
			$msg = JText::_('COM_FLEXIMPORT_FIELDS_COPY_SUCCESS');
			$cache = JFactory::getCache('com_fleximport');
			$cache->clean();
		}
		$this->setRedirect('index.php?option=com_fleximport&view=fields', $msg );
	}
}