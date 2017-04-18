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
class fleximportPluginRelation extends fleximportFieldPlugin {
	public function formatValues()
	{
		$linkTitle = $this->_fieldParams->get('link_title', '1');
		$linkId = $this->_fieldParams->get('link_id', '0');
		$linkField = $this->_fieldParams->get('link_field', '');

		// pour chaque liaison
		if (count($this->_fieldValues)) {
			$db = JFactory::getDbo();
			foreach ($this->_fieldValues as $index=> &$value) {
				// le but est de retrouvé chaque ID d'article pour faire la liaison
				$query = $db->getQuery(true);
				if ($linkId) {
					// on vérifie juste que l'id existe
					$query->select('id,catid')->from('#__content')->where('id='.(int)$value);
				}elseif($linkTitle){
					$query->select('id,catid')->from('#__content')->where('title='.$db->quote($value));
				}elseif($linkField){
					$query->select('c.id,c.catid')->from('#__flexicontent_fields_item_relations AS r')->where('r.value='.$db->quote($value))->where('r.field_id='.(int)$linkField);
					$query->innerJoin('#__content AS c ON r.item_id=c.id');
				}else{
					unset($this->_fieldValues[$index]);
					continue;
				}
				$db->setQuery($query);
				if ($result = $db->loadObject()) {
					$value = $result->id.':'.$result->catid;
				}else{
					unset($this->_fieldValues[$index]);
				}
			}
		}
	}
	public function formatValuesExport()
	{
		$linkTitle = $this->_fieldParams->get('link_title', '1');
		$linkId = $this->_fieldParams->get('link_id', '0');
		$linkField = $this->_fieldParams->get('link_field', '');

		// pour chaque liaison
		if (count($this->_fieldValues)) {
			$db = JFactory::getDbo();
			foreach ($this->_fieldValues as $index=> &$value) {
				// le but est de retrouvé chaque ID d'article pour faire la liaison
				$value = explode(':',$value);
				$value = $value[0];
				$query = $db->getQuery(true);
				if ($linkId) {
					// on vérifie juste que l'id existe
					$query->select('id')->from('#__content')->where('id='.(int)$value);
				}elseif($linkTitle){
					$query->select('title')->from('#__content')->where('id='.(int)$value);
				}elseif($linkField){
					$query->select('value')->from('#__flexicontent_fields_item_relations')->where('item_id='.(int)$value)->where('field_id='.(int)$linkField);
				}else{
					unset($this->_fieldValues[$index]);
					continue;
				}
				$db->setQuery($query);
				if ($result = $db->loadResult()) {
					$value = $result;
				}else{
					unset($this->_fieldValues[$index]);
				}
			}
		}
	}
}
