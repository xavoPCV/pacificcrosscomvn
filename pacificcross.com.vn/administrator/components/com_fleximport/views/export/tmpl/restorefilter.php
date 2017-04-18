<?php
/**
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
?>
<table class="adminlist" cellspacing="1">
<tr>
<th class="left" colspan="2"><?= JText::_('COM_FLEXIMPORT_EXPORT_FILTERS');?></th>
</tr>
<?php
foreach ($this->items as $filter){
?>
	<tr>
	<td><a href="javascript:getParent('<?= $filter['open']; ?>')"><?= $filter['name']; ?></a></td>
	<td><a href="<?= $filter['delete']; ?>"><?= JText::_('COM_FLEXIMPORT_EXPORT_FILTER_DELETE');?></a></td>
	</tr>
<?php
}
?>
</table>