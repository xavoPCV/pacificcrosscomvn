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
jimport('joomla.form.form');
require_once(JPATH_COMPONENT.'/classes/plugins/field.php');
class fleximportPluginattribs extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
        $attribs = $this->_fieldParams->toArray();
    	$strandardParams = new JForm('attribs');
    	$strandardParams->loadFile(JPATH_COMPONENT.'/models/forms/field.xml');
		$strandardParams = array_keys($strandardParams->getFieldset('standard'));
        foreach ($attribs as $index => $attrib) {
        	if (in_array('params_'.$index,$strandardParams) || $attrib) {
        		unset($attribs[$index]);
        		continue;
        	}
        }
        return $attribs;
    }
    public function formatValues()
    {
        // suppression des valeurs multiples
    	if ($this->_fieldValues) {
    		$this->_fieldValues = $this->_fieldValues[0];
    	}

    }
    public function getPostValues()
    {
        $post = array();
        // si ce n'est pas un tableau
        if (!is_array($this->_fieldValues)) {
            $registry = new JRegistry;
            $registry->loadString($this->_fieldValues);
            $this->_fieldValues = $registry->toArray();
        }
        if (!array_key_exists('ilayout', $this->_fieldValues))
            $this->_fieldValues ['ilayout'] = '';

        $post['jform']['attribs'] = $this->_fieldValues;

        return $post;
    }

    /*
	   Charge les valeurs d'un champs pour un article donné
	*/
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('attribs')->from('#__content')->where('id='.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
}