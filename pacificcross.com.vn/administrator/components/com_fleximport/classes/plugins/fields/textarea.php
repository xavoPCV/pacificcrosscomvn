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
class fleximportPlugintextarea extends fleximportFieldPlugin {
	public function formatValues()
	{
		// s'il y a plusieurs valeurs, on les concatene
		// ajout du préfixe et suffixe
		$this->_fieldValues = $this->_fieldParams->get('textarea_start', '') . implode(" ", $this->_fieldValues) . $this->_fieldParams->get('textarea_end', '');
		// suppression des balises html si le paramètre est actif
		if ($this->_fieldParams->get('textarea_html', 0))
			$this->_fieldValues = strip_tags($this->_fieldValues);
		// conversion des sauts de ligne
		if ($this->_fieldParams->get('textarea_convert_return', 1))
			$this->_fieldValues = nl2br($this->_fieldValues);

	}
}