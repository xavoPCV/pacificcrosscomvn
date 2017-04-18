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
        $jlang->load('plg_fleximport_types_csv', JPATH_ADMINISTRATOR);
        return true;
    }
    /*
       Permet de d�composer les donn�es d'un fichier dans un tableau
    */
    public function parseData()
    {
        return $this->parseDataCSV();
    }
    /*
	   Permet de r�cuperer une valeur d'un fichier en fonction d'un index et d'un titre de champs.
	*/
    public function getValues($index = 0, $title = array())
    {
        return $this->getValuesCSV($index, $title);
    }
    /*
	   m�thode pour r�cup�rer les valeurs dans un fichier CSV
	   $filename = nom du fichier csv
	*/
    private function parseDataCSV()
    {
        $session = JFactory::getSession();
        // permet de corriger les bugs de fin de ligne suite � un encodage mac
        @ini_set('auto_detect_line_endings', true);
        // si c'est le second appel
        $csvFile = fopen($this->_pathToTemp . $this->_filename, "r");
        if (!$csvFile)return false;

        $csvSeparator = $this->getSeparator();
        $csvQuote = $this->getQuote();

        if ($this->_firstLoad) {
            $csvNumLine = 1;
            $csvTitle = fgetcsv($csvFile, 0, $csvSeparator, $csvQuote);
            $csvFilePos = ftell($csvFile);
            // force l'�liminiation de tout les caract�res sp�ciaux sur le premier �l�m�nt (cela corrige un probl�me par rapport au fichier utf8
            if (isset($csvTitle[0]))
                $csvTitle[0] = preg_replace("#[^a-zA-Z0-9_\-\s]#", "", $csvTitle[0]);

            $session->set("csvTitle", $csvTitle, "fleximport");
        } else {
            $csvNumLine = $session->get("csvNumLine", 1, "fleximport");
            $csvFilePos = $session->get("csvFilePos", 0, "fleximport");
        }
        $db = JFactory::getDbo();
        fseek($csvFile, $csvFilePos);
        $queryValues=array();
        for ($csvNumLine; $csvNumLine <= $this->numberOfRow();$csvNumLine++) {
            $ligne = fgetcsv($csvFile, 0, $csvSeparator, $csvQuote);
        	if ($this->_params->get('csv_encode', "")) {
        		$fileEncoding = $this->_params->get('csv_encode', "");
        		foreach ($ligne as &$detailLigne) {
        			$detailLigne = @iconv($fileEncoding,'UTF-8',$detailLigne);
        		}
        	}
        	$queryValues[] = '('.$db->quote(serialize($ligne)).')';
            if ((is_int($csvNumLine / $this->_nbAjaxStep0)) && ($csvNumLine <= $this->numberOfRow())) {
                try{
                    $db->setQuery('INSERT INTO #__fleximport_tmp_import_raw (raw) VALUES '.implode(',',$queryValues));
                    $db->execute();
                }catch (Exception $e){
                    FleximportHelper::debug($e->getMessage(),'Insert in RAW format');
                }
                FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_LINE', $csvNumLine));
                // on m�morise les derniers param�tres
                $session->set("csvNumLine", ($csvNumLine + 1), "fleximport");
                $session->set("csvFilePos", ftell($csvFile), "fleximport");
                fclose($csvFile);
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
        $session->clear("csvNumLine", "fleximport");
        $session->clear("csvNumberRow", "fleximport");
        FleximportHelper::JSONReturn(JText::sprintf('COM_FLEXIMPORT_IMPORT_AJAX_STEP0_LINE', ($csvNumLine - 1)));
        fclose($csvFile);
        if ($this->getTotal()) {
            return true;
        } else {
            return false;
        }
    }
    private function getSeparator()
    {
        $csvSeparator = $this->_params->get('csv_separator', "2");
        switch ($csvSeparator) {
            case "1":
                $csvSeparator = "\t";
                break;
            case "2":
                $csvSeparator = ";";
                break;
            case "3":
                $csvSeparator = ",";
                break;
            case "4":
                $csvSeparator = " ";
                break;
        }
        return $csvSeparator;
    }
    private function getQuote()
    {
        $csvQuote = $this->_params->get('csv_quote', "1");
        switch ($csvQuote) {
            case "1":
                $csvQuote = "\0";
                break;
            case "2":
                $csvQuote = "'";
                break;
            case "3":
                $csvQuote = "\"";
                break;
        }
        return $csvQuote;
    }
    private function numberOfRow()
    {
        $session = JFactory::getSession();
        if (!($rows = $session->get("csvNumberRow", 0, "fleximport"))) {
            if (($csvFile = fopen($this->_pathToTemp . $this->_filename, "r")) !== false) {
                $rows = -1;
                while (($record = fgetcsv($csvFile, 0, $this->getSeparator(), $this->getQuote())) !== false) {
                    $rows++;
                }
                fclose($csvFile);
            }
            $session->set("csvNumberRow", $rows, "fleximport");
        }
        return $rows;
    }
    /*
	   M�thode pour r�cup�rer les valeurs dans un fichier CSV
	   $dataCSV = donn�es du fichier CSV
	   $cleCSV = cl� � rechercher dans le fichier
	*/
    private function getValuesCSV($index = 0, $cleCSV = array())
    {
        if (!$cleCSV) return false;
        $session = JFactory::getSession();
        $csvMultipleValue = $this->_params->get('csv_value_multiple', "%%");
        $fieldValues = array();
        $data = $this->getRawData($index);
        $data = unserialize($data);
        foreach ($cleCSV as $cle) {
            $numCol = array_search($cle, $session->get("csvTitle", array(), "fleximport"));
            // si la cl� n'existe pas dans le fichier
            if ($numCol !== false)
                $fieldValues = array_merge($fieldValues, explode($csvMultipleValue, $data[$numCol]));
        }
        return $fieldValues;
    }
    /*
	   M�thode pour g�n�rer un fichier CSV � partir de donn�es
	*/
    public function exportData($datas = array(), $type = 0)
    {
        if (!$datas) return false;
        $columnTitle = array();
        $columnTitleDone = false;
        $lineValues = array();
        foreach ($datas as $itemID => $fields) {
            $lineValue = array();
            foreach ($fields as $field) {
                if (!$columnTitleDone) {
                    $labels = explode(',', $field['labels']);
                    if (count($labels)) {
                        $columnTitle[] = $labels[0];
                    } else {
                        $columnTitle[] = '';
                    }
                }
                foreach ($field['values'] as &$tempValue){
                    if (is_array($tempValue)){
                        $tempValue = json_encode($tempValue);
                    }
                }
                $fieldValues = implode($this->_params->get('csv_value_multiple', '%%'), $field['values']);
                // suppression des sauts de ligne
                $lineValue[] = preg_replace("/(\r\n|\n|\r)/", " ", $fieldValues);
            }
            $lineValues[] = $lineValue;
            $columnTitleDone = true;
            $this->exportDBRecord($itemID, $type);
        }
        if ($f = fopen($this->_pathToTemp . $this->_filename, 'w')) {
            $csvSeparator = $this->getSeparator();
            $csvQuote = $this->getQuote();
            // permet de forcer l'UTF8
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f, $columnTitle, $csvSeparator, $csvQuote);
            foreach ($lineValues as $lineValue) {
                fputcsv($f, $lineValue, $csvSeparator, $csvQuote);
            }
            fclose($f);
            return count($lineValues);
        }
        return false;
    }
}
// ajout de la fonction si c'est PHP < 5.3
if (!function_exists('str_getcsv')) {
    function str_getcsv($str , $delim = ',', $enclose = '"', $preserve = false)
    {
        $resArr = array();
        $n = 0;
        $expEncArr = explode($enclose, $str);
        foreach($expEncArr as $EncItem) {
            if ($n++ % 2) {
                array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem . ($preserve?$enclose:''));
            } else {
                $expDelArr = explode($delim, $EncItem);
                array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
                $resArr = array_merge($resArr, $expDelArr);
            }
        }
        return $resArr;
    }
}