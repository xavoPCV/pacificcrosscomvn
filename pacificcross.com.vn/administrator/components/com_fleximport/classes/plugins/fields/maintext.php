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
class fleximportPluginmaintext extends fleximportFieldPlugin {
    public function formatValues()
    {
        // s'il y a plusieurs valeurs, on les concatene
        // ajout du préfixe et suffixe
        $this->_fieldValues = $this->_fieldParams->get('maintext_start', '') . implode(" ", $this->_fieldValues) . $this->_fieldParams->get('maintext_end', '');
        // suppression des balises html si le paramètre est actif
        if ($this->_fieldParams->get('maintext_html', 0))
            $this->_fieldValues = strip_tags($this->_fieldValues);
    	// conversion des sauts de ligne
    	if ($this->_fieldParams->get('maintext_convert_return', 1))
    		$this->_fieldValues = nl2br($this->_fieldValues);
        // gestion du lire la suite
        $maintextSeparator = $this->_fieldParams->get('maintext_separator', '{readmore}');
        $this->_fieldValues = str_replace($maintextSeparator, "<hr id=\"system-readmore\" />", $this->_fieldValues);
    }
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
        $maintextSeparator = $this->_fieldParams->get('maintext_separator', '{readmore}');
    	$query->select($db->quoteName('introtext') . "," . $db->quoteName('fulltext') )->from('#__content')->where('id='.(int)$itemID);
        $db->setQuery($query);
        $maintext = $db->loadObject();
        $result = array();
        if (count($maintext)) {
            if ($maintext->introtext && $maintext->fulltext) {
                $result[] = $maintext->introtext . $maintextSeparator . $maintext->fulltext;
            } else {
                $result[] = $maintext->introtext . $maintext->fulltext;
            }
        }
        return $result;
    }
}
