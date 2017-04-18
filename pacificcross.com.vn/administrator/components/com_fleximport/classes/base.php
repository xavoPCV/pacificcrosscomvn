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
jimport('joomla.filesystem.folder');

class fleximportBase {
    protected $_pathToTemp = ''; // Chemin vers le répertoire temporaire
    protected $_pathToLog = ''; // Chemin vers le répertoire de log
    protected $_type = 0; // ID du type d'export/import
    protected $_flexi_type_id = 0; // ID du type FLEXIcontent
    protected $_type_format = ''; // format of the file
    protected $_params = null; // Paramètres du type d'export/import
    protected $_attribs = ''; // Paramètres au format ini
    protected $_logFileName = ''; // Nom du fichier de log
    protected $_mainClass = ''; // Pour déterminer si c'est la classe export ou import
    protected $_firstLoad = true; // permet de savoir si la classe a été apellé ou non pour la premiere fois
    protected $_method = 'manual'; // méthode d'import/export (manual , auto ou cron)
    public function __construct()
    {
        // chagement des informations sur le type d'import
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('params,import_type,flexi_type_id')->from('#__fleximport_types')->where('id=' . (int)$this->_type);
        $db->setQuery($query);
        $result = $db->loadAssoc();
        $this->_flexi_type_id = $result['flexi_type_id'];
        // chargement des paramètres
        $this->_attribs = $result['params'];
        $this->_params = new JRegistry($this->_attribs);
        $this->_pathToTemp = JPATH_SITE . '/' . $this->_params->get('temp_path', 'tmp/fleximport/');
        // on purge le répertoire temporaire
        if ($this->_firstLoad) FleximportHelper::clearDir($this->_pathToTemp);

        $this->_type_format = $result['import_type'];

        if ($this->_firstLoad) $this->logImport("START");
    }
    public function __destruct()
    {
        // traitement uniquement si la tache ajax est terminée
        $session = JFactory::getSession();
        if (($session->get("finish", false, "fleximport") && $this->_mainClass == "IMPORT") or $this->_mainClass == "EXPORT") {
            // supprime tous les fichiers temporaires
            if (FleximportHelper::clearDir($this->_pathToTemp)) {
                $this->logImport("DELETE_TEMP");
            }
            $this->logImport("END");
        }
    }
    protected function getModel($model = "", $component = "Flexicontent")
    {
        $component = ucfirst($component);
        if (!class_exists($component . 'Model' . $model)) {
            // Build the path to the model based upon a supplied base path
            $path = JPATH_BASE . '/components/com_' . strtolower($component) . '/models/' . strtolower($model) . '.php';
            // If the model file exists include it and try to instantiate the object
            if (file_exists($path)) {
                require_once($path);
                if (!class_exists($component . 'Model' . $model)) {
                    return false;
                }
            } else {
                return false;
            }
        }

        $modelName = $component . 'Model' . $model;
        $model = new $modelName();
        return $model;
    }
    /*
	   Méthode pour avoir la liste des champs correspondand à un type d'import
	*/
    protected function getFleximportFields()
    {
        if (!$this->_type) return false;
        $filterType = '';
        if ($this->_mainClass == 'IMPORT') {
            $filterType = '(i.params like \'%"usefor":"1"%\' or i.params like \'%"usefor":"2"%\' or i.params not like \'%"usefor":""%\')';
        } elseif ($this->_mainClass == 'EXPORT') {
            $filterType = '(i.params like \'%"usefor":"1"%\' or i.params like \'%"usefor":"3"%\' or i.params not like \'%"usefor":""%\')';
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('i.*, f.name AS fname, f.attribs AS fattribs, f.field_type AS ftype');
        $query->from(' #__fleximport_fields AS i');
        $query->leftJoin('#__flexicontent_fields AS f ON i.flexi_field_id=f.id');
        $query->where('i.published = 1');
        $query->where('i.type_id =  ' . (int) $this->_type);
        $query->where($filterType);
        $query->order('i.ordering');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    /*
	   Méthode pour avoir la liste des champs personnalisés d un type flexicontent
	*/
    protected function getFlexicontentFields()
    {
        if (!$this->_flexi_type_id) return false;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('f.id')->from('#__flexicontent_fields AS f');
        $query->leftJoin('#__flexicontent_fields_type_relations r on f.id=r.field_id');
        $query->where('f.iscore = 0');
        $query->where('r.type_id = ' . (int) $this->_flexi_type_id);
    	$query->where('f.field_type NOT IN ("'.implode('","',$GLOBALS['fi_fields_nocopy']).'")');
        $query->where('f.published= 1');
        $query->order('f.ordering');
        $db->setQuery($query);
        return $db->loadColumn();
    }
    /*
	   Méthode pour avoir la liste des champs personnalisés d un type flexicontent
	*/
    protected function getFlexicontentFieldName($fieldID = 0)
    {
        if (!$fieldID) return false;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('f.name')->from('#__flexicontent_fields AS f');
        $query->where('f.id=' . (int)$fieldID);
        $db->setQuery($query);
        return $db->loadResult();
    }
    /*
	   Méthode pour savoir si un champs est système ou non
	*/
    protected function getFlexicontentFieldIsCore($fieldID = 0)
    {
        if (!$fieldID) return false;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('f.iscore')->from('#__flexicontent_fields AS f');
        $query->where('f.id=' . (int)$fieldID);
        $db->setQuery($query);
        return $db->loadResult();
    }
    /*
	   Méthode pour avoir la valeurs d'un champs
	*/
    protected function getFlexicontentFieldValue($fieldID = 0, $dataID = 0)
    {
        if (!$fieldID && !$dataID) return false;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('value')->from('#__flexicontent_fields_item_relations');
        $query->where('field_id=' . (int)$fieldID)->where('item_id=' . (int)$dataID);
        $db->setQuery($query);
        $results = $db->loadColumn();
        foreach ($results as &$result) {
            if (FleximportHelper::isSerialized($result)) {
                $result = unserialize($result);
            }
        }
        return $results;
    }
    /*
	   Méthode pour les informations sur un champs d'import
	*/
    protected function getFleximportField($fieldID = 0)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('i.*')->from('#__fleximport_fields AS i');
        $query->where('i.published = 1')->where('i.id=' . (int) $fieldID);
        $db->setQuery($query);
        return $db->loadObject();
    }
    /*
	   Methode pour archiver une fichier
	   $filename = nom du fichier à archiver
	*/
    protected function archiveFile($filename = '')
    {
        $path = $this->_pathToTemp;

        $importArchivePath = $this->_params->get('archive_path', 'images/fleximport/archives/');
        // si le premier caractère du chemin du fichier n'est pas un slash, on utilisera le chemin relatif du site
        if (substr($importArchivePath, 0, 1) != '/') {
            $importArchivePath = JPATH_SITE .'/'. $importArchivePath;
        }
        if (!JFolder::exists($importArchivePath)) {
            JFolder::create($importArchivePath);
        }
        $timeArchive = date('YmdGis');
        if (JFile::copy($path . $filename, $importArchivePath . $timeArchive . '_' . $filename)) {
            if (substr($importArchivePath, 0, 1) != '/') {
                $this->logImport('FILE_ARCHIVED', '<a href="' . JUri::root(true) . '/' . $this->_params->get('archive_path', 'images/fleximport/archives/') . $timeArchive . '_' . $filename . '" target="_blank">' . $filename . '</a>');
            } else {
                $this->logImport('FILE_ARCHIVED', $filename);
            }
            return true;
        }
        return false;
    }
    /*
	* Methode pour tracer l'import
       $message : message à sauvegarder
       $detail : message complémentaire
	*/
    protected function logImport($message = '', $details = '')
    {
        $content = '';
        if (!$this->_logFileName || !$this->_pathToLog) {
            $this->_pathToLog = JPATH_SITE . '/' . $this->_params->get('log_path', 'images/fleximport/logs/');
        	$dateLog= JHtml::_('date', 'now', 'YmdHis');
            if ($this->_mainClass == 'IMPORT') {
                $session = JFactory::getSession();
                if (!$session->get("logFileName", "", "fleximport"))
                    $session->set("logFileName", $dateLog . "_" . $this->_type . "_import-" . $this->_method . "-" . $this->_type_format . ".log" , "fleximport");
                $this->_logFileName = $session->get("logFileName", "", "fleximport");
            } elseif ($this->_mainClass == 'EXPORT') {
                $this->_logFileName = $dateLog . "_" . $this->_type . "_export-" . $this->_method . '-' . $this->_type_format . ".log";
            }
        }

        if (!JFolder::exists($this->_pathToLog)) JFolder::create($this->_pathToLog);

        if ($details) $details = " : " . $details;

        if (JFile::exists($this->_pathToLog . $this->_logFileName))
            $content = file_get_contents($this->_pathToLog . $this->_logFileName);

        // gestion des messages valables pour l'export et l'import
        if (JText::_("COM_FLEXIMPORT_LOG_" . $this->_mainClass . '_' . $message) == "COM_FLEXIMPORT_LOG_" . $this->_mainClass . '_' . $message) {
            $message = JText::_("COM_FLEXIMPORT_LOG_" . $message);
        } else {
            $message = JText::_("COM_FLEXIMPORT_LOG_" . $this->_mainClass . '_' . $message);
        }
		$hourLog= JHtml::_('date', 'now', 'H:i:s');
        FleximportHelper::debug($hourLog . " | " . $message . $details);
        $fileToWrite = $this->_pathToLog . $this->_logFileName;
        $messageToWrite = $content . $hourLog . " | " . $message . $details . "\r\n";
        if (JFile::write($fileToWrite, $messageToWrite)) return true;

        return false;
    }
    /*
	   Méthode pour envoyer le rapport par mail
	*/
    public function sendLog()
    {
        // si aucun mail n'est paramétré
        if (!($email = $this->_params->get("log_email", "")))return false;
        // vérifie le formail du mail
        jimport('joomla.utilities.mail');
        if (!JMailHelper::isEmailAddress($email)) return false;
        // vérifie
        if (JFile::exists($this->_pathToLog . $this->_logFileName)) {
            $content = file_get_contents($this->_pathToLog . $this->_logFileName);
            $content = nl2br($content);
        } else {
            return false;
        }
        // préparation et envoi du mail
        $mailer = JFactory::getMailer();
        $mailer->addRecipient($email);
        $mailer->setSubject(JText::_('COM_FLEXIMPORT_LOG_EMAIL_SUBJECT') . $this->_logFileName);
        $mailer->setBody($content);
        $mailer->IsHTML(true);

        if ($mailer->Send() !== true)return false;

        return true;
    }
}