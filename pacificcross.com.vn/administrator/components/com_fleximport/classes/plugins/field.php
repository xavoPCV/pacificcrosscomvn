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

class fleximportFieldPlugin {
	public $_fieldParams = null;
	public $_flexiFieldParams = null;
	public $_typeParams = null;
	public $_fieldLabels = '';
	public $_fieldValues = array();
	public $_field = null;
	function __construct($field = null, $typeParams = null)
	{
		$this->_field = $field;
		$this->_typeParams = $typeParams;
		// m�morise les param�tres du champs d'import
		$this->_fieldParams = new JRegistry($this->_field->params);
		// m�morise les param�tres du champs FLEXIcontent
		$this->_flexiFieldParams = new JRegistry($this->_field->fattribs);
		// ent�te du champs dans le fichier d'import
		$this->_fieldLabels = strtolower($this->_field->label);
		// il peut y en avoir plusieurs champs que l'on concatenera par la site
		$this->_fieldLabels = explode(",", $this->_fieldLabels);
		$jlang = JFactory::getLanguage();
		$jlang->load('plg_fleximport_fields_' . $this->_field->field_type, JPATH_ADMINISTRATOR);
	}
	/*
	   D�finit les valeurs du champs
	*/
	function setValues($fieldValues = array())
	{
		// si la gestion des valeurs multiples n'est pas activ�, on ne m�morisera que la premi�re valeur
		if (!$this->_fieldParams->get('value_multiple',1) && count($fieldValues) > 1) $fieldValues = array($fieldValues[0]);
		// gestion du tableau de correspondance des valeurs
		$valueLink = $this->_fieldParams->get('value_link');

		$valueLink1 = array();
		$valueLink2 = array();
		// si le tableau de correspondance inclut une requ�te SQL
		if (strpos($valueLink,'SELECT')===0) {
			$db = JFactory::getDbo();
			$db->setQuery($valueLink);
			$result = $db->loadRowList();
			foreach ($result as $linkValue){
				if (count($linkValue)==2) {
					$valueLink1[] = $linkValue[0]; //valeur du fichier import�
					$valueLink2[] = $linkValue[1]; // valeur �quivalente
				}
			}
		}else{
			$linkValues = explode("%%", $valueLink);
			// s'il n'y a qu'une valeur, on force la cr�ation du tableau
			if (!is_array($linkValues) && $valueLink) $linkValues = $valueLink;
			foreach ($linkValues as $linkValue) {
				$valueTemp = explode("::", $linkValue);
				if (count($valueTemp) == 2) {
					$valueLink1[] = $valueTemp[0]; //valeur du fichier import�
					$valueLink2[] = $valueTemp[1]; // valeur �quivalente
				}
			}
		}
		// si aucune valeur n'est saisie, on force la cr�ation d'un tableau
		if (!count($fieldValues)) $fieldValues = array(0 => "");

		$i = 0;
		// parcours de l'ensemble des valeurs du champs
		while ($i < count($fieldValues)) {
			// si la valeur correspond � la valeur null ou vierge
			if ($fieldValues[$i] == $this->_fieldParams->get('value_null')) {
				// gestion des valeurs par d�faut selon le type de champs (sauf pour les tags et les cat�gories) qui sont g�r�s lors du formatage car il y a plusieurs valeurs par d�faut possible
				$fieldValues[$i] = $this->getDefaultValue();

				// s'il existe des valeurs correspondantes
			} elseif (count($valueLink1) > 0) {
				// recherche de la cl� pour la valeur correspondante
				if (($key = array_search($fieldValues[$i], $valueLink1)) !== false)
					$fieldValues[$i] = $valueLink2[$key];
			}
			// retire les valeurs nulles
			if ($fieldValues[$i] == $this->_fieldParams->get('value_null')) {
				unset($fieldValues[$i]);
			}
			$i++;
		}
		// r�indexe les valeurs
		$this->_fieldValues = array_values($fieldValues);
	}
	/*
	   Charge les valeurs d'un champs pour un article donn�
	*/
	function getFlexicontentValues($itemID = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('value')->from('#__flexicontent_fields_item_relations');
		$query->where('field_id='.(int)$this->_field->flexi_field_id)->where('item_id='.(int)$itemID);
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
	   D�finit les valeurs du champs pour l'export pour un article donn�
	*/
	function loadValues($itemID = 0)
	{
		if (!$itemID) return array();

		$fieldValues = $this->getFlexicontentValues($itemID);
		// si la gestion des valeurs multiples n'est pas activ�, on ne m�morisera que la premi�re valeur
		if (!$this->_fieldParams->get('value_multiple',1) && count($fieldValues) > 1) $fieldValues = array($fieldValues[0]);
		// nettoyage des valeurs � exporter
		if ($cleaner = $this->_fieldParams->get('cleaner')) {
			$cleaner = explode('%%', $cleaner);
			$tempValues = $fieldValues;
			foreach ($tempValues as &$tempValue) {
				foreach ($cleaner as $charToClean) {
					$tempValue = str_replace($charToClean, '', $tempValue);
				}
			}
			$fieldValues = $tempValues;
		}

		// gestion du tableau de correspondance des valeurs
		$valueLink = $this->_fieldParams->get('value_link');
		$valueLink1 = array();
		$valueLink2 = array();
		// si le tableau de correspondance inclut une requ�te SQL
		if (strpos($valueLink,'SELECT')===0) {
			$db = JFactory::getDbo();
			$db->setQuery($valueLink);
			$result = $db->loadRowList();
			foreach ($result as $linkValue){
				if (count($linkValue)==2) {
					$valueLink1[] = $linkValue[0]; //valeur du fichier import�
					$valueLink2[] = $linkValue[1]; // valeur �quivalente
				}
			}
		}else{
			$linkValues = explode("%%", $valueLink);
			// s'il n'y a qu'une valeur, on force la cr�ation du tableau
			if (!is_array($linkValues) && $valueLink) $linkValues = $valueLink;
			foreach ($linkValues as $linkValue) {
				$valueTemp = explode("::", $linkValue);
				if (count($valueTemp) == 2) {
					$valueLink1[] = $valueTemp[0]; //valeur du fichier import�
					$valueLink2[] = $valueTemp[1]; // valeur �quivalente
				}
			}
		}
		// si aucune valeur n'est saisie, on force la cr�ation d'un tableau
		if (!count($fieldValues)) $fieldValues = array(0 => "");

		$i = 0;
		// parcours de l'ensemble des valeurs du champs
		while ($i < count($fieldValues)) {
			// g�re les valeurs par d�faut et les valeurs nulles, sauf pour les �l�ments s�rialis�s (� g�rer au niveau des plugins de champs)
			if (!is_array($fieldValues[$i])) {
				// si la valeur correspond � la valeur null ou vierge
				if ($fieldValues[$i] == $this->_fieldParams->get('value_null')) {
					// gestion des valeurs par d�faut selon le type de champs (sauf pour les tags et les cat�gories) qui sont g�r�s lors du formatage car il y a plusieurs valeurs par d�faut possible
					// si la m�thode existe dans l'extension du plugin on y fait appel
					$fieldValues[$i] = $this->getDefaultValue();
					// s'il existe des valeurs correspondantes
				} elseif (count($valueLink1) > 0) {
					// recherche de la cl� pour la valeur correspondante
					if (($key = array_search($fieldValues[$i], $valueLink2)) !== false)
						$fieldValues[$i] = $valueLink1[$key];
				}
				// retire les valeurs nulles
				if ($fieldValues[$i] == "") {
					unset($fieldValues[$i]);
				}
			}
			$i++;
		}
		// r�indexe les valeurs
		$this->_fieldValues = array_values($fieldValues);
	}
	/*
	   Retourne les valeurs
	*/
	function getValues()
	{
		return $this->_fieldValues;
	}
	/*
	   Retourne une des propri�t�s du champs
	*/
	function get($field)
	{
		return $this->_field-> {
			$field} ;
	}
	/*
	   Formate les donn�es pour pr�parer le post
	*/
	function getPostValues()
	{
			if ($this->_field->iscore) {
				$post['jform'][$this->_field->fname] = $this->_fieldValues;
			} else {
				$post['custom'][$this->_field->fname] = $this->_fieldValues;
			}

		return $post;
	}
	/*
	   Format la valeur du champs
	*/
	function formatValues()
	{
		return true;
	}
	/*
	   Format la valeur du champs
	*/
	function formatValuesExport()
	{
		return true;
	}
	/*
	   Formate les donn�es pour l'export
	*/
	function getExportValues()
	{
		return true;
	}
	/*
	   Retourne la valeur par d�faut pour le champs
	*/
	function getDefaultValue()
	{
		return $this->_fieldParams->get('value_default');
	}
	/*
	   Permet de rechercher et de copier un fichier dans le r�pertoire temporaire pour le traiter
	*/
	function localizeFiles($fullname = "" , $filename = "" , $localisation = "local", $server = "", $address = "", $username = "", $password = "", $port = "21")
	{
		$fileUnzipped = false;
		// cr�ation du r�pertoire temporaire
		$pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/');
		if (!JFolder::exists($pathTemp)) JFolder::create($pathTemp);
		if (!JFolder::exists($pathTemp)) return false;
		$return = false;

		$importArchive = $this->_typeParams->get('archive');

		$importDelete = $this->_typeParams->get('delete', 0);

		$filenameSafe = JFile::makeSafe($filename);

		if (JFile::exists($pathTemp .'files'. '/' . $filenameSafe))
			$fileUnzipped=true;

		// si le fichier est d�j� pr�sent dans le r�pertoire temporaire (exemple dans le cas d'un zip)
		if (JFile::exists($pathTemp . $filenameSafe) || $fileUnzipped) {
			$return = true;
		} else {
			switch ($localisation) {
				case "local":
					// si le premier caract�re du chemin du fichier n'est pas un slash, on utilisera le chemin relatif du site
					if (substr($address, 0, 1) != '/')
						$address = JPATH_SITE . '/' . $address;
					if (!JFile::exists($address . $fullname)) return false;
					// copie dans le r�pertoire temporaire pour lancer le traitement par la suite
					if (JFile::copy($address . $fullname, $pathTemp . $filenameSafe)) {
						$return = true;
						// Si le fichier a besoin d'�tre supprim�
						if ($importDelete)
							JFile::delete($address . $fullname);
					}
					break;
				case "ftp":
					jimport('joomla.client.ftp');
					// connection au FTP
					$clientFTP = JClientFtp::getInstance($server, $port, null, $username, $password);

					if ($clientFTP->isConnected()) {
						// copie dans le r�pertoire temporaire pour lancer le traitement par la suite
						if ($clientFTP->get($pathTemp . $filenameSafe , $address . $fullname)) {
							$return = true;
							// Si le fichier doit �tre supprim�
							if ($importDelete)
								$clientFTP->delete($address . $fullname);
						}
						$clientFTP->quit();
					}
					break;
				case "web":
					if ($this->_fieldParams->get('raw_value', '0')) {
						$fileWeb = $server . $address . $fullname;
					}else{
						$fileWeb = $server . rawurlencode($address . $fullname);
					}

					if (strpos('http', substr($fileWeb, 0, 4)) === false)
						$fileWeb = 'http://' . $fileWeb;
					if (@fopen($fileWeb, "r")) {
						if (copy($fileWeb, $pathTemp . $filenameSafe))
							$return = true;
					}
					break;
			}
			// si le fichier doit �tre archiv�, on fait une copie de l'ensemble des fichiers vers le r�pertoire d'archive
			// on n'archive pas les fichiers extrait d'un zip
			if ($importArchive && !$fileUnzipped)
				$this->archiveFile($filenameSafe);
		}
		return $return ;
	}
	/*
	   Permet de savoir si le fichier existe d�j� o� non flexicontent
	*/
	function fileExist($filename = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		// v�rifie si l'image par d�faut existe bien
		$query->select('id')->from('#__flexicontent_files');
		$query->where('published = 1')->where('filename = ' . $db->quote($filename));
		$db->setQuery($query);
		return $db->loadResult();
	}
	/*
	   Renvoit les informations d'un fichier
	*/
	function fileInfo($fileID = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		// v�rifie si l'image par d�faut existe bien
		$query->select('*')->from('#__flexicontent_files');
		$query->where('published = 1')->where('id = ' . (int)$fileID);
		$db->setQuery($query);
		return $db->loadObject();
	}
	/*
	   Nettoie le nom du fichier
	*/
	function sanitizeFilename($filename = '')
	{
		if ($filename) {
			$filename = preg_replace("/^[.]*/", '', $filename);
			$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above
			// we need to save the last dot position cause preg_replace will also replace dots
			$lastdotpos = strrpos($filename, '.');
			// replace invalid characters
			$chars = '[^0-9a-zA-Z()_-]';
			$filename = strtolower(preg_replace("/$chars/", '-', $filename));

			$beforedot = substr($filename, 0, $lastdotpos);
			$afterdot = substr($filename, $lastdotpos + 1);

			$filename = $beforedot . '.' . $afterdot;
		}
		return $filename;
	}
	/*
	   Archive le fichier
	*/
	function archiveFile($filename = '')
	{
		$pathTemp = JPATH_SITE . '/' . $this->_typeParams->get('temp_path', 'tmp/fleximport/');

		$importArchivePath = $this->_typeParams->get('archive_path', 'images/fleximport/archives/');
		// si le premier caract�re du chemin du fichier n'est pas un slash, on utilisera le chemin relatif du site
		if (substr($importArchivePath, 0, 1) != '/') {
			$importArchivePath = JPATH_SITE . '/' . $importArchivePath;
		}
		if (!JFolder::exists($importArchivePath)) {
			JFolder::create($importArchivePath);
		}
		if (JFile::copy($pathTemp . $filename, $importArchivePath . date('YmdGis') . '_' . $filename)) {
			return true;
		}
		return false;
	}
	function getModel($model = "")
	{
		if (!class_exists('FlexicontentModel' . $model)) {
			// Build the path to the model based upon a supplied base path
			$path = JPATH_BASE . '/components/com_flexicontent/models/' . strtolower($model) . '.php';
			// If the model file exists include it and try to instantiate the object
			if (file_exists($path)) {
				require_once($path);
				if (!class_exists('FlexicontentModel' . $model)) {
					return false;
				}
			} else {
				return false;
			}
		}
		$modelName = 'FlexicontentModel' . $model;
		$model = new $modelName();
		return $model;
	}
}