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

defined('_JEXEC') or die;

class FleximportControllerLogs extends FleximportController {

    /**
     * Logic to delete types
     *
     * @access public
     * @return void
     * @since 1.5
     */
    function delete()
    {
    	jimport('joomla.filesystem.file');
        $cid = JFactory::getApplication()->input->get('cid',array(0),'array');
        if (!is_array($cid) || count($cid) < 1) {
            $msg = '';
        	throw new Exception(JText::_('COM_FLEXIMPORT_SELECT_ITEM_DELETE'), 400);
        } else {
        	$model 	= $this->getModel('logs');
            foreach ($cid as $file) {
            	$file = urldecode($file);
            	$dataFile = explode("_", $file);
            	$logPath = $model->getTypePath($dataFile[1]);
            	if (JFile::exists(JPATH_SITE . '/' . $logPath . $file)) {
            		Jfile::delete(JPATH_SITE . '/' . $logPath . $file);
            	}
            }
            $msg = count($cid) . ' ' . JText::_('COM_FLEXIMPORT_LOGS_DELETED');
        }

        $this->setRedirect('index.php?option=com_fleximport&view=logs', $msg);
    }
}