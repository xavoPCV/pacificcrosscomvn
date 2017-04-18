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
require_once(JPATH_COMPONENT . '/classes/plugins/field.php');
class fleximportPluginextendedweblink extends fleximportFieldPlugin {
	public function getPostValues()
	{
		$post = array($this->_field->fname => array());
		$i = 0;
		foreach ($this->_fieldValues as $fieldvalue) {
			$values = explode("::", $fieldvalue);
			if (count($values) > 1) {
				$link = $values[0];
				$title = $values[1];
			} elseif (count($values) == 1) {
				$link = $values[0];
				$title = '';
			}

			$post['custom'][$this->_field->fname][$i]['link'] = $link;
			$post['custom'][$this->_field->fname][$i]['title'] = $title;
			$post['custom'][$this->_field->fname][$i]['hits'] = '0';
			$post['custom'][$this->_field->fname][$i]['class'] = '';
			$post['custom'][$this->_field->fname][$i]['id'] = '';

			$i++;
		}
		return $post;
	}
	public function formatValuesExport()
	{
		foreach ($this->_fieldValues as $idv => $fieldValue) {
			$this->_fieldValues[$idv] = $fieldValue['link'];
		}
	}
}