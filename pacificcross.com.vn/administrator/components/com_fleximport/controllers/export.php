<?php
defined('_JEXEC') or die;
require_once (JPATH_COMPONENT . '/classes/export.php');
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
class FleximportControllerExport extends FlexImportController {
    /**
     * Constructor
     *
     * @since 1.0
     */
    function __construct()
    {
        parent::__construct();
        // Register Extra task
        $this->registerTask('all' , 'export');
        $this->registerTask('selection' , 'export');
    }
    /**
     * Logic to export data in a file
     *
     * @access public
     * @return void
     * @since 1.5
     */
    function export()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit('Invalid Token');
        $post = JFactory::getApplication()->input->post->getArray();
        // default return
        $msg = JText::_('COM_FLEXIMPORT_EXPORT_ERROR');
        $msgtype = 'error';
        $link = 'index.php?option=com_fleximport&view=logs';
        if ($export = new fleximportExport($post, JFactory::getApplication()->input->get('task','all'))) {
            if ($export->getData()) {
                if ($export->exportData()) {
                    $msg = JText::_('COM_FLEXIMPORT_EXPORT_DONE');
                    $msgtype = 'message';
                }
            }
        }
        $export->sendLog();
        // call the __destruct
        $export = null;
        if (!FLEXIMPORT_DEBUG)$this->setRedirect($link, $msg, $msgtype);
    }
	function savefilter()
	{
		JSession::checkToken() or jexit('Invalid Token');
		if (JFactory::getApplication()->input->get('filter_name')) {
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
			$pathtofilters = JPATH_COMPONENT .'/classes/filters';
			if (!JFolder::exists($pathtofilters)) JFolder::create($pathtofilters);
			if (!JFolder::exists($pathtofilters)) {
				JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_NOPATH_FILTER'), 'error');
			} else {
				$mainframe = JFactory::getApplication();
				$filterList = array('params_type', 'params_record', 'params_export_attachment', 'filter_added', 'filter_modified', 'filter_addedormodified','filter_noexported', 'filter_scope_include', 'filter_scope', 'filter_search', 'filter_state_include', 'filter_state', 'filter_maincats_include', 'filter_cats_include', 'filter_maincats', 'filter_cats', 'filter_authors_include', 'filter_authors', 'filter_date', 'filter_startdate', 'filter_enddate', 'filter_tags_include', 'filter_tags', 'filter_lang_include', 'filter_lang', 'filter_id_include', 'filter_id');
				$filterParam = new JRegistry();
				foreach ($filterList as $filter) {
					$paramValue = $mainframe->getUserState('com_fleximport.export.' . $filter,'');
					if (is_array($paramValue)) {
						$paramValue = implode("%%", $paramValue);
					}
					$filterParam->set($filter, $paramValue);
				}
				$filename = JFile::makeSafe(JFactory::getApplication()->input->get('filter_name'));
				$fileToWrite = $pathtofilters . '/' . $filename . '.ini';
				$messageToWrite = $filterParam->toString();
				if (JFile::write($fileToWrite, $messageToWrite)) {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_RECORD_FILTER_OK'), 'message');
                    JFactory::getApplication()->input->set('layout', 'restorefilter');
				} else {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_RECORD_FILTER_ERROR'), 'error');
				}
			}
		} else {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_NO_FILENAME'), 'error');
		}
		parent::display();
	}
	function openfilter()
	{
        $input = JFactory::getApplication()->input;
        $input->set('task', 'export.filter');
        $input->set('layout', 'default');
        $input->set('tmpl', 'index');
		if ($filename = JFactory::getApplication()->input->get('file')) {
			jimport('joomla.filesystem.file');
			$pathtofilters = JPATH_COMPONENT . '/classes/filters/';
			if (!JFile::exists($pathtofilters . $filename)) {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_FILTER_NO_EXIST'), 'error');
			} else {
				$mainframe = JFactory::getApplication();
				$contents = file_get_contents($pathtofilters . $filename);
				$filterParam = new JRegistry($contents);
				$filterList = array('params_type', 'params_record', 'params_export_attachment', 'filter_added', 'filter_modified', 'filter_addedormodified','filter_noexported', 'filter_scope_include', 'filter_scope', 'filter_search', 'filter_state_include', 'filter_state', 'filter_maincats_include', 'filter_cats_include', 'filter_maincats', 'filter_cats', 'filter_authors_include', 'filter_authors', 'filter_date', 'filter_startdate', 'filter_enddate', 'filter_tags_include', 'filter_tags', 'filter_lang_include', 'filter_lang', 'filter_id_include', 'filter_id');
				foreach ($filterList as $filter) {
					$paramValue = $filterParam->get($filter);
					if (strpos($paramValue, '%%') !== false) {
						$paramValue = explode('%%', $paramValue);
					}
					$mainframe->setUserState('com_fleximport.export.' . $filter, $paramValue);
					$input->post->set($filter, $paramValue);
				}
				JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_OPEN_FILTER_OK'), 'message');
			}
		} else {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_NO_FILENAME'), 'error');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_fleximport&view=export',false));
	}
	function deletefilter()
	{
        JFactory::getApplication()->input->set('layout', 'restorefilter');
		if ($filename = JFactory::getApplication()->input->get('file')) {
			jimport('joomla.filesystem.file');
			$pathtofilters = JPATH_COMPONENT . '/classes/filters/';
			if (!JFile::exists($pathtofilters . $filename)) {
				JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_FILTER_NO_EXIST'), 'error');
			} else {
				$mainframe = JFactory::getApplication();
				if (JFile::delete($pathtofilters . $filename)) {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_DELETE_FILTER_OK'), 'message');
				} else {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_DELETE_FILTER_ERROR'), 'error');
				}
			}
		} else {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_FLEXIMPORT_EXPORT_NO_FILENAME'), 'error');
		}
		parent::display();
	}
}