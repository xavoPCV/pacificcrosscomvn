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
require_once(JPATH_COMPONENT.'/classes/plugins/field.php');
class fleximportPluginfile extends fleximportFieldPlugin {
    public function formatValues()
    {
		$config = JFactory::getConfig();
		$pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/') ;
        $fileExistMethod = $this->_fieldParams->get('file_exist', '2');
        // manipule seulement dans le r�pertoire s�curis�e de FLEXIcontent
        $onlypath = COM_FLEXICONTENT_FILEPATH . '/';
        // cr�ation du r�pertoire s'il n'existe pas
        if (!JFolder::exists($onlypath)) {
            if (!JFolder::create($onlypath))
                return false;
        }
        if ($this->_fieldParams->get("localisation_use", 0)) {
            $localisation = $this->_fieldParams->get("localisation", "local");
        } else {
            $localisation = $this->_typeParams->get("localisation", "local");
        }
        if ($this->_fieldParams->get("address_use", 0)) {
            $address = $this->_fieldParams->get("address", "");
        } else {
            $address = $this->_typeParams->get("address", "");
        }
        if ($this->_fieldParams->get("server_use", 0)) {
            $server = $this->_fieldParams->get("server", "");
            $port = $this->_fieldParams->get("port", "21");
        } else {
            $server = $this->_typeParams->get("server", "");
            $port = $this->_typeParams->get("port", "21");
        }
        if ($this->_fieldParams->get("user_use", 0)) {
            $username = $this->_fieldParams->get("username", "");
            $password = $this->_fieldParams->get("password", "");
        } else {
            $username = $this->_typeParams->get("username", "");
            $password = $this->_typeParams->get("password", "");
        }
    	$db = JFactory::getDbo();
        $fieldvalues = array();
        foreach ($this->_fieldValues as $fieldvalue) {
            // action � r�aliser
            $process = true;
            $filename = $fieldvalue;
            // v�rifie s'il y a pas un r�pertoire dans le nom du fichier
            if (($strrpos = strrpos($filename, '/')) !== false)
                $filename = substr($filename, $strrpos + 1, strlen($filename) - $strrpos);
			$filenameSafe = JFile::makeSafe($filename);
            $filenameSanitize = $this->sanitizeFilename($filename);
            // si le fichier existe d�j� dans flexicontent
            if ($fileExist = $this->fileExist($filenameSanitize)) {
                switch ($fileExistMethod) {
                    case "1":// utiliser le fichier flexi s'il existe ;
                        $fieldvalues[] = $fileExist;
                        $process = false;
                        break;
                    case "2": // remplace le fichier de flexicontent ;
                        if ($this->localizeFiles($fieldvalue, $filename, $localisation, $server, $address, $username, $password, $port)) {
                            $filepath = JPath::clean($onlypath . strtolower($filenameSanitize));
                            if (JFile::exists($pathTemp . 'files/' . $filenameSafe)) {
                                $pathZip = 'files/';
                            } else {
                                $pathZip = '';
                            }
                            if (JFile::copy($pathTemp . $pathZip . $filenameSafe, $filepath)) {
                                $user = JFactory::getUser();
                                $date = JFactory::getDate();
                                $db->setQuery("UPDATE #__flexicontent_files set uploaded = " . $db->quote($date->toSql()) . " , uploaded_by = " . $user->get('id') . " WHERE id = " . $fileExist);
                                $db->execute();
                            }
                            // on garde la valeur m�me si le fichier n'a pas pu �tre t�l�charg�
                            $fieldvalues[] = $fileExist;
                        }
                        $process = false;
                        break;
                    case "3": // cr�er un autre fichier ;
                        $process = true;
                        break;
                    default:
                        $process = false;
                }
            }
            if ($process) {
                // si le fichier a bien �t� r�cup�r� dans le r�pertoire temporaire
                if ($this->localizeFiles($fieldvalue, $filename, $localisation, $server, $address, $username, $password, $port)) {

					// rend le nom de fichier de destination unique
                    $filenameSafeCopy = flexicontent_upload::sanitize($onlypath, $filename);
                    $filepath = JPath::clean($onlypath . strtolower($filenameSafeCopy));
                	if (JFile::exists($pathTemp . 'files/' . $filenameSafe)) {
                		$pathZip = 'files/';
                	} else {
                		$pathZip = '';
                	}
                    // copie de l'image dans son r�pertoire de destination
                    if (JFile::copy($pathTemp . $pathZip . $filenameSafe, $filepath)) {
                        $ext = strtolower(JFile::getExt($filename));
                        $user = JFactory::getUser();
                        $date = JFactory::getDate();
                        // donn�es de l'image pour la base de donn�e
                        $obj = new stdClass();
                        $obj->filename = $filenameSafeCopy;
                        $obj->altname = $filename;
                        $obj->url = 0;
                        $obj->secure = 1;
                        $obj->ext = $ext;
                        $obj->hits = 0;
                        $obj->uploaded = $date->toSql();
                        $obj->uploaded_by = $user->get('id');
                        // ajout dans la base de donn�e
                        $db->insertObject('#__flexicontent_files', $obj);
                        // m�morisation pour traiter la valeur par le plugin FLEXIcontent
                        $fieldvalues[] = $db->insertid();
                    }
                }
            }
        }
        $this->_fieldValues = $fieldvalues;
    }
    public function formatValuesExport()
    {
        if (!count($this->_fieldValues)) return;

        // si on doit exporter les fichiers
        if (JFactory::getApplication()->input->get('params_export_attachment')=='2') {
            $pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/');
            $filePath = $this->_typeParams->get('filename', 'fleximport');
            $pathTemp = $pathTemp . JFolder::makeSafe($filePath);
            if (!JFolder::exists($pathTemp)) JFolder::create($pathTemp);
            foreach ($this->_fieldValues as $idv => $fieldValue) {
                $fileOk = false;
                if ($fieldInfo = $this->fileInfo($fieldValue)) {
                    if ($fieldInfo->url) {
                        // supprime les espaces
                        if (copy(str_replace(' ', '%20', $fieldInfo->filename), $pathTemp . '/' . $fieldInfo->altname . "." . $fieldInfo->ext)) {
                            $fileOk = true;
                        }
                    } else {
                        if ($fieldInfo->secure) {
                            $flexiPath = COM_FLEXICONTENT_FILEPATH;
                        } else {
                            $flexiPath = COM_FLEXICONTENT_MEDIAPATH;
                        }
                        if (JFile::copy($flexiPath . '/' . $fieldInfo->filename, $pathTemp . '/' . $fieldInfo->filename)) {
                            $fileOk = true;
                        }
                    }
                    $this->_fieldValues[$idv] = $fieldInfo->filename;
                }
                if (!$fileOk) unset($this->_fieldValues[$idv]);
            }
        } else {
            foreach ($this->_fieldValues as $idv => $fieldValue) {
                $fieldInfo = $this->fileInfo($fieldValue);
                $this->_fieldValues[$idv] = $fieldInfo->filename;
            }
        }
    }
}