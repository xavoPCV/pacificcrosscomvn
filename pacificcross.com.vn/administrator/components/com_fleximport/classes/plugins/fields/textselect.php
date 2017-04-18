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
class fleximportPlugintextselect extends fleximportFieldPlugin {
	public function formatValues()
	{
		// ajout du préfixe et suffixe
		$fieldValues = array();
		foreach ($this->_fieldValues as $fieldValue){
			$fieldValues[] = $this->_fieldParams->get('text_start') . $fieldValue . $this->_fieldParams->get('text_end');
		}
		$this->_fieldValues = $fieldValues;
	}
	public function formatValuesExport()
	{
		foreach ($this->_fieldValues as $idv => $fieldValue) {
			$this->_fieldValues[$idv] = $this->_fieldParams->get('text_start') . $fieldValue . $this->_fieldParams->get('text_end');
		}
	}
}
