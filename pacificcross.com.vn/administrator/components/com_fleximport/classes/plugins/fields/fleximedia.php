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
require_once(JPATH_COMPONENT . '/classes/plugins/field.php');
class fleximportPluginFleximedia extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
        return (int)$this->_fieldParams->get('fleximedia_default');
    }
    public function formatValues()
    {
        // chargement de fleximedia
        require_once(JPATH_ADMINISTRATOR . '/components/com_fleximedia/autoload.php');
        require_once(JPATH_ADMINISTRATOR . '/components/com_fleximedia/defineconstants.php');
        JModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fleximedia/models');
        JModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fleximedia/tables');
        $mediaModel = JModel::getInstance('Media', 'FleximediaModel');

        $mediaCategory = $this->_fieldParams->get('fleximedia_cat');
        $mediaType = $this->_fieldParams->get('fleximedia_type');
        $mediaPrefix = $this->_fieldParams->get('fleximedia_prefix');
        $mediaSuffix = $this->_fieldParams->get('fleximedia_suffix');
        $fileExistMethod = $this->_fieldParams->get('fleximedia_exist', '1');
        $pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/');
        // manipule seulement dans le r�pertoire s�curis�e de FLEXIcontent
        $uploadPath = JPATH_SITE . '/media/com_fleximedia/uploads/';

        foreach ($this->_fieldValues as $index => &$value) {
            $filename = $mediaPrefix . $value . $mediaSuffix;
            // v�rifie s'il y a pas un r�pertoire dans le nom du fichier
            if (($strrpos = strrpos($value, '/')) !== false)
                $filename = substr($value, $strrpos + 1, strlen($value) - $strrpos);

            $filenameSafe = JFile::makeSafe($filename);
            // si on ajoute le fichier dans le cas o� il existe d�j�
            if ($fileExistMethod == "3") {
                // rend le nom de fichier de destination unique
                $filenameSanitize = flexicontent_upload::sanitize($uploadPath, $filename);
            } else {
                $filenameSanitize = $this->sanitizeFilename($filename);
            }
            $fileExist = $this->fleximediaExist($filenameSanitize);
            // si le fichier existe d�j� et qu'on utilise la m�thode de remplacement
            if ($fileExist && $fileExistMethod == "1") {
                $value = $fileExist;
            } else {
                if ($this->_fieldParams->get("localisation_use", 0)) {
                    $localisation = $this->_fieldParams->get("localisation", "local");
                } else {
                    $localisation = $this->_typeParams->get("localisation", "local");
                }
                if ($this->_fieldParams->get("address_use", 0)) {
                    $address = $this->_fieldParams->get("address", "");
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
                // si le fichier a bien �t� r�cup�r� dans le r�pertoire temporaire
                if ($this->localizeFiles($filename, $filename, $localisation, $server, $address, $username, $password, $port)) {
                    // on va d�terminer si c'est un fichier qui a �t� d�compr�ss�
                    if (JFile::exists($pathTemp . 'files/' . $filenameSafe)) {
                        $pathZip = 'files/';
                    } else {
                        $pathZip = '';
                    }
                    // copie de l'image dans son r�pertoire de destination
                    if (JFile::exists($pathTemp . $pathZip . $filenameSafe)) {
                        $ext = strtolower(JFile::getExt($filenameSanitize));
                        $date = JFactory::getDate();
                        // si le fichier existe d�j� et qu'on doit juste le mettre � jour
                        $db = JFactory::getDbo();
                        if ($fileExistMethod == "2" && $fileExist) {
                            $db->setQuery("UPDATE #__fleximedia SET modified= " . $db->quote($date->toSql()) . "  WHERE id = " . (int)$fileExist);
                            $db->execute();
                        } else {
                            $data = array();
                            $data['name'] = $filename;
                            $data['type'] = mime_content_type($pathTemp . $pathZip . $filenameSafe);
                            $data['size'] = filesize($pathTemp . $pathZip . $filenameSafe);
                            $data['tmp_name'] = $pathTemp . $pathZip . $filenameSafe;
                            $data['error'] = 0;
                        	try {
                        		$media = $mediaModel->upload($data, $mediaType, false, array($mediaCategory));
                        		$value = (int)$media->id;
                        	} catch(Exeption $e){
                        		unset($this->_fieldValues[$index]);
                        	}
                        }
                    }
                } else {
                    unset($this->_fieldValues[$index]);
                }
            }
        }
        // gestion de l'image par d�faut
        if (!count($this->_fieldValues) && $this->_fieldParams->get('fleximadia_noexist', 0))
            $this->_fieldValues[] = $this->getDefaultValue();
    }

    public function getPostValues()
    {
        $post = array();
        $post['custom'] = array();
        $post['custom'][$this->_field->fname] = array();
        foreach ($this->_fieldValues as $fieldvalue) {
            $post['custom'][$this->_field->fname]['medias'][] = $fieldvalue;
        }
        return $post;
    }
    function fleximediaExist($filename = '')
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // v�rifie si l'image par d�faut existe bien
        $query->select('media_id')->from('#__fleximedia_fields_values');
        $query->where('filename = ' . $db->quote($filename));
        $db->setQuery($query);
        return (int)$db->loadResult();
    }
}