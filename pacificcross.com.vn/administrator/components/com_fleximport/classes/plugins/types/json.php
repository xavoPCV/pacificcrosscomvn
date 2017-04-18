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
require_once(JPATH_COMPONENT . '/classes/plugins/type.php');
class fleximportPluginType extends fleximportPluginTypeBase {
    function __construct($filename = '', $pathToTemp = '', $params = '')
    {
        if (!$filename || !$pathToTemp) return false;
        parent::__construct($filename , $pathToTemp);
        $this->_params = new JRegistry($params);
        $jlang = JFactory::getLanguage();
        $jlang->load('plg_fleximport_types_json', JPATH_ADMINISTRATOR);
        return true;
    }
    /*
       Permet de d�composer les donn�es d'un fichier dans un tableau
    */
    public function parseData()
    {
        return $this->parseDataJSON();
    }
    /*
	   Permet de r�cuperer une valeur d'un fichier en fonction d'un index et d'un titre de champs.
	*/
    public function getValues($index = 0, $title = array())
    {
        return $this->getValuesJSON($index, $title);
    }
    /*
	   m�thode pour r�cup�rer les valeurs dans un fichier JSON
	*/
    private function parseDataJSON()
    {
        $session = JFactory::getSession();
        // permet de corriger les bugs de fin de ligne suite � un encodage mac
        // si c'est le second appel
        $jsonFile = file($this->_pathToTemp . $this->_filename);
        if (!$jsonFile)return false;
        if (!$this->_firstLoad)
            $jsonFilePos = $session->get("jsonFilePos", 0, "fleximport");
        $db = JFactory::getDbo();
        $jsonDatas = json_decode($jsonFile);
    	$queryValues=array();
        for ($jsonFilePos; $jsonFilePos < count($jsonDatas);$jsonFilePos++) {
        	$queryValues[] = '('.$db->quote(serialize($jsonDatas[$jsonFilePos])).')';
            if ((is_int($jsonFilePos / $this->_nbAjaxStep0)) && (($jsonFilePos + 1) < count($jsonDatas))) {
                try{
                    $db->setQuery('INSERT INTO #__fleximport_tmp_import_raw (raw) VALUES '.implode(',',$queryValues));
                    $db->execute();
                }catch (Exception $e){
                    FleximportHelper::debug($e->getMessage(),'Insert in RAW format');
                }
                FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_LINE', $jsonFilePos));
                // on m�morise les derniers param�tres
                $session->set("jsonFilePos", ($jsonFilePos + 1), "fleximport");
                FleximportHelper::AjaxReload();
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
        $session->clear("csvFilePos", "fleximport");
        FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_LINE', ($jsonFilePos - 1)));
        if ($this->getTotal()) {
            return true;
        } else {
            return false;
        }
    }
    /*
	   M�thode pour r�cup�rer les valeurs dans un fichier JSON
	*/
    private function getValuesJSON($index = 0, $cleJSON = array())
    {
        if (!$cleJSON) return false;
        $session = JFactory::getSession();
        $fieldValues = array();
        $data = $this->getRawData($index);
        $data = unserialize($data);
        foreach ($cleJSON as $cle) {
            if (isset($data[$cle]))
                $fieldValues = array_merge($fieldValues, $data[$cle]);
        }
        return $fieldValues;
    }
    /*
	   M�thode pour g�n�rer un fichier JSON � partir de donn�es
	*/
    public function exportData($datas = array(), $type = 0)
    {
        if (!$datas) return false;
        $lineValues = array();
        foreach ($datas as $itemID => $fields) {
            $lineValue = array();
            foreach ($fields as $field) {
                $labels = explode(',', $field['labels']);
                if (count($labels)) {
                    $label = $labels[0];
                    $fieldValues = $field['values'];
                    // suppression des sauts de ligne
                    $lineValue[$label] = preg_replace("/(\r\n|\n|\r)/", " ", $fieldValues);
                }
            }
            $lineValues[] = $lineValue;
            $this->exportDBRecord($itemID, $type);
        }
        if ($f = fopen($this->_pathToTemp . $this->_filename, 'w')) {
            fwrite($f, json_encode($lineValue));
            fclose($f);
            return count($lineValues);
        }
        return false;
    }
}