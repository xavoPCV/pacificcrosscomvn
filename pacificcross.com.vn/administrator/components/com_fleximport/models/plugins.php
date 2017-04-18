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

jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * flexIMPORT Component plugins Model
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportModelPlugins extends JModelLegacy {
    private $items = null;
    private $context = 'com_fleximport.plugin';

    /**
     * Constructor
     *
     * @since 1.0
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Method to get fields data
     *
     * @access public
     * @return array
     */
    function getItems()
    {
        // Lets load the fields if it doesn't already exist
        $type_plugin = $this->getState('filter.type_plugin');
        if ($type_plugin == "field") {
            $pluginsPath = array(FLEXIMPORT_PATH_FIELDS);
        } elseif ($type_plugin == "type") {
            $pluginsPath = array(FLEXIMPORT_PATH_TYPES);
        } else {
            $pluginsPath = array(FLEXIMPORT_PATH_TYPES, FLEXIMPORT_PATH_FIELDS);
        }
        $pluginsFile = array();
        foreach ($pluginsPath as $pluginPath) {
            if (JFolder::exists($pluginPath)) {
                $pluginsFile = JFolder::files($pluginPath, ".xml$");
                foreach ($pluginsFile as $pluginFile) {
                    $plugin = new stdClass();
                    $parser = simplexml_load_file($pluginPath . $pluginFile);
                    @$plugin->name = (string)$parser->name;
                    @$plugin->description = (string)$parser->description;
                    @$plugin->version = (string)$parser->version;
                    @$plugin->license = (string)$parser->license;
                    if (@(string)$parser->system=='true') {
                        $plugin->system = 'true';
                    } else {
                        $plugin->system = 'false';
                    }
                	@$plugin->authorurl = (string)$parser->authorurl;

                    if (@$plugin->authorurl) {
                        $plugin->author = "<a href=\"http://" . $plugin->authorurl . "\" target=\"_blank\">" . @(string)$parser->author . "</a>";
                    }else{
                    	@$plugin->author = (string)$parser->author;
                    }
                    @$plugin->type = $parser->xpath('//install');
                	 if (is_array($plugin->type)){
                        @$plugin->type= @(string)current($plugin->type)->attributes()->type;
                    }else{
                        @$plugin->type= @(string)$plugin->type->attributes()->type;
                    }
                    if ($plugin->name && $plugin->type) {
                        $plugin->id = strtolower($plugin->type . '_' . $plugin->name);
                        $this->items[] = $plugin;
                    }
                }
            }
        }
        return $this->items;
    }
    // Install / Upgrade extensions
    function installUpgrade()
    {
        if (!(is_writable(FLEXIMPORT_PATH_FIELDS) && is_writable(FLEXIMPORT_PATH_TYPES))) {
        	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_CANNOT_WRITE'));
            return false;
        }
        // Get vars
        $userfile = JFactory::getApplication()->input->get('install_package',null,'array');
        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
        	$this->setError(JText::_('COM_FLEXIMPORT_LIB_UPLOAD'));
            return false;
        }
        // Make sure that zlib is loaded so that the package can be unpacked
        if (!extension_loaded('zlib')) {
        	$this->setError(JText::_('COM_FLEXIMPORT_LIB_ZIP'));
            return false;
        }
        // If there is no uploaded file, we have a problem...
        if (!is_array($userfile)) {
        	$this->setError(JText::_('COM_FLEXIMPORT_NO_FILES_SELECTED'));
            return false;
        }
        // Check if there was a problem uploading the file.
        if ($userfile['error'] || $userfile['size'] < 1) {
        	$this->setError(JText::_('COM_FLEXIMPORT_UPLOAD_ERROR'));
            return false;
        }
        // Build the appropriate paths
        $config = JFactory::getConfig();

        $tmp_dest = $config->get('config.tmp_path') . '/' . $userfile['name'];
        $tmp_src = $userfile['tmp_name'];
        // Move uploaded file
        $uploaded = JFile::upload($tmp_src, $tmp_dest);

        if (!$uploaded) {
        	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_ERROR_UPLOAD'));
            return false;
        }
        // Unpack the downloaded package file
        $package = JInstallerHelper::unpack($tmp_dest);
        // Delete the package file
        JFile::delete($tmp_dest);

        $pluginsFile = JFolder::files($package['dir'], ".xml$");
        foreach ($pluginsFile as $pluginFile) {
        	$parser = simplexml_load_file($package['dir'] . '/' . $pluginFile);
            $pluginName = @strtolower((string)$parser->name);
            // vérifie si les deux fichiers du plugin existent bien
            if (JFile::exists($package['dir'] . '/' . $pluginName . '.xml') && JFile::exists($package['dir'] . '/' . $pluginName . '.php')) {
                if ($package['type'] == "fleximport_type") {
                    $install_path = FLEXIMPORT_PATH_TYPES;
                    $languageType = "types";
                } elseif ($package['type'] == "fleximport_field") {
                    $install_path = FLEXIMPORT_PATH_FIELDS;
                    $languageType = "fields";
                }
                if (!$install_path) {
                	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_ERROR_FORMAT'));
                    return false;
                }
                JFile::copy($package['dir'] . '/' . $pluginName . '.xml', $install_path . $pluginName . '.xml');
                JFile::copy($package['dir'] . '/' . $pluginName . '.php', $install_path . $pluginName . '.php');
                $languageFiles = JFolder::files($package['dir'], ".ini$");
                foreach ($languageFiles as $languageFile) {
                    $languageCountry = substr($languageFile, 0, 5);
                    if (JFolder::exists(JPATH_ADMINISTRATOR . '/language/'  . $languageCountry))
                        JFile::copy($package['dir'] . '/' . $languageFile, JPATH_ADMINISTRATOR . '/language/' . $languageCountry . '/' . $languageCountry . ".plg_fleximport_" . $languageType . "_" . $pluginName . ".ini");
                }
                JFolder::delete($package['dir']);
                return true;
            } else {
            	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_ERROR_FORMAT'));
                return false;
            }
        }
    }

    function delete($cid = array())
    {
        if (!is_array($cid) || count($cid) < 1) {
        	$this->setError(JText::_('COM_FLEXIMPORT_SELECT_ITEM_DELETE'));
            return false;
        } else {
            foreach ($cid as $file) {
                $file = urldecode(strtolower($file));
                $dataFile = explode("_", $file);
                // vérifie si c'est bien un fichier de fleximport
                if ($dataFile[0] == "fleximport") {
                    if ($dataFile[1] == "type") {
                        $pluginPath = FLEXIMPORT_PATH_TYPES;
                    } elseif ($dataFile[1] == "field") {
                        $pluginPath = FLEXIMPORT_PATH_FIELDS;
                    } else {
                        $pluginPath = "";
                    	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_WRONG_TYPE'));
                    }
                    if ($pluginPath != "") {
                        if (!$this->pluginUsed($dataFile[1], $dataFile[2])) {
                            if (JFile::exists($pluginPath . $dataFile[2] . ".php") && JFile::exists($pluginPath . $dataFile[2] . ".xml")) {
                                JFile::delete($pluginPath . $dataFile[2] . ".php");
                                JFile::delete($pluginPath . $dataFile[2] . ".xml");
                                $languageDir = JPATH_ADMINISTRATOR . '/language';
                                $languageFolders = JFolder::folders($languageDir);
                                foreach ($languageFolders as $languageFolder) {
                                    if (Jfile::exists($languageDir . '/' . $languageFolder . '/' . $languageFolder . ".plg_fleximport_" . $dataFile[1] . "s_" . $dataFile[2] . ".ini"))
                                        Jfile::delete($languageDir . '/' . $languageFolder . '/' . $languageFolder . ".plg_fleximport_" . $dataFile[1] . "s_" . $dataFile[2] . ".ini");
                                }
                            } else {
                            	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_NO_FILES'));
                            	return false;
                            }
                        } else {
                        	$this->setError(JText::_('COM_FLEXIMPORT_PLUGINS_IN_USE'));
                        	return false;
                        }
                    }
                }
            }
            return true;
        }
    }

    private function pluginUsed($name = "", $type = "")
    {
        if (!($name && $type))return false;
        if ($type == "field") {
            $query = "select id from #__fleximport_fields where field_type=" . $this->_db->quote($name);
        } elseif ($type == "type") {
            $query = "select id from #__fleximport_types where import_type=" . $this->_db->quote($name);
        }
        $this->_db->setQuery($query);
        return (bool)$this->_db->loadResult();
    }

    protected function populateState()
    {
        $app = JFactory::getApplication();
        $filter_typeplugin = $app->getUserStateFromRequest($this->context . 'filter.type_plugin', 'type_plugin');
        $this->setState('filter.type_plugin', $filter_typeplugin);
        parent::populateState();
    }
}