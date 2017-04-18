<?php
/**
 *
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
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * flexIMPORT Component Logs Controller
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportControllerPlugins extends FlexImportController {
    /**
     * Constructor
     *
     * @since 1.0
     */
    function __construct()
    {
        parent::__construct();
    }
    public function delete()
    {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
        // Get items to remove from the request.
        $cid = JFactory::getApplication()->input->get('cid',array(),'array');

        if (!is_array($cid) || count($cid) < 1) {
        	throw new Exception(JText::_('COM_FLEXIMPORT_NO_ITEM_SELECTED'), 400);
        }else {
            // Get the model.
            $model = $this->getModel('plugins');
            // Remove the items.
            if ($model->delete($cid)) {
                $this->setMessage(JText::plural('COM_FLEXIMPORT_N_ITEMS_DELETED', count($cid)));
            }else {
                $this->setMessage($model->getError(), 'error');
            }
        }

        $this->setRedirect(JRoute::_('index.php?option=com_fleximport&view=plugins', false));
    }
    function installUpgrade()
    {
        // Check token
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
        // Get the model.
        $model = $this->getModel('plugins');
        // Remove the items.
        if ($model->installUpgrade($cid)) {
            $this->setMessage(JText::_('COM_FLEXIMPORT_PLUGINS_INSTALLED'));
        }else {
            $this->setMessage($model->getError(), 'error');
        }
        $this->setRedirect(JRoute::_('index.php?option=com_fleximport&view=plugins', false));
    }
}