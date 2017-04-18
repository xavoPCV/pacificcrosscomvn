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
class fleximportPluginMinigallery extends fleximportFieldPlugin {
	public function getDefaultValue()
	{
		return $this->fileExist($this->_fieldParams->get('image_default'));
	}
	public function formatValues()
	{
		foreach ($this->_fieldValues as &$value) {
			$imgFile = $value;
			$fileExistMethod = $this->_fieldParams->get('image_exist', '1');
			// d�compose le fichier au format image::alt::title::desc
			$pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/');
			// manipule seulement dans le r�pertoire s�curis�e de FLEXIcontent
			$onlypath = COM_FLEXICONTENT_FILEPATH . '/';
			// cr�ation du r�pertoire s'il n'existe pas
			if (!JFolder::exists($onlypath)) {
				if (!JFolder::create($onlypath))
					return false;
			}
			// efface la valeur de l'image
			$value = array();
			// si une image doit �tre import�e
			if ($imgFile) {
				$filename = $imgFile;
				// v�rifie s'il y a pas un r�pertoire dans le nom du fichier
				if (($strrpos = strrpos($imgFile, '/')) !== false)
					$filename = substr($imgFile, $strrpos + 1, strlen($imgFile) - $strrpos);

				$filenameSafe = JFile::makeSafe($filename);
				// si on ajoute le fichier dans le cas o� il existe d�j�
				if ($fileExistMethod == "3") {
					// rend le nom de fichier de destination unique
					$filenameSanitize = flexicontent_upload::sanitize($onlypath, $filename);
				} else {
					$filenameSanitize = $this->sanitizeFilename($filename);
				}
				$fileExist = $this->fileExist($filenameSanitize);
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
					if ($this->localizeFiles($imgFile, $filename, $localisation, $server, $address, $username, $password, $port)) {
						// on r�cup�re le chemin du plugin de flexicontent pour tranf�rer ensuite la photo
						$destpath = JPath::clean(JPATH_SITE . '/' . $this->_flexiFieldParams->get('dir', 'images/stories/flexicontent') . '/');
						// cr�ation du r�pertoire s'il n'existe pas
						if (!JFolder::exists($destpath)) {
							if (!JFolder::create($destpath))
								return false;
						}
						// cr�ation des droits pour phpThumb
						if (JPath::canChmod($destpath))
							JPath::setPermissions($destpath, '0666', '0777');

						$filepath = JPath::clean($onlypath . strtolower($filenameSanitize));
						// on va d�terminer si c'est un fichier qui a �t� d�compr�ss�
						if (JFile::exists($pathTemp . 'files/' . $filenameSafe)) {
							$pathZip = 'files/';
						} else{
							$pathZip = '';
						}
						// copie de l'image dans son r�pertoire de destination
						if (JFile::copy($pathTemp . $pathZip . $filenameSafe, $filepath)) {
							$ext = strtolower(JFile::getExt($filenameSanitize));
							$user = JFactory::getUser();
							$date = JFactory::getDate();
							// si le fichier existe d�j� et qu'on doit juste le mettre � jour
							$db = JFactory::getDbo();
							if ($fileExistMethod == "2" && $fileExist) {
								$db->setQuery("UPDATE #__flexicontent_files set uploaded = " . $db->quote($date->toSql()) . " , uploaded_by = " . (int)$user->get('id') . " WHERE id = " . (int)$fileExist);
								$db->execute();
							} else {
								// donn�es de l'image pour la base de donn�e
								$obj = new stdClass();
								$obj->filename = $filenameSanitize;
								$obj->altname = $filename;
								$obj->url = 0;
								$obj->secure = 1;
								$obj->ext = $ext;
								$obj->hits = 0;
								$obj->uploaded = $date->toSql();
								$obj->uploaded_by = $user->get('id');
								// ajout dans la base de donn�e
								$db->insertObject('#__flexicontent_files', $obj);
								$fileExist = $db->insertid();
							}
							// m�morisation pour traiter la valeur par le plugin FLEXIcontent
							$value = $fileExist;
							$sizes = array('l', 'm', 's');
							foreach ($sizes as $size) {
								// param�tres pour  phpthumb
								$prefix = $size . '_';
								$w = $this->_flexiFieldParams->get('w_' . $size);
								$h = $this->_flexiFieldParams->get('h_' . $size);
								$crop = $this->_flexiFieldParams->get('method_' . $size);
								$quality = $this->_flexiFieldParams->get('quality');
								$usewm = $this->_flexiFieldParams->get('use_watermark_' . $size);
								$wmfile = JPath::clean(JPATH_SITE . '/' . $this->_flexiFieldParams->get('wm_' . $size));
								$wmop = $this->_flexiFieldParams->get('wm_opacity');
								$wmpos = $this->_flexiFieldParams->get('wm_position');
								// cr�ation de la miniature
								$this->imagePhpThumb($onlypath, $destpath, $prefix, $filenameSafe, $ext, $w, $h, $quality, $size, $crop, $usewm, $wmfile, $wmop, $wmpos);
							}
						}
					}
				}
			}
		}
		$this->_fieldValues = array_values($this->_fieldValues);
		// gestion de l'image par d�faut
		if (!count($this->_fieldValues) && $this->_fieldParams->get('image_noexist', 0))
			$this->_fieldValues[] = $this->getDefaultValue();
	}
	public function formatValuesExport()
	{
		// si on doit exporter les fichiers
		if (JFactory::getApplication()->input->get('params_export_attachment')=='2' and count($this->_fieldValues)) {
			$pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/');
			$filePath = $this->_typeParams->get('filename', 'fleximport');
			$pathTemp = $pathTemp . JFolder::makeSafe($filePath);
			if (!JFolder::exists($pathTemp)) JFolder::create($pathTemp);
			foreach ($this->_fieldValues as $idv => $fieldValue) {
				$fileOk = false;
				if ($fieldInfo = $this->fileInfo($fieldValue)) {
					if ($fieldInfo->url) {
						// supprime les espaces
						if (copy(str_replace(' ', '%20', $fieldInfo->filename), $pathTemp . '/' . $fieldInfo->altname . "." . $fieldInfo->ext))
							$fileOk = true;
					} else {
						if ($fieldInfo->secure) {
							$flexiPath = COM_FLEXICONTENT_FILEPATH;
						} else {
							$flexiPath = COM_FLEXICONTENT_MEDIAPATH;
						}
						if (JFile::copy($flexiPath . '/' . $fieldInfo->filename, $pathTemp . '/' . $fieldInfo->filename))
							$fileOk = true;
					}
				}
				if ($fileOk){
					$this->_fieldValues[$idv] =  $fieldInfo->filename;
				}else{
					unset($this->_fieldValues[$idv]);
				}
			}
		}


	}
	/*
	   Fonction import� de FLEXIcontent pour g�n�r� les miniatures d'une image
	*/
	protected function imagePhpThumb($origpath, $destpath, $prefix, $filename, $ext, $width, $height, $quality, $size, $crop, $usewm, $wmfile, $wmop, $wmpos)
	{
		$lib = JPATH_SITE . '/components/com_flexicontent/librairies/phpthumb/phpthumb.class.php';
		require_once ($lib);

		unset ($phpThumb);
		$phpThumb = new phpThumb();

		$filepath = $origpath . $filename;

		$phpThumb->setSourceFilename($filepath);
		$phpThumb->setParameter('config_output_format', "$ext");
		$phpThumb->setParameter('w', $width);
		$phpThumb->setParameter('h', $height);
		if ($usewm == 1)
			$phpThumb->setParameter('fltr', 'wmi|' . $wmfile . '|' . $wmpos . '|' . $wmop);

		$phpThumb->setParameter('q', $quality);
		if ($crop == 1)
			$phpThumb->setParameter('zc', 1);

		$output_filename = $destpath . $prefix . $filename ;
		if ($phpThumb->GenerateThumbnail()) {
			return $phpThumb->RenderToFile($output_filename);
		} else {
			return false;
		}
	}
}