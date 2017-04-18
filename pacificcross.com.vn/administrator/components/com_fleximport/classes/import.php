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
jimport('joomla.utilities.date');

class fleximportImport extends fleximportBase {
	private $_files = null; // Liste des fichiers � traiter pour l'import
	private $_nbAjaxStep1 = null;
	private $_nbAjaxStep2 = null;

	function __construct($data = null, $dataFile = null)
	{
		$session = JFactory::getSession();
		$this->_firstLoad = ($data['task'] == 'start' || $data['task'] == 'cron');
		if (($data && !$this->_type) or !$this->_firstLoad) {
			if (!$this->_firstLoad) {
				$data = $session->get("data", null, "fleximport");
				$dataFile = $session->get("dataFile", null, "fleximport");
			} else {
				// on r�initialise les variables de session dans le cas o� le dernier import ne serait pas terminer normalement
				unset($_SESSION["__fleximport"]);
				unset($_SESSION["__fleximport_response"]);
			}
			$this->_mainClass = 'IMPORT';
			// on charge le fichier langue suppl�mentaire
			$this->_type = $data['type_id'];
			// chargement du nom du fichier uniquement si c'est une methode manuelle
			$this->_method = $data['import_method'];
			parent::__construct();

			$cparams = JComponentHelper::getParams('com_fleximport');
			$this->_nbAjaxStep1 = (int) $cparams->get('ajax_import_step1', 20);
			$this->_nbAjaxStep2 = (int) $cparams->get('ajax_import_step2', 20);

			if ($this->_firstLoad) {
				if ($this->_method == "manual" && $dataFile) {
					$this->_files = $dataFile;
				} elseif ($this->_method == "cron") {
					$session->set("cronTask", true, "fleximport");
				}
				// Initialisation de la base donn�e temporaire
				$this->initTmpDB();
				// m�morise les donn�es pour le traitement ajax
				$session->set("dataFile", $dataFile, "fleximport");
				$session->set("data", $data, "fleximport");
				$session->set("step", 0, "fleximport");
			} else {
				$this->_files = $session->get("files", null, "fleximport");
			}
			return true;
		}
		return false;
	}
	function __destruct()
	{
		// traitement uniquement si la tache ajax est termin�e
		$session = JFactory::getSession();
		if ($session->get("finish", false, "fleximport")) {
			$db = JFactory::getDbo();
			$query = 'DROP TABLE IF EXISTS #__fleximport_tmp_import_raw';
			$db->setQuery($query);
			$db->execute();
			$query = 'DROP TABLE IF EXISTS #__fleximport_tmp_import_format';
			$db->setQuery($query);
			$db->execute();
			// supprime toutes les valeurs de session
			unset($_SESSION["__fleximport"]);
		}
		parent::__destruct();
	}
	public function process()
	{
		$session = JFactory::getSession();
		$return = false;
		if ($session->get("step", 0, "fleximport") == 0) {
			$return = $this->parsefile();
		}
		if ($session->get("step", 0, "fleximport") == 1) {
			$return = $this->parseData();
		}
		if ($session->get("step", 0, "fleximport") == 2) {
			$return = $this->importData();
		}
		if ($session->get("step", 0, "fleximport") == 3) {
			$this->sendLog();
		}
		return $return;
	}
	/*
	   *  M�thode pour localiser les fichiers � traiter
	*/
	private function localizeFiles()
	{
		// cr�ation du r�pertoire temporaire
		if (!JFolder::exists($this->_pathToTemp)) JFolder::create($this->_pathToTemp);
		if (!JFolder::exists($this->_pathToTemp)) return false;

		$return = false;

		$importArchive = $this->_params->get('archive');
		// import manuel
		if ($this->_method == "manual" && $this->_files) {
			$fileList = array();
			// upload des fichiers vers le r�pertoire temporaire
			foreach ($this->_files as $file) {
				$filename = JFile::makeSafe($file['name']);
				JFile::upload($file['tmp_name'], $this->_pathToTemp . $filename);
				$fileList[] = $filename;
				$this->logImport("FILE_GOTOTEMP", $filename);
				if ($importArchive) $this->archiveFile($filename);
			}
			// m�morise la liste des fichiers d'import � traiter
			$this->_files = $fileList;
			$return = true;
			// import automatique
		} elseif (($this->_method == "auto" || $this->_method == "cron") && !$this->_files) {
			// m�mmorise le bon path pour le fichier
			$importAddress = $this->_params->get('address');
			// expression logique pour trouver les fichiers
			if ($this->_params->get('filter')) {
				$importFilename = $this->_params->get('filter');
			} else {
				$importFilename = $this->_params->get('filename', JText::_('COM_FLEXIMPORT_DEFAULT_FILENAME')) ;
			}

			$importDelete = $this->_params->get('delete', 0);

			$filesList = array();
			switch ($this->_params->get('localisation')) {
				case "local":
					// si le premier caract�re du chemin du fichier n'est pas un slash, on utilisera le chemin relatif du site
					if (substr($importAddress, 0, 1) != '/') {
						$importAddress = JPATH_SITE . '/' . $importAddress;
					}
					if (JFolder::exists($importAddress)) {
						// on liste l'ensemble des fichiers du r�pertoire
						$filesList = JFolder::files($importAddress);
					}
					foreach ($filesList as $fileToImport) {
						if (preg_match('/' . $importFilename . '/', $fileToImport)) {
							$filename = JFile::makeSafe($fileToImport);
							// copie dans le r�pertoire temporaire pour lancer le traitement par la suite
							if (JFile::copy($importAddress . $fileToImport, $this->_pathToTemp . $filename)) {
								// on le garde en m�moire
								$this->_files[] = $filename;
								$this->logImport("FILE_GOTOTEMP", $filename);
								$return = true;
								// Si le fichier a besoin d'�tre supprim�
								if ($importDelete) {
									if (JFile::delete($importAddress . $fileToImport)) {
										$this->logImport("FILE_DELETED", $importAddress . $filename);
									} else {
										$this->logImport("ERROR_DELETE", $importAddress . $filename);
									}
								}
							} else {
								$this->logImport("ERROR_COPY", $importAddress . $filename);
							}
						}
					}
					break;
				case "ftp":
					jimport('joomla.client.ftp');
					$importUsername = $this->_params->get('username');
					$importPassword = $this->_params->get('password');
					$importPort = $this->_params->get('port', '21');
					$importServer = $this->_params->get('server');
					// connection au FTP
					$clientFTP = JClientFtp::getInstance($importServer, $importPort, null, $importUsername, $importPassword);

					if ($clientFTP->isConnected()) {
						$this->logImport("CONNECTED_FTP");
						// on liste l'ensemble des fichiers du r�pertoire
						$filesList = $clientFTP->listNames($importAddress);
						foreach ($filesList as $fileToImport) {
							if (preg_match('/' . $importFilename . '/', $fileToImport)) {
								$filename = JFile::makeSafe($fileToImport);
								// copy to temp directory to process after
								if ($clientFTP->get($this->_pathToTemp . $filename , $importAddress . $fileToImport)) {
									// on le garde en m�moire
									$this->_files[] = $filename;
									$this->logImport("FILE_GOTOTEMP", $filename);
									$return = true;
									// Si le fichier doit �tre supprim�
									if ($importDelete) {
										if ($clientFTP->delete($fileToImport)) {
											$this->logImport("FILE_DELETED", $importAddress . $filename);
										} else {
											$this->logImport("ERROR_DELETE", $importAddress . $filename);
										}
									}
								} else {
									$this->logImport("ERROR_COPY", $importAddress . $filename);
								}
							}
						}
						$clientFTP->quit();
					} else {
						$this->logImport("ERROR_CONNECT_FTP");
					}
					break;
				case "web":
					$importServer = $this->_params->get('server');
					$filename = "http://" . $importServer . rawurlencode($importAddress . $importFilename);
					if (@fopen($filename, "r")) {
						$filenameDest = JFile::makeSafe($importFilename);
						if (@copy($filename, $this->_pathToTemp . $filenameDest)) {
							$this->_files[] = $filenameDest;
							$this->logImport("FILE_GOTOTEMP", $filenameDest);
							$return = true;
						} else {
							$this->logImport("ERROR_COPY", $filename);
						}
					} else {
						$this->logImport("ERROR_FILE_NOEXIST", $filename);
					}
					break;
			}
			// si le fichier doit �tre archiv�, on fait une copie de l'ensemble des fichiers vers le r�pertoire d'archive
			if ($importArchive && $this->_files) {
				foreach ($this->_files as $file) {
					$this->archiveFile($file);
				}
			}
		}
		return $return ;
	}
	/*
	   M�thode pour d�compacter les archives si c'est un fichier zip.
	*/
	private function UnZipFile()
	{
		// parcours l'ensemble des fichiers
		foreach ($this->_files as $i => $file) {
			$extension = JFile::getExt($file);
			// m�morise le nom du fichier � trouver, il correspond au nom de l'archive + le format d'import
			$fileToFound = JFile::stripExt($file) . "." . $this->_type_format;
			// si c'est le premier chargement on va d�compresser l'archive
			if ($this->_firstLoad) {
				switch ($extension) {
					// pour le moment on ne traite que les zips
					case 'zip':
						$archiveValid = false;
						$zip = new ZipArchive;
						if ($zip->open($this->_pathToTemp . $file) === true) {
							// si le fichier d'import est bien trouv� dans l'archive
							if ($zip->locateName($fileToFound, ZIPARCHIVE::FL_NOCASE) !== false) {
								$archiveValid = true;
								// on d�compresse tout dans le chemin temporaire
								$zip->extractTo($this->_pathToTemp);
								$this->logImport("UNZIP_DONE", $file);
								// m�morise le nouveau fichier � traiter
								$this->_files[$i] = $fileToFound;
							}
							$zip->close();
						}
						if (!$archiveValid) {
							// on supprime le fichier afin de ne pas le traiter par la suite
							$this->logImport("NO_ZIP_FILE", $file);
							unset($this->_files[$i]);
							JFile::delete($this->_pathToTemp . $file);
						}
						break;
				}
			} else {
				// on met � jour le nom du fichier
				$this->_files[$i] = $fileToFound;
			}
		}
		return true;
	}
	/*
	   M�thode pour d�terminer l'action � mener pour un enregistrement
	   $importFile : objet fleximportPluginType
	   $index : index de l'enregistrement � traiter
	*/
	private function getDataAction($importFile = null , $index = 0)
	{
		// m�morisation des actions � mener
		$fieldActionAddAllow = $this->_params->get("allow_add", 1);
		$fieldActionUpdateAllow = $this->_params->get("allow_update", 1);
		$fieldActionDeleteAllow = $this->_params->get("allow_delete", 1);
		$fieldLinkedItemID = $this->_params->get("field_linked_id", 1);
		$fieldLinked = $this->_params->get("field_linked", "");
		$fieldAction = $this->_params->get("field_action", "");
		$fieldActionAdd = $this->_params->get("field_action_value_add", "");
		$fieldActionUpdate = $this->_params->get("field_action_value_update", "");
		$fieldActionDelete = $this->_params->get("field_action_value_delete", "");

		$dataAction = array();
		$fieldLinkedValue = array();
		$fieldActionValue = array();
		$fieldLinkedID = null;
		// recherche de l'identifiant uniquement pour le champs li�
		if ($fieldLinked) {
			// r�cup�ration de la valeur du champs li� dans le fichier
			$fieldLinked = $this->getFleximportField($fieldLinked);
			if ($fieldLinked) {
				$fieldLinkedValue = $importFile->getValues($index, array($fieldLinked->label));
				if (count($fieldLinkedValue)) {
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					if ($fieldLinkedItemID) {
						$query->select('id')->from('#__content')->where('id = ' . $db->quote($fieldLinkedValue[0]));
					}elseif ($fieldLinked->field_type == 'title') {
						$query->select('id')->from('#__content')->where('title = ' . $db->quote($fieldLinkedValue[0]));
					} else {
						$textPrefix = '';
						$textSuffix = '';
						if ($fieldLinked->field_type == 'text') {
							$fieldParams = new JRegistry($fieldLinked->params);
							$textPrefix = $fieldParams->get('text_start');
							$textSuffix = $fieldParams->get('text_end');
						}
						$query->select('distinct f.item_id')->from('#__flexicontent_fields_item_relations AS f');
						$query->innerJoin('#__flexicontent_items_ext AS i ON f.item_id=i.item_id');
						$query->where('f.value = ' . $db->quote($textPrefix . $fieldLinkedValue[0] . $textSuffix));
						$query->where('f.field_id = ' . (int)$fieldLinked->flexi_field_id);
						$query->where('i.type_id = ' . (int)$this->_flexi_type_id);
					}
					$db->setQuery($query, 0, 1);
					$fieldLinkedID = $db->loadResult();
				}
			}
		}
		// si le champs de gestion des actions n'a pas �t� d�fini et que l'ID n'a pas �t� trouv� ca sera forc�ment un ajout
		if (!$fieldAction && !$fieldLinkedID) {
			$dataAction['action'] = 1;
			// pas d'enregistrement li� pour l'ajout
			$dataAction['id'] = 0;
			// si le champs action n'est pas renseign� mais qu'il y a un ID de trouver alors ca sera une modification
		} elseif (!$fieldAction && $fieldLinkedID) {
			$dataAction['action'] = 2;
			// pas d'enregistrement li� pour l'ajout
			$dataAction['id'] = $fieldLinkedID;
		} else {
			// r�cup�ration de la valeur du champs action dans le fichier
			$fieldAction = $this->getFleximportField($fieldAction);
			$fieldActionValue = $importFile->getValues($index, array($fieldAction->label));
			// selon la valeur du champs d'action
			switch ($fieldActionValue[0]) {
				case $fieldActionAdd:
					$dataAction['action'] = 1;
					$dataAction['id'] = 0;
					break;
				case $fieldActionUpdate:
					$dataAction['action'] = 2;
					$dataAction['id'] = $fieldLinkedID;
					break;
				case $fieldActionDelete:
					$dataAction['action'] = 3;
					$dataAction['id'] = $fieldLinkedID;
					break;
				default:
					$dataAction['action'] = 0;
					$dataAction['id'] = 0;
			}
		}
		// v�rification des droits sur les diff�rentes actions
		switch ($dataAction['action']) {
			case 1:
				if (!$fieldActionAddAllow) {
					$dataAction['action'] = 0;
					$dataAction['id'] = 0;
				}
				break;
			case 2:
				if (!$fieldActionUpdateAllow or !$dataAction['id']) {
					$dataAction['action'] = 0;
					$dataAction['id'] = 0;
				} ;
				break;
			case 3:
				if (!$fieldActionDeleteAllow or !$dataAction['id']) {
					$dataAction['action'] = 0;
					$dataAction['id'] = 0;
				} ;
				break;
			default:
				$dataAction['action'] = 0;
				$dataAction['id'] = 0;
		}

		return $dataAction;
	}
	/*
	   M�thode pour extraire les donn�es du fichier
	*/
	private function parsefile()
	{
		$session = JFactory::getSession();
		// seulement si l'inialisation a bien �t� r�alis�
		if ($this->_type && $this->_type_format) {
			if ($session->get("step0First", false, "fleximport")) {
				$stepFileAjax = $session->get("ParsedFile", 0, "fleximport");
				$localizeFiles = (count($this->_files) > 0);
			} else {
				$localizeFiles = $this->localizeFiles();
				$session->set("files", $this->_files, "fleximport");
				// intialise pour forcer le premier traitement
				$session->set("ParsedFile", 0, "fleximport");
				$stepFileAjax = -1;
			}
			// localiser les fichiers � importer
			if (!$localizeFiles) {
				$this->logImport("ERROR_LOCALISE_FILES");
				return false;
			} else {
				if (!count($this->_files)) {
					$this->logImport("TYPE_NO_EXIST");
					return false;
				}
				// d�compresse le fichier si c'est un fichier zip
				$this->UnZipFile();
				// v�rifie si le type que l'on va traiter existe bien
				if (!JFile::exists(JPATH_COMPONENT . '/classes/plugins/types/' . $this->_type_format . '.php')) {
					$this->logImport("NO_FILES_TO_IMPORT");
					return false;
				}
				require_once(JPATH_COMPONENT . '/classes/plugins/types/' . $this->_type_format . '.php');
				// parcours de la liste des fichiers � importer
				$importFiles = array();
				foreach ($this->_files as $file) {
					$importFiles[] = new fleximportPluginType($file, $this->_pathToTemp, $this->_attribs);
				}

				if (!$session->get("step0First", false, "fleximport")) FleximportHelper::JSONReturn(JText::_('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_TITLE'));
				foreach ($importFiles as $keyFile => &$importFile) {
					// uniquement si le fichier n'a pas encore �t� trait�
					if ($stepFileAjax <= $keyFile) {
						// si le fichier n'a pas �t� encore pars�
						if ($session->get("ParsedFile", 0, "fleximport") == $keyFile) {
							// parse de mani�re brute les donn�es du fichier � importer (ligne par ligne)
							$importFile->parseData();
						}
						// m�morise le dernier fichier trait�
						$session->set("ParsedFile", ($keyFile + 1), "fleximport");
						// permet de lib�rer de la m�moire
						unset($importFile);
					}
				}
			}
		}
		// s'il n'y a aucune donn�e � traiter
		if (!$this->countData("raw")) {
			$this->logImport("ERROR_NO_DATAS");
			return false;
		} else {
			// indique que le parsing du fichier est termin�
			$session->set("step", 1 , "fleximport");
			return true;
		}
	}
	/*
	   m�thode pour enregistrer les donn�es dans un tableau format�
	*/
	private function parseData()
	{
		$session = JFactory::getSession();
		// seulement si l'inialisation a bien �t� r�alis�
		if ($this->_type && $this->_type_format) {
			if ($session->get("step1First", false, "fleximport")) {
				$stepAjax = $session->get("stepParse", 1, "fleximport");
				$idData = $session->get("idData", 0, "fleximport");
			} else {
				// intialise pour forcer le premier traitement
				$session->set("stepParse", 0, "fleximport");
				$session->set("idData", 0, "fleximport");
				$stepAjax = 1;
				$idData = 0;
			}
			if (!count($this->_files)) {
				$this->logImport("TYPE_NO_EXIST");
				return false;
			}
			// v�rifie si le type que l'on va traiter existe bien
			if (!JFile::exists(JPATH_COMPONENT . '/classes/plugins/types/' . $this->_type_format . '.php')) {
				$this->logImport("NO_FILES_TO_IMPORT");
				return false;
			}
			require_once(JPATH_COMPONENT . '/classes/plugins/types/' . $this->_type_format . '.php');
			// cr�� l'objet en fonction du type charg� sur un fichier virtuel, cela permet juste d'exploiter les m�thodes
			$importFile = new fleximportPluginType('fake', $this->_pathToTemp, $this->_attribs);
			// liste des champs pour l'import
			$fields = $this->getFleximportFields();

			if (!$session->get("step1First", false, "fleximport")) FleximportHelper::JSONReturn(JText::_('COM_FLEXIMPORT_IMPORT_AJAX_STEP1_TITLE'));
			// compte le nombre total d'enregistrement brute de la base de donn�e
			$totalRaw = $this->countData("raw");
			$db = JFactory::getDbo();
			for ($i = $stepAjax ; $i <= $totalRaw ;$i++) {
				// s'il y a bien une action � r�aliser pour cet enregistrement
				$dataAction = $this->getDataAction($importFile, $i);
				if ($dataAction['action']) {
					// parcours de l'ensemble des champs de l'import
					$query = array();
					foreach ($fields as &$field) {
						if (JFile::exists(JPATH_COMPONENT . '/classes/plugins/fields/' . $field->field_type . '.php')) {
							require_once (JPATH_COMPONENT . '/classes/plugins/fields/' . $field->field_type . '.php');
						} else {
							// on ne stocke pas la valeur de l'enregistrement et on ajoute une erreur dans le log
							$this->logImport("ERROR_PLUGIN_FIELD", JText::sprintf('COM_FLEXIMPORT_LOG_ERROR_REQUIRED_FIELD_DETAIL', $field->field_type, $i));
							$idData--;
							break;
						}
						// formatage des valeurs du champ
						$fieldImportClass = 'fleximportPlugin' . $field->field_type;
						$fieldImport = new $fieldImportClass($field, $this->_params);
						$fieldValues = array();
						// r�cup�ration des valeurs en fonction du type de fichier
						$fieldLabels = explode(",", $field->label);
						$fieldValues = $importFile->getValues($i, $fieldLabels);

						// r�cup�re les valeurs par d�faut du champs si c'est une MAJ
						if (!$fieldValues && $dataAction['action'] == 2 && $dataAction['id'])
							$fieldValues = $fieldImport->getFlexicontentValues($dataAction['id']);
						// nettoyage des valeurs et/ou split des donn�es
						$cleaner = $fieldImport->_fieldParams->get('cleaner', '');
						$spliter = $fieldImport->_fieldParams->get('spliter', '');
						$trimValue = $fieldImport->_fieldParams->get('value_trim', '0');
						$deleteNull = $fieldImport->_fieldParams->get('value_null_delete', '0');
						if ($spliter && $fieldImport->_fieldParams->get('value_multiple', 1)) {
							$newValues = array();
							foreach ($fieldValues as $fieldValue) {
								$newValues = array_merge($newValues, explode($spliter, $fieldValue));
							}
							$fieldValues = $newValues;
						}
						if ($cleaner || $trimValue || $deleteNull) {
							if ($cleaner) {
								$cleaner = explode('%%', $cleaner);
							} else {
								$cleaner = array();
							}
							foreach ($fieldValues as &$fieldValue) {
								if ($trimValue)$fieldValue = trim($fieldValue);
								if ($deleteNull && !$fieldValue) continue;
								foreach ($cleaner as $charToClean) {
									$charCleanReplace = '';
									$charToClean = explode('::', $charToClean);
									// si on souhaite utilis� un caract�re sp�ciale pour le remplacement
									if (count($charToClean) == 2) {
										$charCleanReplace = $charToClean[1];
										$charToClean = $charToClean[0];
									} else {
										$charToClean = $charToClean[0];
									}
									$fieldValue = str_replace($charToClean, $charCleanReplace, $fieldValue);
								}
							}
						}
						// d�finition et formatage des valeurs
						$fieldImport->setValues($fieldValues);
						$fieldImport->FormatValues();
						// formate pour le post
						$postValue = $fieldImport->getPostValues();
						$fValue = $fieldImport->getValues();

						// 0 peut �tre une valeur, notament par exemple pour le champs state
						if ($fValue === '' || (is_array($fValue) && count($fValue)==0) || (is_array($fValue) && count($fValue)==1 && @$fValue[0]==='')) {
							// si c'est un champs obligatoire et que ce n'est pas une suppression
							if ($fieldImport->get('isrequired') && $dataAction['action'] != 3) {
								// on ne stocke pas la valeur de l'enregistrement et on ajoute une erreur dans le log
								$query = array();
								$this->logImport("ERROR_REQUIRED_FIELD", JText::sprintf('COM_FLEXIMPORT_LOG_ERROR_REQUIRED_FIELD_DETAIL', $i, $field->name));
								$idData--;
								break;
							}
						}
						$query[] = '("",' . $db->quote($idData) . ',' . $db->quote($field->ordering) . ',' . $db->quote($field->flexi_field_id) . ',' . $db->quote(serialize($postValue)) . ',' . $db->quote($dataAction['action']) . ',' . $db->quote($dataAction['id']) . ')';
						unset($fieldImport, $field);
					}
					if (count($query)) {
                        try{
                            $query = "INSERT INTO #__fleximport_tmp_import_format VALUES " . implode(',', $query);
                            $db->setQuery($query);
                            $db->execute();
                        }catch (Exception $e){
                            FleximportHelper::debug($e->getMessage(), 'Insert in TMP formated');
                        }
					}
					// passage � la donn�e suivante
					$idData++;
				} else {
					$this->logImport("ERROR_IGNORED", $i);
				}
				if ((is_int($i / $this->_nbAjaxStep1)) && ($i < $totalRaw)) {
					FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP1_LINE', $i));
					// on m�morise les derniers param�tres
					$session->set("stepParse", ($i + 1), "fleximport");
					$session->set("idData", $idData, "fleximport");
					FleximportHelper::AjaxReload(true, $this->_method);
				}
			}
			FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP1_LINE', ($i - 1)));
		}
		// s'il n'y a aucune donn�e � traiter
		if (!$this->countData()) {
			$this->logImport("ERROR_NO_DATAS");
			return false;
		} else {
			// indique que le parsing du fichier est termin�
			$session->set("step", 2, "fleximport");
			return true;
		}
	}
	/*
	   M�thode pour importer les donn�es dans FLEXIcontent
	*/
	private function importData()
	{
		$session = JFactory::getSession();
		// aucun traitement si les donn�es n'ont pas �t� pr�par�es au pr�alable
		if (!$nbData = $this->countData()) {
			$this->logImport("ERROR_NO_DATAS");
			return false;
		}
		if ($session->get("step2First", false, "fleximport")) {
			$addError = $session->get("addError", 0, "fleximport");
			$addSuccess = $session->get("addSuccess", 0, "fleximport");
			$updateError = $session->get("updateError", 0, "fleximport");
			$updateSuccess = $session->get("updateSuccess", 0, "fleximport");
			$deleteError = $session->get("deleteError", 0, "fleximport");
			$deleteSuccess = $session->get("deleteSuccess", 0, "fleximport");
			$stepAjax = $session->get("stepImport", 0, "fleximport");
		} else {
			$addError = 0;
			$addSuccess = 0;
			$updateError = 0;
			$updateSuccess = 0;
			$deleteError = 0;
			$deleteSuccess = 0;
			$stepAjax = 0;
			FleximportHelper::JSONReturn(JText::_('COM_FLEXIMPORT_IMPORT_AJAX_STEP2_TITLE'));
		}
		// m�morise la liste des champs flexicontent
		$flexiFields = $this->getFlexicontentFields();
		$db = JFactory::getDbo();
		// formatage des donn�es au format post pour simuler une saisie
		for($i = $stepAjax; $nbData > $i ;$i++) {
			$query = $db->getQuery(true);
			$query->select('*')->from('#__fleximport_tmp_import_format')->where('id_data=' . (int)$i);
			$db->setQuery($query);
			$data = $db->loadObjectList();
			$tmpFlexiFields = $flexiFields;
			unset($actionType, $actionID);
			$post = array();
			// pour chaque donn�es � ins�rer
			foreach ($data as $field) {
				$actionType = $field->action_value;
				$actionID = $field->action_field;
				$post = array_merge_recursive($post, unserialize($field->value));
				// on indique que le champs a �t� trait� dans le post
				if (($indexField = array_search($field->flexifield_id, $tmpFlexiFields)) !== false)
					unset($tmpFlexiFields[$indexField]);
			}
			// si la langue n'a pas �t� d�fini, on affecte la langue par d�faut de FLEXIcontent
			if (!isset($post['jform']['language']))
				$post['jform']['language'] = '*';
			// on associe les valeurs g�n�rales
			$post['jform']['type_id'] = $this->_flexi_type_id;
            JAccess::getActionsFromFile(JPATH_ADMINISTRATOR . '/components/com_flexicontent/access.xml');
			// h�rite automatiquement des droits par d�faut
			$flexi_rules = array('core.delete', 'core.edit', 'core.edit.state');
			foreach ($flexi_rules as $flexi_rule) {
				$post['jform']['rules'][$flexi_rule] = array();
			}
			// pas de gestion des metadata pour le moment
			$post['jform']['metadesc'] = array();
			$post['jform']['metakey'] = array();
			$post['jform']['metadata'] = array();
			// approuve automatiquement l'ensemble des contenus
			$post['jform']['vstate'] = 2;
			$post['jform']['id'] = '';
			if (!isset($post['jform']['publish_up']))
				$post['jform']['publish_up'] = JFactory::getDate()->toSql();
			// ajout du token
			$post[JSession::getFormToken()] = '1';
			// FleximportHelper::debug($post, "flexIMPORT - Store ");
			$db = JFactory::getDbo();
			switch ($actionType) {
				case 1: // ajout
					// cr�ation des valeurs vide pour les champs non d�finis
					/* ce n'est plus utile
					   foreach ($tmpFlexiFields as $tmpFlexiField) {
					   $flexiFieldName = $this->getFlexicontentFieldName($tmpFlexiField);
					   if ($this->getFlexicontentFieldIsCore($tmpFlexiField)) {
					   if ($this->getFlexicontentFieldIsMultiple($tmpFlexiField)) {
					   $post['jform'][$flexiFieldName] = array();
					   }else{
					   $post['jform'][$flexiFieldName] = '';
					   }
					   } else {
					   if ($this->getFlexicontentFieldIsMultiple($tmpFlexiField)) {
					   $post['custom'][$flexiFieldName] = array();
					   }else{
					   $post['custom'][$flexiFieldName] = '';
					   }
					   }
					   }*/
                    JRequest::set($post,'post');

					$model = $this->getModel('item');
					$post = JFactory::getApplication()->input->get('jform', array(), 'array');
					$post['custom'] = JFactory::getApplication()->input->get('custom', array(), 'array');
					$post['jfdata'] = JFactory::getApplication()->input->get('jfdata', array(), 'array');
					$post['jform']['created'] = JHtml::_('date', 'now', 'Y-m-d H:i:s');
					if (!$model->store($post)) {
						$this->logImport("STORE_ADD_ERROR", implode(", ", $model->getErrors()));
						$addError++;
					} else {
						$getID = $model->getId();

						$this->logImport("STORE_ADD_SUCCESS", $getID);
						$addSuccess++;
					}
					break;
				case 2: // modification
					// permet de ne pas supprimer les valeurs d�j� stock�s
					foreach ($tmpFlexiFields as $tmpFlexiField) {
						$flexiFieldName = $this->getFlexicontentFieldName($tmpFlexiField);
						$flexiFieldValue = $this->getFlexicontentFieldValue($tmpFlexiField, $actionID);
						if ($this->getFlexicontentFieldIsCore($tmpFlexiField)) {
							$post['jform'][$flexiFieldName] = $flexiFieldValue;
						} else {
							$post['custom'][$flexiFieldName] = $flexiFieldValue;
						}
					}
                    $post['jform']['state']= -3;
					$post['jform']['id'] = $actionID;
					// simulation du post
                    $_POST = $post;

					$model = $this->getModel('item');
					$post = JFactory::getApplication()->input->get('jform', array(), 'array');
					$post['custom'] = JFactory::getApplication()->input->get('custom', array(), 'array');
					$post['jfdata'] = JFactory::getApplication()->input->get('jfdata', array(), 'array');

					if (!$model->store($post)) {
						$this->logImport("STORE_UPDATE_ERROR", $actionID . "." . implode(", ", $model->getErrors()));
						$updateError++;
					} else {
						$getID = $model->get('id');

						$getID = $model->getId();

						$this->logImport("STORE_UPDATE_SUCCESS", $getID);
						$updateSuccess++;
					}
					break;
				case 3: // suppression
					switch ($this->_params->get("field_delete_action", "0")) {
						case "1": // on supprime le contenu
                            JFactory::getApplication()->input->set('cid',array($actionID));
							$model = $this->getModel('items');
							if (!$model->delete(array($actionID))) {
								$this->logImport("STORE_DELETE_ERROR", $actionID . "." . implode(", ", $model->getErrors()));
								$deleteError++;
							} else {
								$this->logImport("STORE_DELETE_SUCCESS", $actionID);
								$deleteSuccess++;
							}
							break;
						case "0":
                            try{
                                $query = 'UPDATE #__content set state=0'
                                    . ' WHERE id= ' . (int) $actionID;
                                $db->setQuery($query);
                                $db->execute();
                                $this->logImport("STORE_DELETE_SUCCESS", $actionID);
                                $deleteSuccess++;
                            }catch (Exception $e){
                                $this->logImport("STORE_DELETE_ERROR", $actionID . "." . $e->getMessage());
                                $deleteError++;
                            }
							break;
						case "-1": // on archive le contenu
                            try{
                                $query = 'UPDATE #__content set state=-1'
                                    . ' WHERE id= ' . (int) $actionID;
                                $db->setQuery($query);
                                $db->execute();
                                $this->logImport("STORE_DELETE_SUCCESS", $actionID);
                                $deleteSuccess++;
                            }catch (Exception $e){
                                $this->logImport("STORE_DELETE_ERROR", $actionID . "." . $e->getMessage());
                                $deleteError++;
                            }
							break;
					} // switch
					break;
				default: // erreur ;
					$this->logImport("STORE_UNKNOW_ERROR");
			}
			if ((is_int(($i + 1) / $this->_nbAjaxStep2)) && ($nbData > ($i + 1))) {
				// on incr�mente pour indiquer que l'on va traiter l'enregistrement suivant
				$i++;
				FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP2_LINE', $i));
				// on m�morise les derniers param�tres
				$session->set("addError", $addError, "fleximport");
				$session->set("addSuccess", $addSuccess, "fleximport");
				$session->set("updateError", $updateError, "fleximport");
				$session->set("updateSuccess", $updateSuccess, "fleximport");
				$session->set("deleteError", $deleteError, "fleximport");
				$session->set("deleteSuccess", $deleteSuccess, "fleximport");
				$session->set("stepImport", $i, "fleximport");
				FleximportHelper::AjaxReload(true, $this->_method);
			}
		}
		FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP2_LINE', $i));
		// indique que l'import est termin�
		$session->set("step", 3, "fleximport");

		$return = false;
		if ($addSuccess || $updateSuccess || $deleteSuccess) {
			$this->logImport("STORE_SUCCESS_COUNT", JText::sprintf('COM_FLEXIMPORT_LOG_IMPORT_STORE_COUNT_DETAIL', $addSuccess, $updateSuccess, $deleteSuccess));
			$return = true;
		}
		if ($addError || $updateError || $deleteError) {
			$this->logImport("STORE_ERROR_COUNT", JText::sprintf('COM_FLEXIMPORT_LOG_IMPORT_STORE_COUNT_DETAIL', $addError, $updateError, $deleteError));
			$return = false;
		}
		// mise � jour du cache
		$cache = JFactory::getCache('com_flexicontent');
		$cache->clean();
		return $return;
	}
	/* M�thode permettant de supprimer la table temporaire si elle existe puis de cr�er les tables temporaires */
	private function initTmpDB()
	{
		$db = JFactory::getDbo();
		$query = array();
		$query[] = 'DROP TABLE IF EXISTS #__fleximport_tmp_import_raw';
		$query[] = 'CREATE TABLE  #__fleximport_tmp_import_raw (
					id INT NOT NULL AUTO_INCREMENT,
					raw LONGTEXT NOT NULL,
					PRIMARY KEY (id)
					)';
		$query[] = 'DROP TABLE IF EXISTS #__fleximport_tmp_import_format';
		$query[] = 'CREATE TABLE #__fleximport_tmp_import_format (
					  id int NOT NULL AUTO_INCREMENT,
					  id_data int NOT NULL,
					  ordering int NOT NULL,
					  flexifield_id int NOT NULL,
					  value LONGTEXT NOT NULL,
					  action_value int NOT NULL,
					  action_field int NOT NULL,
					  PRIMARY KEY (id),
					  KEY id_data (id_data),
					  KEY flexifield_id (flexifield_id)
					)';
		foreach ($query as $sql) {
            try{
                $db->setQuery($sql);
                $db->execute();
            }catch (Exception $e){
                $this->logImport("STORE_DELETE_ERROR", $actionID . "." . $e->getMessage());
                FleximportHelper::debug($e->getMessage(), 'Creating tmp table');
            }
		}
	}
	/* retourne le nombre d'�l�ment */
	private function countData($format = "format")
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($format == "format") {
			$query->select('count(distinct id_data)')->from('#__fleximport_tmp_import_format');
		} elseif ($format == "raw") {
			$query->select('count(id)')->from('#__fleximport_tmp_import_raw');
		}
		if ($query) {
            try{
                $db->setQuery($query);
                $result = (int)$db->loadResult();
            }catch (Exception $e){
                FleximportHelper::debug($e->getMessage(), 'countData');
            }
			return $result;
		} else {
			return false;
		}
	}
}