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
class fleximportPluginselect extends fleximportFieldPlugin {
	public function formatValues()
	{
		$listarrays = array();
		// recupere les valeurs possibles du champs
		$db = JFactory::getDbo();
		if ($this->_flexiFieldParams->get('sql_mode')) {
			$query = preg_match('#^select#i', $this->_flexiFieldParams->get('field_elements')) ? $this->_flexiFieldParams->get('field_elements') : '';
			$db->setQuery($query);
			$listelements = $db->loadObjectList();
			foreach ($listelements as $listelement) {
				$listarrays[] = $listelement->value;
			}
		}else{
			$listelements = explode("%%", $this->_flexiFieldParams->get('field_elements'));
			foreach ($listelements as $listelement) {
				$listvalue = explode("::", $listelement);
				$listarrays[] = trim($listvalue[0]);
			}
		}
		$fieldvalues=array();
		// on ne mémorisera que les valeurs possibles
		foreach ($this->_fieldValues as &$fieldvalue) {
			// si la valeur n'est pas valide
			if (!in_array($fieldvalue, $listarrays)) {
				if ($this->_fieldParams->get('select_create_value') && !$this->_flexiFieldParams->get('sql_mode')) {
					// on ajoute la valeur dans les parametres
					$this->_flexiFieldParams->set('field_elements', $this->_flexiFieldParams->get('field_elements') . '%% ' . $fieldvalue . '::' . $fieldvalue);
					$query = 'UPDATE #__flexicontent_fields set attribs=' . $db->quote($this->_flexiFieldParams->toString()) . ' WHERE id = ' . (int)$this->_field->flexi_field_id;
					$db->setQuery($query);
					$db->execute();
				} else {
					unset($fieldvalue);
				}
			}
			if (isset($fieldvalue)) $fieldvalues[] = $fieldvalue;
		}
		// on ne garde que la premiére valeur
		if (count($fieldvalues)) {
			$this->_fieldValues = array($fieldvalues[0]);
		}else{
			$this->_fieldValues = array();
		}
	}
}
