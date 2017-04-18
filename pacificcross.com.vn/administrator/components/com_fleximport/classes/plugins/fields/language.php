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
class fleximportPluginlanguage extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
    	$lang = JFactory::getLanguage();
        return $this->_fieldParams->get('language', '*');
    }
    public function formatValues()
    {
        // suppression des valeurs multiples
        $this->_fieldValues = $this->_fieldValues[0];
    }
    public function getPostValues()
    {
        $post = array();
        $post['jform']['language'] = $this->_fieldValues;
        return $post;
    }
    /*
	   Charge les valeurs d'un champs pour un article donné
	*/
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('language')->from('#__flexicontent_items_ext')->where('item_id='.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
}