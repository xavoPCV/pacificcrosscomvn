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
jimport('joomla.filesystem.file');
/**
 * View class for the flexIMPORT logs screen
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportViewLogs extends JViewLegacy {
    function display($tpl = null)
    {
        $cid = JFactory::getApplication()->input->get('cid',array(0),'array');
        if (!is_array($cid) || count($cid) < 1) {
            $msg = '';
        	throw new Exception(JText::_('COM_FLEXIMPORT_SELECT_ITEM_PREVIEW'), 500);
        } else {
        	$model = $this->getModel();
            foreach ($cid as $file) {
                $file = urldecode($file);
                $dataFile = explode("_", $file);
                $logPath = $model->getTypePath($dataFile[1]);
                if (JFile::exists(JPATH_SITE . '/' . $logPath . $file)) {
                    $logs = file_get_contents(JPATH_SITE . '/' . $logPath . $file);
                    $logs = nl2br($logs);
                    echo $logs;
                }
            }
        }
        jexit();
    }
}