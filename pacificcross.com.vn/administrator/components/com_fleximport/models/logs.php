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
// no direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport( 'joomla.application.component.modellist');

/**
 * flexIMPORT Component logs Model
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportModelLogs extends JModelList {
	private $items = null;
	private $total = null;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to get fields data
	 *
	 * @access public
	 * @return array
	 */
	public function getItems()
	{
		// Lets load the fields if it doesn't already exist
		$filter_type = $type_plugin = $this->getState('filter.type');
		$query = $this->_db->getQuery(true);
		$query->select('id,name')->from('#__fleximport_types');
		if ($filter_type)
			$query->where('id=' . (int)$filter_type);

		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		$logsPath = array();
		foreach ($result as $type) {
			$typeInfo[$type->id] = $type->name;
			$logsPath[] = $this->getTypePath($type->id);
		}
		$logsPath = array_unique($logsPath);
		$logsFile = array();
		foreach ($logsPath as $logPath) {
			if (JFolder::exists(JPATH_SITE . '/' . $logPath)) {
				$logsFile = array_merge($logsFile, JFolder::files(JPATH_SITE . '/' . $logPath, ".log"));
			}
		}

		$tempLogFile = array();
		foreach ($logsFile as $logFile) {
			$dataFile = explode("_", $logFile);
			// elimine les fichiers logs incorrects
			if (($dataFile[1] == $filter_type || !$filter_type) && count($dataFile) == 3 && array_key_exists($dataFile[1], $typeInfo)) {
				$tempLogFile[] = $logFile;
			}
		}
		$logsFile = $tempLogFile;
		if (is_array($logsFile)) {
			// classe les logs par date
			$logsFile = array_unique($logsFile);
			sort ($logsFile);
			$logsFile = array_reverse($logsFile);
			$this->total = count($logsFile);
			$datasFile=array();
			if ($this->getState('list.limit')) {
				for ($i = $this->getState('list.start') ; ($i < ($this->getState('list.limit') + $this->getState('list.start'))) && ($i < $this->total);$i++) {
					$datasFile[] = $logsFile[$i];
				}
			} else {
				$datasFile = $logsFile;
			}
			if (count($datasFile)) {
				foreach ($datasFile as $dataFile) {
					// date , type , method
					$file = new stdClass();
					$file->filepath = urlencode($dataFile);
					$dataFile = explode("_", $dataFile);
					$file->date = substr($dataFile[0], 6, 2) . "/" . substr($dataFile[0], 4, 2) . "/" . substr($dataFile[0], 0, 4) . " " . substr($dataFile[0], 8, 2) . ":" . substr($dataFile[0], 10, 2) . ":" . substr($dataFile[0], 12, 2);
					$file->type = $dataFile[1];
					$file->type_name = $typeInfo[$dataFile[1]];
					$file->method = substr($dataFile[2], 0, strlen($dataFile[2]) - 4);
					$this->items[] = $file;
				}
			}
		}
		return $this->items;
	}

	public function getTotal(){
		if (!$this->total) {
			$this->getItems();
		}
		return $this->total;
	}
	public function getTypePath($typeID = 0)
	{
		if (!$typeID) return;
		$query = $this->_db->getQuery(true);
		$query->select('params')->from('#__fleximport_types')->where('id='.(int)$typeID);
		$this->_db->setQuery($query);
		$params = new JRegistry($this->_db->loadResult());
		$typePath = $params->get("log_path", "images/fleximport/log/");
		return $typePath;
	}

	protected function populateState($ordering = '', $direction = '')
	{
		$filter_type = $this->getUserStateFromRequest($this->context . 'filter.type', 'filter_type');
		$this->setState('filter.type', $filter_type);
		parent::populateState($ordering, $direction);
	}
}