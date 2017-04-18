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
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

?>
<tr>
	<th width="5%">
		<?= JHtml::_('grid.checkall');?>
	</th>
	<th width="5%">
		<?= JText::_('COM_FLEXIMPORT_NUM'); ?>
	</th>
	<th width="20%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_TYPE', 't.name', $listDirn, $listOrder); ?>
	</th>
	<th width="10%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_FLEXI_TYPE', 'ft.name', $listDirn, $listOrder); ?>
	</th>
	<th width="10%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_IMPORT_TYPE', 't.import_type', $listDirn, $listOrder); ?>
	</th>
	<th width="25%">
		<?= JText::_('COM_FLEXIMPORT_DESCRIPTION'); ?>
	</th>
	<th width="10%">
		<?= JText::_('COM_FLEXIMPORT_FIELDS'); ?>
	</th>
	<th width="5%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_PUBLISHED', 't.published', $listDirn, $listOrder); ?>
	</th>
	<th width="5%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_ID', 't.id', $listDirn, $listOrder); ?>
	</th>
</tr>