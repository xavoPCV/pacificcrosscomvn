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
require_once (JPATH_COMPONENT . '/classes/base.php');

class fleximportExport extends fleximportBase {
    private $_filters = null; // ID du type d'export
    private $_task = null; // tâche d'export à réaliser
    function __construct($filters = null, $task = 'all')
    {
        if ($filters && !$this->_type) {
            $this->_mainClass = 'EXPORT';
            if ($task == 'cron') {
                $this->_method = 'cron';
            } else {
                $this->_method = 'manual';
            }
            $this->_filters = $filters;
            $this->_type = $this->_filters['params_type'];
            $this->_task = $task;
            $this->_data = null;
            // supprime la sélection
            if ($this->_task == 'all')
                $this->_filters['cid'] = array();
            parent::__construct();
        }
        return false;
    }
    function __destruct()
    {
        parent::__destruct();
    }
    /* Méthode pour récupérer toutes les données à exporter*/
    public function getData()
    {
        if ($this->_type && $this->_filters && !$this->_data && $this->_task) {
            $linkWithID = $this->_params->get('field_linked_id');
            $fieldLinked = $this->_params->get('field_linked');
            $model = $this->getModel('Export', 'Fleximport');
            // on récupére tous les id à exporter
            $items = $model->getAllItems();
            $fields = $this->getFleximportFields();
            foreach ($items as $item) {
                $itemID = $item->id;
                foreach ($fields as $field) {
                    if (JFile::exists(JPATH_COMPONENT . '/classes/plugins/fields/' . $field->field_type . '.php')) {
                        require_once (JPATH_COMPONENT . '/classes/plugins/fields/' . $field->field_type . '.php');
                    } else {
                        // on ne stocke pas la valeur de l'enregistrement et on ajoute une erreur dans le log
                        $this->logImport("ERROR_PLUGIN_FIELD", JText::sprintf('COM_FLEXIMPORT_LOG_ERROR_REQUIRED_FIELD_DETAIL', $field->field_type, $itemID));
                        unset($this->_data[$itemID]);
                        break;
                    }
                    // formatage des valeurs du champ
                    $fieldExportClass = 'fleximportPlugin' . $field->field_type;
                    $fielExport = new $fieldExportClass($field, $this->_params);
                    // si c'est la clé primaire et que l'on souhaite utiliser l'ID comme valeur
                    if ($linkWithID && $fieldLinked == $field->id) {
                        $this->_data[$itemID][$field->id]['values'] = array($itemID);
                    } else {
                        $fielExport->loadValues($itemID);
                        $fielExport->formatValuesExport();
                        $this->_data[$itemID][$field->id]['values'] = $fielExport->getValues();
                    }
                    $this->_data[$itemID][$field->id]['labels'] = $field->label;
                    $this->_data[$itemID][$field->id]['params'] = new JRegistry($field->params);
                }
            }
        }
        // s'il n'y a aucune donnée à traiter
        if (!count($this->_data)) {
            $this->logImport("ERROR_NO_DATAS");
            return false;
        } else {
            return true;
        }
    }
    /*
		Méthode pour générer le fichier exporté
	*/
    public function exportData()
    {
        if (!$this->_data) return false;
        if (!JFile::exists(JPATH_COMPONENT . '/classes/plugins/types/' . $this->_type_format . '.php')) {
            $this->logImport("ERROR_PLUGIN_FIELD");
            return false;
        }
        require_once(JPATH_COMPONENT . '/classes/plugins/types/' . $this->_type_format . '.php');
        $filename = $this->_params->get('filename', JText::_('COM_FLEXIMPORT_DEFAULT_FILENAME'));
        if ($this->_params->get('export_prefix', '')) {
            $exportPrefix = JHtml::_('date', 'now', $this->_params->get('export_prefix', ''));
        } else {
            $exportPrefix = '';
        }
        if ($this->_params->get('export_suffix', '')) {
            $exportSuffix = JHtml::_('date', 'now', $this->_params->get('export_suffix', ''));
        } else {
            $exportSuffix = '';
        }

        $filename = $exportPrefix . $filename . $exportSuffix;
        // si c'est un fichier zip, on supprime l'extension de base
        if (substr($filename, strlen($filename) - 3, 3) == 'zip')
            $filename = substr($filename, 0, (strrpos($filename, '.')));
        // force l'extension du type exporté
        if (substr($filename, strlen($filename) - 3, 3) != $this->_type_format)
            $filename .= '.' . $this->_type_format;
        $filename = JFile::makeSafe($filename);
        if (!JFolder::exists($this->_pathToTemp))
            JFolder::create($this->_pathToTemp);
        $exportFile = new fleximportPluginType($filename, $this->_pathToTemp, $this->_attribs);
        if ($nbExport = $exportFile->exportData($this->_data, $this->_type)) {
            $this->logImport('NB_EXPORT', $nbExport);
            if (JFactory::getApplication()->input->get('params_export_attachment')=='2') {
                $filePath = $this->_params->get('filename', 'fleximport');
                $pathExtract = $this->_pathToTemp . JFolder::makeSafe($filePath) ;
                if (JFolder::exists($pathExtract)) {
                    $filesToZip = JFolder::files($pathExtract, null, true, true);
                    $arZip = array();
                    $i = 0;
                    foreach ($filesToZip as $fileToZip) {
                        $arZip[$i]['file'] = $fileToZip;
                        $arZip[$i]['zip'] = 'files/' . basename($fileToZip);
                        $i++;
                    }
                    $arZip[$i]['file'] = $this->_pathToTemp . $filename;
                    $arZip[$i]['zip'] = $filename;
                    // on supprime l'extension du fichier
                    $filename = substr($filename, 0, (strrpos($filename, '.')));
                    if (FleximportHelper::createZip($arZip, $this->_pathToTemp . $filename . '.zip'))
                        $filename .= '.zip';
                }
            }
            $return = $this->transfert($filename);
            return $return;
        } else {
            $this->logImport('ERROR_FILE', $filename);
            return false;
        }
    }
    /*
		Méthode pour transférer le fichier au bon endroit
	*/
    private function transfert($Exportfilename = '')
    {
        // création du répertoire temporaire
        if (!JFile::exists($this->_pathToTemp . $Exportfilename)) return false;

        $return = false;

        $exportArchive = $this->_params->get('archive');
        $exportAddress = $this->_params->get('address', 'images/fleximport/');

        switch ($this->_params->get('localisation')) {
            case "ftp":
                jimport('joomla.client.ftp');
                $exportUsername = $this->_params->get('username');
                $exportPassword = $this->_params->get('password');
                $exportPort = $this->_params->get('port', '21');
                $exportServer = $this->_params->get('server');
                // connection au FTP
            	    $clientFTP = JClientFtp::getInstance($exportServer, $exportPort, null, $exportUsername, $exportPassword);

                if ($clientFTP->isConnected()) {
                    $this->logImport("CONNECTED_FTP");
                    if (!$clientFTP->chdir($exportAddress)) {
                        $clientFTP->mkdir($exportAddress);
                    }
                    if ($clientFTP->chdir($exportAddress)) {
                        // copy to temp directory to process after
                        if ($clientFTP->store($this->_pathToTemp . $Exportfilename)) {
                            $this->logImport("COPY_DONE", $Exportfilename);
                            $return = true;
                        } else {
                            $this->logImport("ERROR_COPY", $exportAddress . $Exportfilename);
                        }
                    } else {
                        $this->logImport("FTP_ERROR_DIR", $exportAddress . $Exportfilename);
                    }
                    $clientFTP->quit();
                } else {
                    $this->logImport("ERROR_CONNECT_FTP");
                }
                break;
            case "web":
            case "local":
                if ($this->_params->get('localisation') == 'web') {
                    $this->logImport("ERROR_COPY_WEB");
                }
                // si le premier caractère du chemin du fichier n'est pas un slash, on utilisera le chemin relatif du site
                if (substr($exportAddress, 0, 1) != '/') {
                    $exportAddress = JPATH_SITE . '/' . $exportAddress;
                }
                // copie dans le répertoire temporaire pour lancer le traitement par la suite
                if (JFile::copy($this->_pathToTemp . $Exportfilename, $exportAddress . $Exportfilename)) {
                    $this->logImport("COPY_DONE", $Exportfilename);
                    // si c'est un fichier à l'intérieur du site on fait un lien dessus pour que l'utilisateur puisse directement le télécharger
                    if (substr($this->_params->get('address'), 0, 1) != '/') {
                        $this->logImport('DONE', '<a href="' . JUri::root(true) . '/' . $this->_params->get('address', 'images/fleximport/') . $Exportfilename . '" target="_BLANK">' . $Exportfilename . '</a>');
                    } else {
                        $this->logImport('DONE', $Exportfilename);
                    }
                    $return = true;
                } else {
                    $this->logImport("ERROR_COPY", $exportAddress . $Exportfilename);
                }
                break;
        }
        // si le fichier doit être archivé, on fait une copie de l'ensemble des fichiers vers le répertoire d'archive
        if ($exportArchive) {
            $this->archiveFile($Exportfilename);
        }
        return $return ;
    }
}