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
class fleximportPlugindate extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
        $defaultValue = $this->_fieldParams->get('value_default');
        if ($date = $this->getFormatDate($defaultValue)) {
            $defaultValue = $date;
        } else {
            $defaultValue = "";
        }
        return $defaultValue;
    }
    public function formatValues()
    {
        // on ne traite pas la valeur si c'est celle par défaut
        $fieldvalues = array();
        foreach ($this->_fieldValues as $fieldvalue) {
            if ($date = $this->getFormatDate($fieldvalue))
                $fieldvalues[] = $date;
        }
        $this->_fieldValues = $fieldvalues;
    }
    private function getFormatDate($dateInput = "")
    {
        if (!$dateInput)
            return false;
        $date_format = $this->_fieldParams->get('date_format', '1');
        $dateInput = trim($dateInput);
        switch ($date_format) {
            case 1:
                $regex = '/^([0-9]{2}).([0-9]{2}).([0-9]{4})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[1];
                    $dateM = $date[2];
                    $dateY = $date[3];
                }
                break;
            case 2:
                $regex = '/^([0-9]{2}).([0-9]{2}).([0-9]{4})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[2];
                    $dateM = $date[1];
                    $dateY = $date[3];
                }
                break;
            case 3:
                $regex = '/^([0-9]{4}).([0-9]{2}).([0-9]{2})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[3];
                    $dateM = $date[2];
                    $dateY = $date[1];
                }
                break;
            case 4:
                $regex = '/^([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[1];
                    $dateM = $date[2];
                    $dateY = $date[3];
                }
                break;
            case 5:
                $regex = '/^([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[2];
                    $dateM = $date[1];
                    $dateY = $date[3];
                }
                break;
            case 6:
                $regex = '/^([0-9]{4}).([0-9]{1,2}).([0-9]{1,2})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[3];
                    $dateM = $date[2];
                    $dateY = $date[1];
                }
                break;
            default:
                $regex = '/^([0-9]{2}).([0-9]{2}).([0-9]{4})$/';
                if (preg_match ($regex, $dateInput, $date)) {
                    $dateD = $date[1];
                    $dateM = $date[2];
                    $dateY = $date[3];
                }
        }
        // si l'année est sur 2 caractères, gestion du siècle
        if (isset($dateY) && strlen($dateY) == 2) {
            if ($dateY > date("y")) {
                $dateY = ((int)substr(date("Y"), 0, 2) - 1) . $dateY;
            } else {
                $dateY = substr(date("Y"), 0, 2) . $dateY;
            }
        }
        if (isset($dateD) && isset($dateM) && isset($dateY)) {
            $dateD = sprintf("%02d", $date[1]);
            $dateM = sprintf("%02d", $date[2]);
            if (checkdate($dateM, $dateD, $dateY))
                return $dateY . "-" . $dateM . "-" . $dateD;
        }
        return false;
    }
    /**
     * fleximportPlugindate::formatValuesExport()
     *
     * @return
     */
    public function formatValuesExport()
    {
        $dateS = '/';
        $formatTS = '';
        $date_format = $this->_fieldParams->get('date_format', '1');
        foreach ($this->_fieldValues as $idv => $fieldValue) {
            $date = explode("-", $fieldValue);
            if (count($date) != 3) {
                unset($this->_fieldValues[$idv]);
            } else {
                $ts = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
                if (!$formatTS) {
                    switch ($date_format) {
                        case 1:
                            $formatTS = 'd' . $dateS . 'm' . $dateS . 'Y';
                            break;
                        case 2:
                            $formatTS = 'm' . $dateS . 'd' . $dateS . 'Y';
                            break;
                        case 3:
                            $formatTS = 'Y' . $dateS . 'm' . $dateS . 'd';
                            break;
                        case 4:
                            $formatTS = 'j' . $dateS . 'n' . $dateS . 'y';
                            break;
                        case 5:
                            $formatTS = 'n' . $dateS . 'j' . $dateS . 'y';
                            break;
                        case 6:
                            $formatTS = 'y' . $dateS . 'n' . $dateS . 'j';
                            break;
                        default:
                            $formatTS = 'd' . $dateS . 'm' . $dateS . 'Y';
                    }
                }
                $this->_fieldValues[$idv] = date($formatTS, $ts);
            }
        }
    }
}
