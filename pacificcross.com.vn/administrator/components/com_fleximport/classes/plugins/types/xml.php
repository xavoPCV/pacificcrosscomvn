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
require_once(JPATH_COMPONENT.'/classes/plugins/type.php');
class fleximportPluginType extends fleximportPluginTypeBase {
	function __construct($filename = '', $pathToTemp = '', $params = '')
	{
		if (!$filename || !$pathToTemp) return false;
		parent::__construct($filename, $pathToTemp);
		$this->_params = new JRegistry($params);
		$jlang = JFactory::getLanguage();
		$jlang->load('plg_fleximport_types_xml', JPATH_ADMINISTRATOR);
		return true;
	}
	/*
	   Permet de d�composer les donn�es d'un fichier dans un tableau
	*/
	public function parseData()
	{
		return $this->parseDataXML();
	}
	/*
	   Permet de r�cuperer une valeur d'un fichier en fonction d'un index et d'un titre de champs.
	*/
	public function getValues($index = 0, $title = array())
	{
		$data = $this->getRawData($index);
		$data = simplexml_load_string($data);
		$fieldValues = array();
		return $this->getXMLValues($data, $title, $fieldValues);
	}
	/*
	   m�thode pour r�cup�rer les valeurs dans un fichier XML
	*/
	private function parseDataXML($filename = '')
	{
		if (!file_exists($this->_pathToTemp . $this->_filename))return false;

		if (!$xmlFile = simplexml_load_file ($this->_pathToTemp . $this->_filename))return false;
		// r�cup�re la cl� root xml
		$rootXML = $this->_params->get('xml_root', 'root');
		// si le fichier XML ne possede pas la cl� root XML
		if ($xmlFile->getName() != $rootXML or $rootXML == '')return false;
		$session = JFactory::getSession();
		// si c'est le second appel
		if ($this->_firstLoad) {
			$xmKeyPos = 0;
			$xmlFilePos = 0;
		} else {
			$xmKeyPos = $session->get("xmKeyPos", 0, "fleximport");
			$xmlFilePos = $session->get("xmlFilePos", 0, "fleximport");
		}
		// r�cup�rer les valeurs pour toutes les cl�s d'un champs
		$cleXML = $this->_params->get('xml_key', 'item');
		$cleXML = explode(",", $cleXML);
		$xmlArray = array();
		$db = JFactory::getDbo();
		$queryValues=array();
		for ($xmKeyPos; $xmKeyPos < count($cleXML);$xmKeyPos++) {
			$xmlDatas = $this->getXMLKeys($xmlFile, $cleXML[$xmKeyPos]);
			for ($xmlFilePos; $xmlFilePos < count($xmlDatas);$xmlFilePos++) {
				$queryValues[] = '('.$db->quote($xmlDatas[$xmlFilePos]->asXML()) .')';
				if ((is_int(($xmlFilePos + 1) / $this->_nbAjaxStep0)) && (($xmlFilePos + 1) < count($xmlDatas))) {
                    try{
                        $db->setQuery('INSERT INTO #__fleximport_tmp_import_raw (raw) VALUES '.implode(',',$queryValues));
                        $db->execute();
                    }catch (Exception $e){
                        FleximportHelper::debug($e->getMessage(),'Insert in RAW format');
                    }
					$xmlFilePos++;
					FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_LINE', $xmlFilePos));
					// on m�morise les derniers param�tres
					$session->set("xmKeyPos", $xmKeyPos, "fleximport");
					$session->set("xmlFilePos", $xmlFilePos , "fleximport");
					FleximportHelper::AjaxReload();
				}
			}
			if (($xmKeyPos + 1) < count($cleXML)) {
				$xmlFilePos = 0;
			}
		}
		if ($queryValues) {
            try{
                $db->setQuery('INSERT INTO #__fleximport_tmp_import_raw (raw) VALUES '.implode(',',$queryValues));
                $db->execute();
            }catch (Exception $e){
                FleximportHelper::debug($e->getMessage(),'Insert in RAW format');
            }
		}
		$session->clear("xmKeyPos", "fleximport");
		$session->clear("xmlFilePos", "fleximport");

		FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_LINE', $xmlFilePos));

		if ($this->getTotal()) {
			return true;
		} else {
			return false;
		}
	}
	/*
	   M�thode r�cursive pour r�cup�rer l'ensemble des valeurs d'un cl� XML
	   $cleXML = cl�s XML pour lesquelles il faut r�cup�rer les valeurs
	*/
	private function getXMLKeys($xmlFile, $cleXML = '')
	{
		$xmlArray = array();
		if ($nameSpace = $this->_params->get('xml_namespace')) {
			$xmlArray = @$xmlFile->xpath("//*[local-name() = '" . $nameSpace . ":" . $cleXML . "']");
		}
		if (!$xmlArray) {
			$xmlArray = @$xmlFile->xpath('//' . $cleXML);
		}
		return $xmlArray;
	}
	/*
	   *M�thode r�cursive pour r�cup�rer les valeurs d'une cl� ou d'un parametre xml
	   $dataXML = tableau de type SimpleXML
	   $cleXML = tableau des cl�s XML pour lesquelles il faut r�cup�rer les valeurs
	   $fieldValues = tableau des valeurs
	*/
	private function getXMLValues($dataXML = null, $cleXML = array(), &$fieldValues)
	{
		if (!$dataXML || !$cleXML) return false;
		// force la cr�ation d'un tableau
		if (!is_array($dataXML)) $dataXML = array($dataXML);
		if (!is_array($cleXML)) $cleXML = array($cleXML);
		// pour chaque enregistrement XML d'une cl�
		foreach ($dataXML as $detailXML) {
			// si c'est bien un objet du type SimpleXML
			if (is_object($detailXML)) {
				// parcours l'ensemble des cl�s � rechercher
				foreach ($cleXML as $cle) {
					// si c'est un attribut XML clexml@@attribut
					if (strpos($cle, "@@") !== false) {
						$cleAr = explode("@@", $cle);
						$cle = $cleAr[0];
						$cleParam = $cleAr[1];
					} else {
						$cleParam = "";
					}
					// si c'est la cl� root XML, on peut seulement r�cup�rerer les attributs
					if ($cle == $detailXML->getName() && $cleParam) {
						$fieldValues[] = $detailXML->attributes()->$cleParam;
					} else {
						// r�cup�ration de l'ensemble des cl�s xml
						$xmlEntries = $this->getXMLKeys($detailXML, $cle);
						if (count($xmlEntries)) {
							foreach ($xmlEntries as $xmlEntry) {
								if ($cleParam) {
									$fieldValues[] = $xmlEntry->attributes()->$cleParam;
								} else {
									// dans le cas o� il y a des sous �l�ments avec la cl� value, on va prendre l'ensemble des �l�ments
									// a terme je pense qu'il faudrait reprendre le parametre 1 utilis� pour l'export.
									if (is_object($xmlEntry) && isset($xmlEntry->value)) {
										$xmlEntryValues = $this->getXMLKeys($xmlEntry, $cle . '/value');
										if (is_array($xmlEntryValues)) {
											foreach ($xmlEntryValues as $xmlEntryValue) {
												$fieldValues[] = (string)$xmlEntryValue;
											}
										}
									} else {
										$fieldValues[] = (string)$xmlEntry;
									}
								}
							}
						}
					}
				}
			}
		}
		return $fieldValues;
	}
	/*
	   M�thode pour g�n�rer un fichier CSV � partir de donn�es
	*/
	public function exportData($datas = array(), $type = 0)
	{
		if (!$datas) return false;
		$xmlNode = $this->_params->get('xml_root', 'root');
		$xmlKey = $this->_params->get('xml_key', 'item');
		$xml = new DomDocument('1.0', 'utf-8');
		$xml->formatOutput = true;
		$xml->preserveWhiteSpace = false;
		$xmlRoot = $xml->createElement($xmlNode);
		$xml->appendChild($xmlRoot);
		$nbExport = 0;
		foreach ($datas as $itemID => $fields) {
			$xmlItem = $xml->createElement($xmlKey);
			$xmlRoot->appendChild($xmlItem);
			$j=0;
			foreach ($fields as $field) {
				$j++;
				// noeud particulier pour un champs
				$fieldXMLNode = $field['params']->get('param1', 'value');
				$labels = explode(',', $field['labels']);
				if (count($labels)) {
					$xmlItemNodeName = $labels[0];
					$detailLabel = explode('@@', $xmlItemNodeName);
					// on v�rifie s'il n'y a pas un attribut
					if (count($detailLabel) == 2) {
						$xmlItemNodeName = $detailLabel[0];
						$xmlItemNodeAttrib = $detailLabel[1];
					} else {
						$xmlItemNodeName = $detailLabel[0];
						$xmlItemNodeAttrib = '';
					}
					$detailNodes = explode('/', $xmlItemNodeName);
					$xmlItemTemp = $xmlItem;
					// on explore l'ensemble des niveaux
					foreach ($detailNodes as $detailNode) {
						// permet d'�purer la cl�
						$detailNode = preg_replace('/\[\s*.*?\]/i', "", $detailNode);
						$detailNode = str_replace(' ','',$detailNode);
						$xmlItemNode = $xmlItemTemp->getElementsByTagName($detailNode);
						if ($xmlItemNode->length == 0) {
							$xmlItemNode = $xml->createElement($detailNode);
							$xmlItemTemp->appendChild($xmlItemNode);
							$xmlItemTemp = $xmlItemNode;
						} else {
							// on garde uniquement le premier element de la liste
							$xmlItemTemp = $xmlItemNode->item(0);
						}
					}
					$xmlItemNode = $xmlItemTemp;
					// si la valeur est un attribut
					if ($xmlItemNodeAttrib) {
						$currentAttribute = '';
						// on g�re le fait si l'attribut �tait d�j� d�fini
						if ($xmlItemNode->hasAttribute($xmlItemNodeAttrib)) {
							$currentAttribute = ',' . $xmlItemNode->getAttribute($xmlItemNodeAttrib);
						}
						$xmlItemNode->setAttribute($xmlItemNodeAttrib, implode(',', $field['values']) . $currentAttribute);
					} else {
						$oldXMLValue = '';
						$nbValues = count($field['values']) > 1;
						// si une valeur �tait d�j� pr�sente (et que c'est pas un enfant on la transforme en enfant afin de stocked plusieurs valeurs
						if ($xmlItemNode->nodeValue && !$xmlItemNode->hasChildNodes()) {
							$oldXMLValue = $xmlItemNode->nodeValue;
							// suppression de l'ancien noeud
							$xmlItemNode->nodeValue = '';
							// on rattache la valeur dans un �l�ment enfant
							$xmlValue = $xml->createElement($fieldXMLNode, $oldXMLValue);
							$xmlItemNode->appendChild($xmlValue);
						}
						// on ajoute l'ensemble des valeurs
						foreach ($field['values'] as $value) {
							// prot�ge dans le cas o� c'est un tableau qui arrive en valeur
							if (is_array($value)) {
								$value = serialize($value);
							}
							// s'il y a plus d'une valeur on va cr�er un sous �l�ment
							if ($nbValues or $oldXMLValue) {
								if ($field['params']->get('param5', 0)) {
									$xmlValue = $xml->createCDATASection($value);
								}else{
									$xmlValue = $xml->createTextNode($value);
								}
								$xmlMutipleValue = $xml->createElement($fieldXMLNode);
								$xmlMutipleValue->appendChild($xmlValue);
								$xmlItemNode->appendChild($xmlMutipleValue);
							} else {
								// v�rifie s'il faut forcer le CDATA
								if ($field['params']->get('param5', 0)) {
									$xmlValue = $xml->createCDATASection($value);
								}else{
									$xmlValue = $xml->createTextNode($value);
								}
								$xmlItemNode->appendChild($xmlValue);
							}
						}
					}
					$xmlItemNode->normalize();
				}
			}
			$nbExport++;
			$this->exportDBRecord($itemID, $type);
		}
		$datas = $xml->saveXML();
		if (JFile::write($this->_pathToTemp .$this->_filename,$datas)) {
			return $nbExport;
		}
		return false;
	}
}