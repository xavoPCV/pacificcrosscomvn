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
class fleximportPlugintitle extends fleximportFieldPlugin {
    public function formatValues()
    {
    	// ajout du préfixe et suffixe
    	$this->_fieldValues = $this->_fieldParams->get('title_start') . implode(" ", $this->_fieldValues) . $this->_fieldParams->get('title_end');
    }
	public function getFlexicontentValues($itemID=0){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title')->from('#__content')->where('id='.(int)$itemID);
		$db->setQuery($query);
		return $db->loadColumn();
	}
}