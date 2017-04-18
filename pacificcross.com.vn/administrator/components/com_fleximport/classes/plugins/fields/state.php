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
class fleximportPluginstate extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
        return $this->_fieldParams->get('state_default', '0'); // 0 = dépublié
    }
    public function formatValues()
    {
        // si l'état n'est pas valide, mémorisation de l'état par défaut
        if (!in_array($this->_fieldValues[0], array("0", "1", "-1", "-2", "-3", "-4", "-5"))) {
            $this->_fieldValues = $this->_fieldParams->get('state_default', '0');
        } else {
            // suppression des valeurs multiples
            $this->_fieldValues = $this->_fieldValues[0];
        }
    }
    public function getPostValues()
    {
        $post['jform'][$this->_field->fname] = $this->_fieldValues;

        return $post;
    }
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('state')->from('#__content')->where('id='.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
}