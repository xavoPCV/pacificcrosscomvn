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
class fleximportPlugincheckbox extends fleximportFieldPlugin {
    public function formatValues()
    {
        // récupére les valeurs possibles du champs
        $listelements = explode("%%", $this->_flexiFieldParams->get('field_elements'));
        $listarrays = array();
        foreach ($listelements as $listelement) {
            $listvalue = explode("::", $listelement);
            $listarrays[] = trim($listvalue[0]);
        }
    	$db = JFactory::getDbo();
    	$fieldvalues = array();
        // on ne mémorisera que les valeurs possibles
        foreach ($this->_fieldValues as &$fieldvalue) {
            // si la valeur n'est pas valide
            if (!in_array($fieldvalue, $listarrays)) {
                if ($this->_fieldParams->get('checkbox_create_value')) {
                    // on ajoute la valeur dans les parametres
                    $this->_flexiFieldParams->set('field_elements', $this->_flexiFieldParams->get('field_elements') . '%% ' . $fieldvalue . '::' . $fieldvalue);
                    $query = 'UPDATE #__flexicontent_fields set attribs=' . $db->quote($this->_flexiFieldParams->toString()) . ' WHERE id = ' . $this->_field->flexi_field_id;
                    $db->setQuery($query);
                    $db->execute();
                } else {
                    unset($fieldvalue);
                }
            }
            if (isset($fieldvalue)) $fieldvalues[] = $fieldvalue;
        }
        $this->_fieldValues = $fieldvalues;
    }
}