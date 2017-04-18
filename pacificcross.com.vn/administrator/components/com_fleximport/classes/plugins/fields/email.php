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
class fleximportPluginemail extends fleximportFieldPlugin {
    public function formatValues()
    {
        $emailSeparator = $this->_fieldParams->get('email_separator', '::');
        $emailExport = $this->_fieldParams->get('email_export', '1');
        $emailRelease = $this->_fieldParams->get('email_release', '1');

        $regex = '/^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+.)+[a-zA-Z]{2,4}$/';
        $fieldvalues = array();
        $i = 0;
        foreach ($this->_fieldValues as $value) {
            // RC5 R1338 ajout de paramètres
            if ($emailRelease) {
                $valueEmail = explode($emailSeparator, $value);
                if (preg_match($regex, $valueEmail[0])) {
                    // s'il y a un email et un nom
                    if (count($valueEmail) > 1 && $emailExport == 2) {
                        $fieldvalues[$i]['addr'] = $valueEmail[0];
                        $fieldvalues[$i]['text'] = $valueEmail[1];
                        // seulement un email
                    } else {
                        $fieldvalues[$i]['addr'] = $valueEmail[0];
                        $fieldvalues[$i]['text'] = '';
                    }
                }
                // ancienne version
            } elseif (preg_match($regex, $value)) {
                $fieldvalues[$i] = $value;
            }
            $i++;
        }
        $this->_fieldValues = $fieldvalues;
    }
    public function formatValuesExport()
    {
        $emailSeparator = $this->_fieldParams->get('email_separator', '::');
        $emailExport = $this->_fieldParams->get('email_export', '1');
        $emailRelease = $this->_fieldParams->get('email_release', '1');
        foreach ($this->_fieldValues as $idv => $fieldValue) {
            if ($emailRelease == '1') {
                if ($emailExport == 2) {
                    $this->_fieldValues[$idv] = $fieldValue['addr'] . $emailSeparator . $fieldValue['text'];
                } else {
                    $this->_fieldValues[$idv] = $fieldValue['addr'];
                }
            } else {
                $this->_fieldValues[$idv] = $fieldValue;
            }
        }
    }
}