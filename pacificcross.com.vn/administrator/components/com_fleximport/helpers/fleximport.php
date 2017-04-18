<?php
defined('_JEXEC') or die;
class FleximportHelper {
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu)
	{

		$user = JFactory::getUser();

		JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_HOME'), 'index.php?option=com_fleximport', $submenu == 'dashboard');

		if ($user->authorise('core.edit', 'com_fleximport') || $user->authorise('core.create', 'com_flexistore') || $user->authorise('core.delete', 'com_flexistore'))
			JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_TYPES'), 'index.php?option=com_fleximport&view=types', $submenu == 'types');

		if ($user->authorise('core.edit', 'com_fleximport') || $user->authorise('core.create', 'com_flexistore') || $user->authorise('core.delete', 'com_flexistore'))
			JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_FIELDS'), 'index.php?option=com_fleximport&view=fields', $submenu == 'fields');

		if ($user->authorise('fleximport.import', 'com_fleximport'))
			JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_IMPORT'), 'index.php?option=com_fleximport&view=import', $submenu == 'import');

		if ($user->authorise('fleximport.export', 'com_fleximport'))
			JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_EXPORT'), 'index.php?option=com_fleximport&view=export', $submenu == 'export');
			if ($user->authorise('fleximport.export', 'com_fleximport') || $user->authorise('fleximport.import', 'com_fleximport')) {
				JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_LOGS'), 'index.php?option=com_fleximport&view=logs', $submenu == 'logs');
			}
		if ($user->authorise('core.admin', 'com_fleximport'))
			JSubMenuHelper::addEntry(JText::_('COM_FLEXIMPORT_PLUGINS'), 'index.php?option=com_fleximport&view=plugins', $submenu == 'plugins');

	}

	public static function getFlexiFields($type = null){
		if (!$type) return false;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT f.id,f.field_type,f.label,f.name,f.description,f.iscore,f.ordering');
		$query->from('#__flexicontent_fields AS f');
		$query->join('left','#__flexicontent_fields_type_relations AS r ON f.id = r.field_id');
		$query->where('f.published = 1');
		$query->where('f.field_type NOT IN ("'.implode('","',$GLOBALS['fi_fields_nocopy']).'")');
		$query->where('r.type_id = ' .(int)$type);
		$query->order('f.ordering');
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public static function loadFlexiContent(){

		require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.helper.php');
		require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.categories.php');
		require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.fields.php');
		require_once(JPATH_COMPONENT . '/helpers/fleximport.php');

		// Set the table directory
		JTable::addIncludePath(JPATH_COMPONENT . '/tables');
		JTable::addIncludePath(JPATH_BASE . '/components/com_flexicontent/tables');
		// Import the field plugins

		JPluginHelper::importPlugin('flexicontent_fields');
		$jlang = JFactory::getLanguage();
		$jlang->load('com_content' , JPATH_ADMINISTRATOR);
		$jlang->load('com_flexicontent' , JPATH_ADMINISTRATOR);

		// Set filepath
		$params = JComponentHelper::getParams('com_flexicontent');

		require_once (JPATH_ADMINISTRATOR . '/components/com_flexicontent/defineconstants.php');
		require_once (JPATH_SITE . '/components/com_flexicontent/helpers/permission.php');
	}

	public static function JSONReturn($text = "")
	{
		$session = JFactory::getSession();
		$session->set("jsonReturn", $session->get("jsonReturn", '', "fleximport_response") . $text, "fleximport_response");
	}
	public static function AjaxReload($reload = true, $method = null)
	{
		$session = JFactory::getSession();
		$activeStep = $session->get("step", 0, "fleximport");
		$session->set("step" . $activeStep . "First", true, "fleximport");
		$errors = ob_get_contents();
		ob_end_clean();
		// si c'est une tache cron
		if ($session->get("cronTask", false, "fleximport") || $method == 'cron') {
			if ($reload) {
				$mainframe = JFactory::getApplication();
				$cronLink = 'index.php?option=com_fleximport&task=import.cron_reload&format=raw&cronid=' . JFactory::getApplication()->input->get('cronid',null,'int');
				$mainframe = JFactory::getApplication();
				$mainframe->redirect($cronLink);
			}
			header('Content-Type: text/html; charset=utf-8');
			echo json_encode(array('html' => $session->get("jsonReturn", '', "fleximport_response"),'errors' => $errors));
		} else {
			header('Content-type: application/json');
			echo json_encode(array('reload' => $reload, 'html' => $session->get("jsonReturn", '', "fleximport_response"),'errors' => $errors));
		}
		// on vide le cache de retour
		$session->clear("jsonReturn", "fleximport_response");
		$session->close();
		jexit();
	}
	public static function isSerialized($data = "")
	{
		if (trim($data) == "") {
			return false;
		}
		if (preg_match("/^(i|s|a|o|d){1}:{1}(.*)/si", $data)) {
			return true;
		}
		return false;
	}
	/* Permet de cr�er une archive ZIP */
	public static function createZip($files = array(), $destination = '', $overwrite = true)
	{
		if (file_exists($destination) && !$overwrite) {
			return false;
		}
		// v�rifie que tous les fichiers existent bien
		$valid_files = array();
		if (is_array($files)) {
			foreach($files as $file) {
				if (file_exists($file['file'])) {
					$valid_files[] = $file;
				}
			}
		}
		// s'il y a bien des fichiers
		if (count($valid_files)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			// ajoute les fichiers en fonction du path (zip) d�finit
			foreach($valid_files as $file) {
				$zip->addFile($file['file'], $file['zip']);
			}
			$zip->close();
			return file_exists($destination);
		} else {
			return false;
		}
	}
	public static function debug($value = "", $title = "<hr/>", $die = false )
	{
		if (FLEXIMPORT_DEBUG) {
			echo "<div>";
			echo "<strong>" . $title . "</strong><br/>";
			if (is_array($value) or is_object($value)) {
				echo "<pre>";
				print_r($value);
				echo "</pre>" ;
			} else {
				echo $value;
			}
			echo "</strong>";
		}
		if ($die)die('DEBUG');
	}
	/*
	   fonction r�cursive qui permet de supprimer tout le contenu d'un dossier
	*/
	public static function clearDir($directory)
	{
		if (substr($directory, - 1) == "/") {
			$directory = substr($directory, 0, - 1);
		}
		if (!file_exists($directory) || !is_dir($directory)) {
			return false;
		} elseif (!is_readable($directory)) {
			return false;
		} else {
			$directoryHandle = opendir($directory);
			while ($contents = readdir($directoryHandle)) {
				if ($contents != '.' && $contents != '..') {
					$path = $directory . "/" . $contents;
					if (is_dir($path)) {
						self::clearDir($path);
					} else {
						@unlink($path);
					}
				}
			}
			closedir($directoryHandle);
			return true;
		}
		return true;
	}
}