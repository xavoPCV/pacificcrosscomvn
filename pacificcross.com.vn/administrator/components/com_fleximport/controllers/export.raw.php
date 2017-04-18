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

require_once (JPATH_COMPONENT . '/classes/export.php');

class FleximportControllerExport extends JControllerLegacy {
	/* Fonction pour traiter l'export directement via une tache cron*/
	function cron()
	{
		// get variables
		$cparams = JComponentHelper::getParams('com_fleximport');
		if (!$cparams->get('allow_cron', 0)) die('Cron not Allowed.');
		// force le type
		$filters = array();
		$filters['params_type'] = JFactory::getApplication()->input->get('cronid',null,'int');
		$mainframe = JFactory::getApplication();
		$mainframe->setUserState('com_fleximport.export.params_type', $filters['params_type']);
		// si des filtres doivent �tre charg�s
		if ($fileFilter = JFactory::getApplication()->input->get('filter')) {
            JFactory::getApplication()->input->set('file', $fileFilter . '.ini');
			$this->openfilter();
		}
		if ($export = new fleximportExport($filters, 'cron')) {
			if ($export->getData())
				$export->exportData();
		}
		$export->sendLog();
		// call the __destruct
		$export = null;
		echo "Export done !";
		jexit();
	}
	function openfilter()
	{
		if ($filename = JFactory::getApplication()->input->get('file')) {
			jimport('joomla.filesystem.file');
			$pathtofilters = JPATH_COMPONENT . '/classes/filters/';
			if (!JFile::exists($pathtofilters . $filename)) {
				return false;
			} else {
				$mainframe = JFactory::getApplication();
				$contents = file_get_contents($pathtofilters . $filename);
				$filterParam = new JRegistry($contents);
				$filterList = array('params_type', 'params_record', 'params_export_attachment', 'filter_added', 'filter_modified', 'filter_noexported', 'filter_scope_include', 'filter_scope', 'filter_search', 'filter_state_include', 'filter_state', 'filter_maincats_include', 'filter_cats_include', 'filter_maincats', 'filter_cats', 'filter_authors_include', 'filter_authors', 'filter_date', 'filter_startdate', 'filter_enddate', 'filter_tags_include', 'filter_tags', 'filter_lang_include', 'filter_lang', 'filter_id_include', 'filter_id');
				foreach ($filterList as $filter) {
					$paramValue = $filterParam->get($filter);
					if (strpos($paramValue, '%%') !== false) {
						$paramValue = explode('%%', $paramValue);
					}
					$mainframe->setUserState('com_fleximport.export.' . $filter, $paramValue);
					JFactory::getApplication()->input->set($filter, $paramValue, 'POST');
				}
				return true;
			}
		} else {
			return true;
		}
	}
}