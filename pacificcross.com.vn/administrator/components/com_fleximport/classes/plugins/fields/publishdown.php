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
class fleximportPluginpublishdown extends fleximportFieldPlugin {
    public function formatValues()
    {
        $separator = $this->_fieldParams->get('publishdown_separator', '/');
        $dayPos = (int)$this->_fieldParams->get('publishdown_day', 2);
        $monthPos = (int)$this->_fieldParams->get('publishdown_month', 1);
        $yearPos = (int)$this->_fieldParams->get('publishdown_year', 0);
        $hour = $this->_fieldParams->get('publishdown_hour', '23:59:59');
        // suppression des valeurs multiples
        $this->_fieldValues = $this->_fieldValues[0];

        $arDate = explode($separator, $this->_fieldValues);
        // on ne gère la valeur que si il y a bien une date complète
        if (count($arDate) != 3) {
            $this->_fieldValues = $this->getDefaultValue();
        } else {
            foreach ($arDate as $index => $dateVal) {
                $dateVal = trim($dateVal);
                // permet de limiter les valeurs en cas de dépassement,notamment s'il y a une heure derrière
                if ($dayPos == $index || $monthPos == $index) {
                    $dateVal = (int)substr($dateVal, 0, 2);
                } elseif ($yearPos == $index) {
                    $dateVal = (int)substr($dateVal, 0, 4);
                }
            }
            $publishDate = mktime(0, 0, 0, $arDate[$monthPos], $arDate[$dayPos], $arDate[$yearPos]);
            $this->_fieldValues = date('Y-m-d', $publishDate) . ' ' . $hour;
        }
    }
    public function formatValuesExport()
    {
        $separator = $this->_fieldParams->get('publishdown_separator', '/');
        $dayPos = (int)$this->_fieldParams->get('publishdown_day', 2);
        $monthPos = (int)$this->_fieldParams->get('publishdown_month', 1);
        $yearPos = (int)$this->_fieldParams->get('publishdown_year', 0);
    	$db = JFactory::getDbo();
		$nullDate = $db->getNulldate();
        foreach ($this->_fieldValues as $idv => $fieldValue) {
            // on n'exporte pas les dates nulles
            if ($fieldValue == $nullDate || $fieldValue == '') {
                unset($this->_fieldValues[$idv]);
            } else {
                // réordonne la date en fonction des paramètres
                $datePublish[$yearPos] = substr($fieldValue, 0, 4);
                $datePublish[$monthPos] = substr($fieldValue, 5, 2);
                $datePublish[$dayPos] = substr($fieldValue, 8, 2);
                $this->_fieldValues[$idv] = $datePublish[0] . $separator . $datePublish[1] . $separator . $datePublish[2];
            }
        }
    }
    public function getPostValues()
    {
        $post['jform']['publish_down'] = $this->_fieldValues;

        return $post;
    }
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('publish_down')->from('#__content')->where('id='.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
    public function getDefaultValue()
    {
    	$db = JFactory::getDbo();
    	$nullDate = $db->getNulldate();
        return $nullDate;
    }
}