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
class fleximportPluginweblink extends fleximportFieldPlugin {
	public function formatValues()
	{
		$weblinkSeparator = $this->_fieldParams->get('weblink_separator', '::');
		$weblinkExport = $this->_fieldParams->get('weblink_export', '1');
		$weblinkExplode = $this->_fieldParams->get('weblink_export', '0');
		// si l'on doit explosé les données
		if ($weblinkExplode && count($this->_fieldValues) && $weblinkExport > '1') {
			$nbValues = count($this->_fieldValues);
			// on va reformater les groupes de valeurs
			$LinkValues = array_chunk($this->_fieldValues, (int)($nbValues / (int)$weblinkExport));

			// si on a bien le même nombre de valeur
			if (count($LinkValues[0]) == count($LinkValues[1])) {
				$this->_fieldValues = array();
				// si par défaut on importe pas le compteur, alors on met le compteur à 0
				if ($weblinkExport!='3')
					$LinkValues[2]=array_fill(0,count($LinkValues[0]),0);
				// création des nouvelles valeurs
				foreach ($LinkValues[0] as $indexValue => $LinkValue) {
					$this->_fieldValues[] = trim($LinkValue) . $weblinkSeparator . trim($LinkValues[1][$indexValue]) . $weblinkSeparator . trim($LinkValues[2][$indexValue]);
				}
			}
		}

		// pour chaque link
		foreach ($this->_fieldValues as &$value) {
			$valueLink = explode($weblinkSeparator, $value);
			$value = array();
			// s'il y a un lien et un titre et le compteur
			if (count($valueLink) > 2 && $weblinkExport == 3) {
				$value['link'] = $valueLink[0];
				$value['title'] = $valueLink[1];
				$value['hits'] = $valueLink[2];
				// seulement le lien et le titre
			} elseif (count($valueLink) > 1 && $weblinkExport == 2) {
				$value['link'] = $valueLink[0];
				$value['title'] = $valueLink[1];
				$value['hits'] = 0;
				// uniquement le lien
			} else {
				$value['link'] = $valueLink[0];
				$value['title'] = '';
				$value['hits'] = 0;
			}
		}
	}
	public function formatValuesExport()
	{
		$weblinkSeparator = $this->_fieldParams->get('weblink_separator', '::');
		$weblinkExport = $this->_fieldParams->get('weblink_export', '1');
		foreach ($this->_fieldValues as $idv => $fieldValue) {
			if ($weblinkExport == 3) {
				$this->_fieldValues[$idv] = $fieldValue['link'] . $weblinkSeparator . $fieldValue['title'] . $weblinkSeparator . $fieldValue['hits'];
			} elseif ($weblinkExport == 2) {
				$this->_fieldValues[$idv] = $fieldValue['link'] . $weblinkSeparator . $fieldValue['title'];
			} else {
				$this->_fieldValues[$idv] = $fieldValue['link'];
			}
		}
	}
}