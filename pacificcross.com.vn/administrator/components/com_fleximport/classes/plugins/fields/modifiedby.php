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
class fleximportPluginmodifiedby extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
        return $this->_fieldParams->get('modified_by_default', JFactory::getUser()->id);
    }
    public function formatValues()
    {
        $autFormat = $this->_fieldParams->get('modified_by_format', '1');
        // suppression des valeurs multiples
        $this->_fieldValues = $this->_fieldValues[0];
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('id')->from('#__users');

        // gestion des auteurs en fonction des ID
        if ($autFormat == '1') {
            $query->where('id = ' . (int)$this->_fieldValues);
        } elseif ($autFormat == '2') {
            // gestion des auteurs en fonction du nom
            $query->where('name = ' . $db->quote($this->_fieldValues));
        } elseif ($autFormat == '3') {
            // nom d'utilisateur
            $query->where('username = ' . $db->quote($this->_fieldValues));
        } elseif ($autFormat == '4') {
            // email
            $query->where('email = ' . $db->quote($this->_fieldValues));
        }
        $db->setQuery($query);
        if (!$this->_fieldValues = $db->loadResult())
            $this->_fieldValues = $this->getDefaultValue();
    }
    public function getPostValues()
    {
        $post['jform']['modified_by'] = $this->_fieldValues;
        return $post;
    }
    public function formatValuesExport()
    {
        $autFormat = $this->_fieldParams->get('modified_by_format', '1');
    	$db = JFactory::getDbo();
        foreach ($this->_fieldValues as $idv => $fieldValue) {
        	$query = $db->getQuery(true);
            if ($autFormat == '1') {
                $query->select('id AS value');
            } elseif ($autFormat == '2') {
                // gestion des auteurs en fonction du nom
            	$query->select('name AS value');
            } elseif ($autFormat == '3') {
                // nom d'utilisateur
            	$query->select('username AS value');
            } elseif ($autFormat == '4') {
                // email
            	$query->select('email AS value');
            }
        	$query->from('#__users')->where('id='.(int)$fieldValue);
            $db->setQuery($query);
            if ($result = $db->loadResult()) {
                $this->_fieldValues[$idv] = $result;
            } else {
                unset($this->_fieldValues[$idv]);
            }
        }
    }
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('modified_by')->from('#__content')->where('id = '.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
}